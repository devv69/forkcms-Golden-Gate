<?php

/**
 * In this file we store all generic data communication functions
 *
 * @package		backend
 * @subpackage	user_tracker
 *
 * @author		Davy Hellemans <davy@spoon-library.com>
 * @since		2.1
 */
class BackendUserTrackermodel
{
	/**
	 * Fetch the name of the oldest logfile.
	 *
	 * @return	string
	 */
	public static function getOldestLogFilename()
	{
		// init var
		$filename = 'visitors.log';

		// list of files
		$files = SpoonFile::getList(FRONTEND_FILES_PATH . '/user_tracker', '/^visitors\.log\.([0-9]{14})$/');

		/*
		 * The visitors.log has been renamed, because it was too big. The file below, is the
		 * oldest one that we currently have.
		 */
		if(!empty($files)) return $files[0];

		// no files?
		if(SpoonFile::exists(FRONTEND_FILES_PATH . '/user_tracker/visitors.log')) return 'visitors.log';

		// nothing at all?
		return false;
	}
}

?>