<?php

/**
 * Cronjob that processes the logs.
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
		// check for json encoded string
		if(preg_match('/\{\"id\"\:\"(.*)\}/', $value, $matches))
		{
			// decode string
			$data = json_decode($matches[0], true);

			// everything is fine
			if($data !== null && !empty($data['id']) && !empty($data['session_id']) && !empty($data['url']) && !empty($data['status']))
			{
				// general fields
				$record['visitor_identifier'] = $data['id'];
				$record['visitor_session'] = $data['session_id'];
				$record['url'] = $data['url'];
				$record['status'] = $data['status'];
				$record['added_on'] = BackendModel::getUTCDate('Y-m-d H:i:s', strtotime(substr(trim($value), 0, 19)));

				// referrer
				if(isset($data['referrer']))
				{
					// fetch chunks
					$referrer = parse_url($data['referrer']);

					// everything went fine?
					if($referrer !== false || $referrer !== null && !empty($referrer['host']))
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
}

?>