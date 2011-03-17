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

		/*
		 * We're going to build a list of all the values we know of this surfer. If multiple values
		 * for a specific category exist, they will be listed accordingly.
		 */

		// fetch user data
		$data = (array) BackendModel::getDB()->getRecords('SELECT i.name, i.value, i.added_on, UNIX_TIMESTAMP(i.added_on) AS added_on
															FROM user_tracker_data AS i
															WHERE i.id = ?
															ORDER BY i.name ASC, i.added_on DESC', $identifier);

		// loop values
		foreach($data as $record)
		{
			// set name and value
			$visitor['data'][$record['name']]['name'] = $record['name'];
			$visitor['data'][$record['name']]['list'][] = array('value' => @unserialize($record['value']));

			// last update
			if($record['added_on'] > $visitor['lastUpdate']) $visitor['lastUpdate'] = $record['added_on'];
		}

		// init counter
		$i = 0;

		// cleanup array keys
		foreach($visitor['data'] as $key => $value)
		{
			$visitor['data'][$i] = $visitor['data'][$key];
			unset($visitor['data'][$key]);
			$i++;
		}


		/*
		 * We're going to fetch the list of sessions for this visitor.
		 */

		// list of unique sessions
		$sessions = (array) BackendModel::getDB()->getColumn('SELECT i.visitor_session
																FROM user_tracker_pageviews AS i
																WHERE i.visitor_identifier = ?
																GROUP BY i.visitor_session
																ORDER BY i.added_on DESC',
																array($visitor['identifier']));

		// loop unique sessions
		foreach($sessions as $session)
		{
			// fetch session
			$visits = (array) BackendModel::getDB()->getRecords('SELECT i.url, UNIX_TIMESTAMP(i.added_on) AS date, i.status
																FROM user_tracker_pageviews AS i
																WHERE i.visitor_session = ? AND i.visitor_identifier = ?
																ORDER BY i.added_on ASC',
																array($session, $visitor['identifier']));


			// define subset
			$currentSession['dateStart'] = $visits[0]['date'];
			$currentSession['dateStop'] = $visits[count($visits) - 1]['date'];
			$currentSession['visits'] = $visits;

			// add to list
			$visitor['sessions'][] = $currentSession;

			// last visit
			if($visitor['lastVisit'] === false) $visitor['lastVisit'] = $currentSession['dateStop'];
		}

		// last visited date
		if($visitor['lastVisit'] === false) $visitor['lastVisit'] = $visitor['lastUpdate'];

		// we made it!
		return $visitor;
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