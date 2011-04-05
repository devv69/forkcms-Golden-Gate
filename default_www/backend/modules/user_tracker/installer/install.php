<?php

/**
 * Installer for the user tracker module
 *
 * @package		installer
 * @subpackage	user_tracker
 *
 * @author		Davy Hellemans <davy@spoon-library.com>
 * @since		2.1
 */
class UserTrackerInstall extends ModuleInstaller
{
	/**
	 * Install the module
	 *
	 * @return	void
	 */
	protected function execute()
	{
		// load install.sql
		$this->importSQL(dirname(__FILE__) . '/data/install.sql');

		// add module
		$this->addModule('user_tracker', 'The usertracker module.');

		// module rights
		$this->setModuleRights(1, 'user_tracker');

		// action rights
		$this->setActionRights(1, 'user_tracker', 'index');
		$this->setActionRights(1, 'user_tracker', 'details');

		// insert locale (nl)
		$this->insertLocale('nl', 'backend', 'user_tracker', 'lbl', 'DateLastVisit', 'datum laatste bezoek');
		$this->insertLocale('nl', 'backend', 'core', 'lbl', 'UserTracker', 'usertracker');
		$this->insertLocale('nl', 'backend', 'core', 'lbl', 'Unknown', 'onbekend');
		$this->insertLocale('nl', 'backend', 'user_tracker', 'lbl', 'VisitorDetails', 'bezoekersgegevens');
		$this->insertLocale('nl', 'backend', 'user_tracker', 'lbl', 'NumberOfActions', 'aantal acties');
		$this->insertLocale('nl', 'backend', 'user_tracker', 'lbl', 'NumberOfPageviews', 'aantal paginaweergaves');
		$this->insertLocale('nl', 'backend', 'user_tracker', 'lbl', 'NumberOfVisits', 'aantal bezoeken');


		// insert locale (en)
		$this->insertLocale('en', 'backend', 'user_tracker', 'lbl', 'DateLastVisit', 'date last visit');
		$this->insertLocale('en', 'backend', 'core', 'lbl', 'UserTracker', 'usertracker');
		$this->insertLocale('en', 'backend', 'core', 'lbl', 'Unknown', 'unknown');
		$this->insertLocale('en', 'backend', 'user_tracker', 'lbl', 'VisitorDetails', 'visitor details');
		$this->insertLocale('en', 'backend', 'user_tracker', 'lbl', 'NumberOfActions', 'number of actions');
		$this->insertLocale('en', 'backend', 'user_tracker', 'lbl', 'NumberOfPageviews', 'number of pageviews');
		$this->insertLocale('en', 'backend', 'user_tracker', 'lbl', 'NumberOfVisits', 'number of visits');
	}
}

?>
