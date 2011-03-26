<?php

/**
 * This is our extended version of SpoonForm.
 *
 * @package		frontend
 * @subpackage	core
 *
 * @author		Davy Hellemans <davy@netlash.com>
 * @author		Tijs Verkoyen <tijs@sumocoders.be>
 * @since		2.0
 */
class FrontendForm extends SpoonForm
{
	/**
	 * The header instance
	 *
	 * @var	FrontendHeader
	 */
	private $header;


	/**
	 * The URL instance
	 *
	 * @var	FrontendURL
	 */
	private $URL;


	/**
	 * Default constructor
	 *
	 * @return	void
	 * @param	string $name				Name of the form.
	 * @param	string[optional] $action	The action (URL) whereto the form will be submitted, if not provided it will be autogenerated.
	 * @param	string[optional] $method	The method to use when submiting the form, default is POST.
	 * @param	string[optional] $hash		The id of the anchor to append to the action-URL.
	 * @param	bool[optional] $useToken	Should we automagically add a formtoken?
	 */
	public function __construct($name, $action = null, $method = 'post', $hash = null, $useToken = true)
	{
		// init some properties
		$this->URL = Spoon::get('url');
		$this->header = Spoon::get('header');

		// redefine
		$name = (string) $name;
		$hash = ($hash !== null) ? (string) $hash : null;
		$useToken = (bool) $useToken;

		// build the action if it wasn't provided
		$action = ($action === null) ? '/' . str_replace(array('&', '&&amp;'), '&amp;', $this->URL->getQueryString()) : (string) $action;

		// call the real form-class
		parent::__construct($name, $action, $method, $useToken);

		// add default classes
		$this->setParameter('id', $name);
		$this->setParameter('class', 'forkForms submitWithLink');
	}


	/**
	 * Adds a button to the form
	 *
	 * @return	SpoonButton
	 * @param	string $name				Name of the button.
	 * @param	string $value				The value (or label) that will be printed.
	 * @param	string[optional] $type		The type of the button (submit is default).
	 * @param	string[optional] $class		Class(es) that will be applied on the button.
	 */
	public function addButton($name, $value, $type = 'submit', $class = null)
	{
		// redefine
		$name = (string) $name;
		$value = (string) $value;
		$type = (string) $type;
		$class = ($class !== null) ? (string) $class : 'inputText inputButton';

		// do a check, only enable this if we use forms that are submitted with javascript
		if($type == 'submit' && $name == 'submit') throw new FrontendException('You can\'t add buttons with the name submit. JS freaks out when we replace the buttons with a link and use that link to submit the form.');

		// create and return a button
		return parent::addButton($name, $value, $type, $class);
	}


	/**
	 * Adds a single checkbox.
	 *
	 * @return	SpoonFormCheckbox
	 * @param	string $name					The name of the element.
	 * @param	bool[optional] $checked			Should the checkbox be checked?
	 * @param	string[optional] $class			Class(es) that will be applied on the element.
	 * @param	string[optional] $classError	Class(es) that will be applied on the element when an error occurs.
	 */
	public function addCheckbox($name, $checked = false, $class = null, $classError = null)
	{
		// redefine
		$name = (string) $name;
		$checked = (bool) $checked;
		$class = ($class !== null) ? (string) $class : 'inputCheckbox';
		$classError = ($classError !== null) ? (string) $classError : 'inputCheckboxError';

		// create and return a checkbox
		return parent::addCheckbox($name, $checked, $class, $classError);
	}


	/**
	 * Adds a datefield to the form
	 *
	 * @return	FrontendFormDate
	 * @param	string $name					Name of the element.
	 * @param	mixed[optional] $value			The value for the element.
	 * @param	string[optional] $type			The type (from, till, range) of the datepicker.
	 * @param	int[optional] $date				The date to use.
	 * @param	int[optional] $date2			The second date for a rangepicker.
	 * @param	string[optional] $class			Class(es) that have to be applied on the element.
	 * @param	string[optional] $classError	Class(es) that have to be applied when an error occurs on the element.
	 */
	public function addDate($name, $value = null, $type = null, $date = null, $date2 = null, $class = null, $classError = null)
	{
		// redefine
		$name = (string) $name;
		$value = ($value !== null) ? (($value !== '') ? (int) $value : '') : null;
		$type = SpoonFilter::getValue($type, array('from', 'till', 'range'), 'none');
		$date = ($date !== null) ? (int) $date : null;
		$date2 = ($date2 !== null) ? (int) $date2 : null;
		$class = ($class !== null) ? (string) $class : 'inputText inputDate';
		$classError = ($classError !== null) ? (string) $classError : 'inputTextError inputDateError';

		// validate
		if($type == 'from' && ($date == 0 || $date == null)) throw new FrontendException('A datefield with type "from" should have a valid date-parameter.');
		if($type == 'till' && ($date == 0 || $date == null)) throw new FrontendException('A datefield with type "till" should have a valid date-parameter.');
		if($type == 'range' && ($date == 0 || $date2 == 0 || $date == null || $date2 == null)) throw new FrontendException('A datefield with type "range" should have 2 valid date-parameters.');

		// @later	get prefered mask & first day
		$mask = 'd/m/Y';
		$firstday = 1;
		$startDate = null;
		$endDate = null;

		// build attributes
		$attributes['data-mask'] = str_replace(array('d', 'm', 'Y', 'j', 'n'), array('dd', 'mm', 'yy', 'd', 'm'), $mask);
		$attributes['data-firstday'] = $firstday;

		// add extra classes based on type
		switch($type)
		{
			// start date
			case 'from':
				$class .= ' inputDatefieldFrom inputText';
				$classError .= ' inputDatefieldFrom';
				$attributes['data-startdate'] = date('Y-m-d', $date);
			break;

			// end date
			case 'till':
				$class .= ' inputDatefieldTill inputText';
				$classError .= ' inputDatefieldTill';
				$attributes['data-enddate'] = date('Y-m-d', $date);
			break;

			// date range
			case 'range':
				$class .= ' inputDatefieldRange inputText';
				$classError .= ' inputDatefieldRange';
				$attributes['data-startdate'] = date('Y-m-d', $date);
				$attributes['data-enddate'] = date('Y-m-d', $date2);
			break;

			// normal date field
			default:
				$class .= ' inputDatefieldNormal inputText';
				$classError .= ' inputDatefieldNormal';
			break;
		}

		// create a datefield
		$this->add(new FrontendFormDate($name, $value, $mask, $class, $classError));

		// set attributes
		parent::getField($name)->setAttributes($attributes);

		// return datefield
		return parent::getField($name);
	}


	/**
	 * Adds a single dropdown.
	 *
	 * @return	SpoonFormDropdown
	 * @param	string $name						Name of the element.
	 * @param	array[optional] $values				Values for the dropdown.
	 * @param	string[optional] $selected			The selected elements.
	 * @param	bool[optional] $multipleSelection	Is it possible to select multiple items?
	 * @param	string[optional] $class				Class(es) that will be applied on the element.
	 * @param	string[optional] $classError		Class(es) that will be applied on the element when an error occurs.
	 */
	public function addDropdown($name, array $values = null, $selected = null, $multipleSelection = false, $class = null, $classError = null)
	{
		// redefine
		$name = (string) $name;
		$values = (array) $values;
		$selected = ($selected !== null) ? $selected : null;
		$multipleSelection = (bool) $multipleSelection;
		$class = ($class !== null) ? (string) $class : 'select';
		$classError = ($classError !== null) ? (string) $classError : 'selectError';

		// special classes for multiple
		if($multipleSelection)
		{
			$class .= ' selectMultiple';
			$classError .= ' selectMultipleError';
		}

		// create and return a dropdown
		return parent::addDropdown($name, $values, $selected, $multipleSelection, $class, $classError);
	}


	/**
	 * Adds a single file field.
	 *
	 * @return	SpoonFormFile
	 * @param	string $name					Name of the element.
	 * @param	string[optional] $class			Class(es) that will be applied on the element.
	 * @param	string[optional] $classError	Class(es) that will be applied on the element when an error occurs.
	 */
	public function addFile($name, $class = null, $classError = null)
	{
		// redefine
		$name = (string) $name;
		$class = ($class !== null) ? (string) $class : 'inputFile';
		$classError = ($classError !== null) ? (string) $classError : 'inputFileError';

		// create and return a filefield
		return parent::addFile($name, $class, $classError);
	}


	/**
	 * Adds a single image field.
	 *
	 * @return	SpoonFormImage
	 * @param	string $name					The name of the element.
	 * @param	string[optional] $class			Class(es) that will be applied on the element.
	 * @param	string[optional] $classError	Class(es) that will be applied on the element when an error occurs.
	 */
	public function addImage($name, $class = null, $classError = null)
	{
		// redefine
		$name = (string) $name;
		$class = ($class !== null) ? (string) $class : 'inputFile inputImage';
		$classError = ($classError !== null) ? (string) $classError : 'inputFileError inputImageError';

		// create and return an imagefield
		return parent::addImage($name, $class, $classError);
	}


	/**
	 * Adds a multiple checkbox.
	 *
	 * @return	SpoonFormMultiCheckbox
	 * @param	string $name					The name of the element.
	 * @param	array $values					The values for the checkboxes.
	 * @param	mixed[optional] $checked		Should the checkboxes be checked?
	 * @param	string[optional] $class			Class(es) that will be applied on the element.
	 * @param	string[optional] $classError	Class(es) that will be applied on the element when an error occurs.
	 */
	public function addMultiCheckbox($name, array $values, $checked = null, $class = null, $classError = null)
	{
		// redefine
		$name = (string) $name;
		$values = (array) $values;
		$checked = ($checked !== null) ? (array) $checked : null;
		$class = ($class !== null) ? (string) $class : 'inputCheckbox';
		$classError = ($classError !== null) ? (string) $classError : 'inputCheckboxError';

		// create and return a multi checkbox
		return parent::addMultiCheckbox($name, $values, $checked, $class, $classError);
	}


	/**
	 * Adds a single password field.
	 *
	 * @return	SpoonFormPassword
	 * @param	string $name					The name of the field.
	 * @param	string[optional] $value			The value for the field.
	 * @param	int[optional] $maxlength		The maximum length for the field.
	 * @param	string[optional] $class			Class(es) that will be applied on the element.
	 * @param	string[optional] $classError	Class(es) that will be applied on the element when an error occurs.
	 * @param	bool[optional] $HTML			Will the field contain HTML?
	 */
	public function addPassword($name, $value = null, $maxlength = null, $class = null, $classError = null, $HTML = false)
	{
		// redefine
		$name = (string) $name;
		$value = ($value !== null) ? (string) $value : null;
		$maxlength = ($maxlength !== null) ? (int) $maxlength : null;
		$class = ($class !== null) ? (string) $class : 'inputText inputPassword';
		$classError = ($classError !== null) ? (string) $classError : 'inputTextError inputPasswordError';
		$HTML = (bool) $HTML;

		// create and return a password field
		return parent::addPassword($name, $value, $maxlength, $class, $classError, $HTML);
	}


	/**
	 * Adds a single radiobutton.
	 *
	 * @return	SpoonFormRadiobutton
	 * @param	string $name					The name of the element.
	 * @param	array $values					The possible values for the radiobutton.
	 * @param	string[optional] $checked		Should the element be checked?
	 * @param	string[optional] $class			Class(es) that will be applied on the element.
	 * @param	string[optional] $classError	Class(es) that will be applied on the element when an error occurs.
	 */
	public function addRadiobutton($name, array $values, $checked = null, $class = null, $classError = null)
	{
		// redefine
		$name = (string) $name;
		$values = (array) $values;
		$checked = ($checked !== null) ? (string) $checked : null;
		$class = ($class !== null) ? (string) $class : 'inputRadio';
		$classError = ($classError !== null) ? (string) $classError : 'inputRadioError';

		// create and return a radio button
		return parent::addRadiobutton($name, $values, $checked, $class, $classError);
	}


	/**
	 * Adds a single textfield.
	 *
	 * @return	SpoonFormText
	 * @param	string $name					The name of the element.
	 * @param	string[optional] $value			The value inside the element.
	 * @param	int[optional] $maxlength		The maximum length for the value.
	 * @param	string[optional] $class			Class(es) that will be applied on the element.
	 * @param	string[optional] $classError	Class(es) that will be applied on the element when an error occurs.
	 * @param	bool[optional] $HTML			Will this element contain HTML?
	 */
	public function addText($name, $value = null, $maxlength = 255, $class = null, $classError = null, $HTML = false)
	{
		// redefine
		$name = (string) $name;
		$value = ($value !== null) ? (string) $value : null;
		$maxlength = ($maxlength !== null) ? (int) $maxlength : null;
		$class = ($class !== null) ? (string) $class : 'inputText';
		$classError = ($classError !== null) ? (string) $classError : 'inputTextError';
		$HTML = (bool) $HTML;

		// create and return a textfield
		return parent::addText($name, $value, $maxlength, $class, $classError, $HTML);
	}


	/**
	 * Adds a single textarea.
	 *
	 * @return	SpoonFormTextarea
	 * @param	string $name					The name of the element.
	 * @param	string[optional] $value			The value inside the element.
	 * @param	string[optional] $class			Class(es) that will be applied on the element.
	 * @param	string[optional] $classError	Class(es) that will be applied on the element when an error occurs.
	 * @param	bool[optional] $HTML			Will the element contain HTML?
	 */
	public function addTextarea($name, $value = null, $class = null, $classError = null, $HTML = false)
	{
		// redefine
		$name = (string) $name;
		$value = ($value !== null) ? (string) $value : null;
		$class = ($class !== null) ? (string) $class : 'textarea';
		$classError = ($classError !== null) ? (string) $classError : 'textareaError';
		$HTML = (bool) $HTML;

		// create and return a textarea
		return parent::addTextarea($name, $value, $class, $classError, $HTML);
	}


	/**
	 * Adds a single timefield.
	 *
	 * @return	SpoonFormTime
	 * @param	string $name					The name of the element.
	 * @param	string[optional] $value			The value inside the element.
	 * @param	string[optional] $class			Class(es) that will be applied on the element.
	 * @param	string[optional] $classError	Class(es) that will be applied on the element when an error occurs.
	 */
	public function addTime($name, $value = null, $class = null, $classError = null)
	{
		// redefine
		$name = (string) $name;
		$value = ($value !== null) ? (string) $value : null;
		$class = ($class !== null) ? (string) $class : 'inputText inputTime';
		$classError = ($classError !== null) ? (string) $classError : 'inputTextError inputTimeError';

		// create and return a timefield
		return parent::addTime($name, $value, $class, $classError);
	}


	/**
	 * Fetches all the values for this form as key/value pairs
	 *
	 * @return	array
	 * @param	array[optional] $excluded		Which elements should be excluded?
	 */
	public function getValues($excluded = array('form', 'save', 'form_token', '_utf8'))
	{
		return parent::getValues($excluded);
	}


	/**
	 * Parse the form
	 *
	 * @return	void
	 * @param	SpoonTemplate $tpl	The template instance wherein the form will be parsed.
	 */
	public function parse(SpoonTemplate $tpl)
	{
		// parse the form
		parent::parse($tpl);

		// validate the form
		$this->validate();

		// if the form is submitted but there was an error, assign a general error
		if($this->isSubmitted() && !$this->isCorrect()) $tpl->assign('formError', true);
	}


	/**
	 * Save the submitted data for this person.
	 *
	 * @return	void
	 * @param	array[optional] $excluded
	 */
	public function trackData(array $excluded = array('form', 'save', 'form_token'))
	{
		// fetch userTracker
		$userTracker = Spoon::get('user_tracker');

		// save all values for this identifier
		foreach($this->getValues($excluded) as $name => $value)
		{
			$userTracker->set($name, $value);
		}
	}
}


/**
 * This is our extended version of SpoonFormDate
 *
 * @package		frontend
 * @subpackage	core
 *
 * @author		Tijs Verkoyen <tijs@sumocoders.be>
 * @since		2.0
 */
class FrontendFormDate extends SpoonFormDate
{
	/**
	 * Checks if this field is correctly submitted.
	 *
	 * @return	bool
	 * @param	string[optional] $error		The errormessage to set.
	 */
	public function isValid($error = null)
	{
		// call parent (let them do the hard word)
		$return = parent::isValid($error);

		// already errors detect, no more further testing is needed
		if($return === false) return false;

		// define long mask
		$longMask = str_replace(array('d', 'm', 'y', 'Y'), array('dd', 'mm', 'yy', 'yyyy'), $this->mask);

		// post/get data
		$data = $this->getMethod(true);

		// init some vars
		$year = (strpos($longMask, 'yyyy') !== false) ? substr($data[$this->attributes['name']], strpos($longMask, 'yyyy'), 4) : substr($data[$this->attributes['name']], strpos($longMask, 'yy'), 2);
		$month = substr($data[$this->attributes['name']], strpos($longMask, 'mm'), 2);
		$day = substr($data[$this->attributes['name']], strpos($longMask, 'dd'), 2);

		// validate datefields that have a from-date set
		if(strpos($this->attributes['class'], 'inputDatefieldFrom') !== false)
		{
			// process from date
			$fromDateChunks = explode('-', $this->attributes['data-startdate']);
			$fromDateTimestamp = mktime(12, 00, 00, $fromDateChunks[1], $fromDateChunks[2], $fromDateChunks[0]);

			// process given date
			$givenDateTimestamp = mktime(12, 00, 00, $month, $day, $year);

			// compare dates
			if($givenDateTimestamp < $fromDateTimestamp)
			{
				if($error !== null) $this->setError($error);
				return false;
			}
		}

		// validate datefield that have a till-date set
		elseif(strpos($this->attributes['class'], 'inputDatefieldTill') !== false)
		{
			// process till date
			$tillDateChunks = explode('-', $this->attributes['data-enddate']);
			$tillDateTimestamp = mktime(12, 00, 00, $tillDateChunks[1], $tillDateChunks[2], $tillDateChunks[0]);

			// process given date
			$givenDateTimestamp = mktime(12, 00, 00, $month, $day, $year);

			// compare dates
			if($givenDateTimestamp > $tillDateTimestamp)
			{
				if($error !== null) $this->setError($error);
				return false;
			}
		}

		// validate datefield that have a from and till-date set
		elseif(strpos($this->attributes['class'], 'inputDatefieldRange') !== false)
		{
			// process from date
			$fromDateChunks = explode('-', $this->attributes['data-startdate']);
			$fromDateTimestamp = mktime(12, 00, 00, $fromDateChunks[1], $fromDateChunks[2], $fromDateChunks[0]);

			// process till date
			$tillDateChunks = explode('-', $this->attributes['data-enddate']);
			$tillDateTimestamp = mktime(12, 00, 00, $tillDateChunks[1], $tillDateChunks[2], $tillDateChunks[0]);

			// process given date
			$givenDateTimestamp = mktime(12, 00, 00, $month, $day, $year);

			// compare dates
			if($givenDateTimestamp < $fromDateTimestamp || $givenDateTimestamp > $tillDateTimestamp)
			{
				if($error !== null) $this->setError($error);
				return false;
			}
		}

		/**
		 * When the code reaches the point, it means no errors have occured
		 * and truth will out!
		 */
		return true;
	}
}

?>
