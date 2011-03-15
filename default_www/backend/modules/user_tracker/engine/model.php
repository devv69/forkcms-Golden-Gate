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


	/**
	 * Fetch all know details about this visitor.
	 *
	 * @return	array
	 * @param	string $identifier
	 */
	public static function getVisitor($identifier)
	{
		// init vars
		$identifier = (string) $identifier;
		$visitor = array();
		$visitor['identifier'] = $identifier;
		$visitor['lastUpdate'] = false;
		$visitor['lastVisit'] = false;

		// fetch user data
		$data = BackendModel::getDB()->getRecords('SELECT i.name, i.value, i.added_on, UNIX_TIMESTAMP(i.added_on) AS added_on_timestamp
													FROM user_tracker_data AS i
													WHERE i.id = ?
													ORDER BY i.name ASC, i.added_on DESC', $identifier);


		foreach($data as $record)
		{
			if(!isset($visitor['values'][$record['name']]['current'])) $visitor['values'][$record['name']]['current'] = @unserialize($record['value']);
			$visitor['values'][$record['name']]['list'][] = array($record['name'] => @unserialize($record['value']));
		}

		// @todo remove this
		Spoon::dump($visitor);
	}


	/**
	 * Is there an identifier with this id that contains data?
	 *
	 * @return	bool
	 * @param	string $identifier
	 */
	public static function hasData($identifier)
	{
		return (bool) BackendModel::getDB()->getVar('SELECT COUNT(id) FROM user_tracker_data WHERE id = ?', (string) $identifier);
	}
}

?>