<?php

/**
 * Cronjob that imports the log files.
 *
 * @package		backend
 * @subpackage	user_tracker
 *
 * @author		Davy Hellemans <davy@spoon-library.com>
 * @since		2.1
 */
class BackendUserTrackerCronjobProcessLogs extends BackendBaseCronjob
{
	/**
	 * Log instance.
	 *
	 * @var	SpoonLog
	 */
	protected $log;


	/**
	 * Cleans the database.
	 *
	 * @return	void
	 */
	protected function cleanupDatabase()
	{
		// everything older than 4 weeks is deleted
		BackendModel::getDB(true)->delete('user_tracker_pageviews', 'added_on < ?', array(SpoonDate::getDate('Y-m-d H:i:s', strtotime('-4 week'))));
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

		// set busy file
		$this->setBusyFile();

		// create instance of log
		$this->log = new SpoonLog('visitors', FRONTEND_FILES_PATH . '/user_tracker');

		// process a few items from the log file
		$this->process();

		// get rid of old records
		$this->cleanupDatabase();

		// remove busy file
		$this->clearBusyFile();
	}


	/**
	 * Process a limited number of log entries.
	 *
	 * @return	void
	 */
	protected function process()
	{
		// file to process
		$log = BackendUserTrackermodel::getOldestLogFilename();

		// we have a decent log file
		if($log !== false)
		{
			// rotate needed (only if the oldest file is not archived)
			if($log == 'visitors.log')
			{
				// rotate
				$this->log->rotate();

				// file to process
				$log = BackendUserTrackermodel::getOldestLogFilename();
			}

			// open file
			$handle = @fopen(FRONTEND_FILES_PATH . '/user_tracker/' . $log, 'r+');

			// no problems detected
			if($handle)
			{
				// read file
				while(!feof($handle))
				{
					// fetch line
					$line = fgets($handle, 4096);

					// process
					$this->processLine($line);
				}

				// close stream
				fclose($handle);

				// delete file
				SpoonFile::delete(FRONTEND_FILES_PATH . '/user_tracker/' . $log);
			}
		}
	}


	/**
	 * Writes this line to the appropriate tables.
	 *
	 * @return	void
	 * @param	string $value
	 */
	protected function processLine($value)
	{
		// init vars
		$visitorIdentifier = null;
		$visitorSession = null;
		$url = null;
		$referrer = null;
		$status = null;

		// fetch vars from line
		if(preg_match('/\[id\]([a-z0-9]{32})\[\/id\]/', $value, $matches)) $visitorIdentifier = $matches[1];
		if(preg_match('/\[session_id\]([a-z0-9]{32})\[\/session_id\]/', $value, $matches)) $visitorSession = $matches[1];
		if(preg_match('/\[url\](.+)\[\/url\]/', $value, $matches)) $url = $matches[1];
		if(preg_match('/\[referrer](.+)\[\/referrer\]/', $value, $matches)) $referrer = $matches[1];
		if(preg_match('/\[status]([0-9]{3})\[\/status\]/', $value, $matches)) $status = $matches[1];

		// required fields are present
		if($visitorIdentifier !== null && $visitorSession !== null && $url !== null && $status !== null)
		{
			// general fields
			$record['visitor_identifier'] = $visitorIdentifier;
			$record['visitor_session'] = $visitorSession;
			$record['url'] = $url;
			$record['status'] = $status;
			$record['added_on'] = date('Y-m-d H:i:s', strtotime(substr(trim($value), 0, 19)));

			// referrer
			if($referrer !== null)
			{
				// fetch chunks
				$referrer = parse_url($referrer);

				// everything went fine?
				if($referrer !== false || $referrer !== null)
				{
					$record['referrer_host'] = $referrer['host'];
					$record['referrer_path'] = (isset($referrer['path'])) ? $referrer['path'] : null;
					$record['referrer_query'] = (isset($referrer['query'])) ? $referrer['query'] : null;
				}
			}

			// insert into db
			BackendModel::getDB()->insert('user_tracker_pageviews', $record);

			// reset
			unset($record);
		}
	}
}

?>