<?php

/**
 * This is the detail-action, it will display all the information about this surfer.
 *
 * @package		backend
 * @subpackage	user_tracker
 *
 * @author		Davy Hellemans <davy@spoon-library.com>
 * @since		2.1
 */
class BackendUserTrackerDetails extends BackendBaseAction
{
	/**
	 * Id.
	 *
	 * @var	string
	 */
	private $id;


	/**
	 * Array holding all the values for this user.
	 *
	 * @var	array
	 */
	private $record;


	/**
	 * Execute the action
	 *
	 * @return	void
	 */
	public function execute()
	{
		// get parameters
		$this->id = $this->getParameter('id');

		// does the item exists
		if($this->id !== null && BackendUserTrackermodel::hasData($this->id))
		{
			// call parent, this will probably add some general CSS/JS or other required files
			parent::execute();

			// get all data for the item we want to edit
			$this->getData();

			// parse the datagrid
			$this->parse();

			// display the page
			$this->display();
		}

		// no item found, throw an exception, because somebody is fucking with our URL
		else $this->redirect(BackendModel::createURLForAction('index') . '&error=non-existing');
	}


	/**
	 * Get the data
	 * If a revision-id was specified in the URL we load the revision and not the actual data.
	 *
	 * @return	void
	 */
	private function getData()
	{
		// get the record
		$this->record = (array) BackendUserTrackermodel::getVisitor($this->id);

		// no item found, throw an exceptions, because somebody is fucking with our URL
		if(empty($this->record)) $this->redirect(BackendModel::createURLForAction('index') . '&error=non-existing');
	}


	/**
	 * Parse the form
	 *
	 * @return	void
	 */
	protected function parse()
	{
		// map custom modifier
		$this->tpl->mapModifier('tolabel', array(__CLASS__, 'toLabel'));

		// record
		$this->tpl->assign('visitor', $this->record);
	}


	/**
	 * Convert this string into a well formed label.
	 *
	 * @return	string
	 * @param	string $value
	 */
	public static function toLabel($value)
	{
		return ucfirst(BL::lbl(SpoonFilter::toCamelCase($value, '_', false)));
	}
}

?>