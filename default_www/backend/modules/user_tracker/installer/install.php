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

		// add 'blog' as a module
		$this->addModule('user_tracker', 'The blog module.');

		// module rights
		$this->setModuleRights(1, 'user_tracker');

		// action rights
		$this->setActionRights(1, 'user_tracker', 'index');
		$this->setActionRights(1, 'user_tracker', 'details');

		// insert locale (nl) @todo this needs to be revised
		$this->insertLocale('nl', 'backend', 'user_tracker', 'lbl', 'UniqueId', 'uniek id');
		$this->insertLocale('nl', 'backend', 'user_tracker', 'lbl', 'LastDataUpdate', 'laatste aanpassing gegevens');
		$this->insertLocale('nl', 'backend', 'user_tracker', 'lbl', 'LastVisit', 'laatste bezoek');
		$this->insertLocale('nl', 'backend', 'user_tracker', 'lbl', 'City', 'stad');
		$this->insertLocale('nl', 'backend', 'user_tracker', 'lbl', 'Gender', 'geslacht');
		$this->insertLocale('nl', 'backend', 'user_tracker', 'lbl', 'Street', 'straat');
		$this->insertLocale('nl', 'backend', 'user_tracker', 'lbl', 'Data', 'gegevens');
		$this->insertLocale('nl', 'backend', 'user_tracker', 'lbl', 'Birthday', 'verjaardag');


		// insert locale (en) @todo this needs to be revised
		$this->insertLocale('en', 'backend', 'user_tracker', 'lbl', 'UniqueId', 'uniek id');
		$this->insertLocale('en', 'backend', 'user_tracker', 'lbl', 'LastDataUpdate', 'last data update');
		$this->insertLocale('en', 'backend', 'user_tracker', 'lbl', 'LastVisit', 'last visit');
		$this->insertLocale('en', 'backend', 'user_tracker', 'lbl', 'City', 'city');
		$this->insertLocale('en', 'backend', 'user_tracker', 'lbl', 'Gender', 'gender');
		$this->insertLocale('en', 'backend', 'user_tracker', 'lbl', 'Street', 'street');
		$this->insertLocale('en', 'backend', 'user_tracker', 'lbl', 'Data', 'data');
		$this->insertLocale('en', 'backend', 'user_tracker', 'lbl', 'Birthday', 'birthday');
	}
}

?>
