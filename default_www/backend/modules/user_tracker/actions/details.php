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

			// get all data
			$this->getData();

			// parse the page
			$this->parse();

			// display the page
			$this->display();
		}

		// no item found, throw an exception, because somebody is fucking with our URL
		else $this->redirect(BackendModel::createURLForAction('index') . '&error=non-existing');
	}


	/**
	 * Get the data
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
	 * Parse the page.
	 *
	 * @return	void
	 */
	protected function parse()
	{
		// record
		$this->tpl->assign('visitor', $this->record);
	}
}

?>