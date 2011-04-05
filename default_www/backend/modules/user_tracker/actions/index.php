<?php

/**
 * This page show the overview of tracked user that have left some data.
 *
 * @package		backend
 * @subpackage	user_tracker
 *
 * @author		Davy Hellemans <davy@spoon-library.com>
 * @since		2.1
 */
class BackendUserTrackerIndex extends BackendBaseActionIndex
{
	/**
	 * Filter variables
	 *
	 * @var	array
	 */
	private $filter;


	/**
	 * Form
	 *
	 * @var BackendForm
	 */
	private $frm;


	/**
	 * Builds the query for this datagrid
	 *
	 * @return	array		An array with two elements containing the query and its parameters.
	 */
	private function buildQuery()
	{
		// init var
		$parameters = array();

		// build query
		$query = 'SELECT i.id,
						UNIX_TIMESTAMP(i.added_on) AS added_on,
						i2.value AS email,
						i3.value AS name,
						COUNT(DISTINCT(utp.visitor_session)) AS num_visits,
						COUNT(DISTINCT(utp.id)) AS num_pageviews,
						COUNT(DISTINCT(i.added_on)) AS num_actions
				FROM user_tracker_data AS i
				LEFT OUTER JOIN user_tracker_pageviews AS utp ON utp.visitor_identifier = i.id
				LEFT OUTER JOIN user_tracker_data AS i2 ON i2.name = ? AND i2.id = i.id
				LEFT OUTER JOIN user_tracker_data AS i3 ON i3.name = ? AND i3.id = i.id
				GROUP BY i.id
				ORDER BY i.added_on DESC';

		// search for email
		$parameters[] = 'email';
		$parameters[] = 'name';

		// query + parameters
		return array($query, $parameters);
	}


	/**
	 * Execute the action
	 *
	 * @return	void
	 */
	public function execute()
	{
		// call parent, this will probably add some general CSS/JS or other required files
		parent::execute();

		// set filter
		//$this->setFilter();

		// load form
		//$this->loadForm();

		// load datagrids
		$this->loadDataGrid();

		// parse page
		$this->parse();

		// display the page
		$this->display();
	}


	/**
	 * Load the datagrid
	 *
	 * @return	void
	 */
	private function loadDataGrid()
	{
		// fetch query and parameters
		list($query, $parameters) = $this->buildQuery();

		// create datagrid
		$this->datagrid = new BackendDataGridDB($query, $parameters);

		// labels
		$this->datagrid->setHeaderLabels(array('added_on' => ucfirst(BL::lbl('DateLastVisit'))));

		// column functions
		$this->datagrid->setColumnFunction(array(__CLASS__, 'setEmail'), '[email]', 'email', true);
		$this->datagrid->setColumnFunction(array(__CLASS__, 'setName'), '[name]', 'name', true);
		$this->datagrid->setColumnFunction(array('BackendDatagridFunctions', 'getLongDate'), array('[added_on]'), 'added_on', true);

		// add column
		$this->datagrid->addColumn('detail', null, 'Details', BackendModel::createURLForAction('details') . '&id=[id]');
	}


	/**
	 * Parse & display the page
	 *
	 * @return	void
	 */
	private function parse()
	{
		// parse datagrid
		$this->tpl->assign('datagrid', ($this->datagrid->getNumResults() != 0) ? $this->datagrid->getContent() : false);

		// parse paging & sorting
		$this->tpl->assign('offset', (int) $this->datagrid->getOffset());
		$this->tpl->assign('order', (string) $this->datagrid->getOrder());
		$this->tpl->assign('sort', (string) $this->datagrid->getSort());

		// parse filter
		//$this->tpl->assign($this->filter);
	}


	/**
	 * Unserializes data if it was set.
	 *
	 * @return	string
	 * @param	string $value
	 */
	public static function setEmail($value)
	{
		// redefine value
		$value = ($value != '') ? @unserialize($value) : '';

		// add mailto
		return ($value != '') ? '<a href="mailto:' . $value . '">' . $value . '</a>' : '';
	}


	/**
	 * Sets the filter based on the $_GET array.
	 *
	 * @return	void
	 */
	private function setFilter()
	{
		return; // @todo adjust
		$this->filter['language'] = (isset($_GET['language'])) ? $this->getParameter('language') : BL::getWorkingLanguage();
		$this->filter['application'] = $this->getParameter('application');
		$this->filter['module'] = $this->getParameter('module');
		$this->filter['type'] = $this->getParameter('type');
		$this->filter['name'] = $this->getParameter('name');
		$this->filter['value'] = $this->getParameter('value');
	}


	/**
	 * Unserializes data if it was set.
	 *
	 * @return	string
	 * @param	string $value
	 */
	public static function setName($value)
	{
		return ($value != '') ? @unserialize($value) : '';
	}
}

?>