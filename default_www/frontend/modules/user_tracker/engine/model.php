<?php

/**
 * In this file we store all generic functions that we will be using in the user tracker module
 *
 * @package		frontend
 * @subpackage	user_tracker
 *
 * @author		Davy Hellemans <davy@spoon-library.com>
 * @since		2.0
 */
class FrontendUserTracker
{
	/**
	 * Holds the identifier for the current user.
	 *
	 * @var	string
	 */
	private $identifier;


	/**
	 * Log object.
	 *
	 * @var SpoonLog
	 */
	private $log;


	/**
	 * List of cached values.
	 *
	 * @var	array
	 */
	private $values = array();


	/**
	 * Class constructor.
	 *
	 * @return	FrontendUserTracker
	 */
	public function __construct()
	{
		// add ourself to the registry
		Spoon::set('user_tracker', $this);

		// init log
		$this->log = new SpoonLog('visitors', FRONTEND_FILES_PATH . '/user_tracker');
		$this->log->setMaxLogSize(55);
	}


	/**
	 * Fetch data for a specific identifier.
	 *
	 * @return	mixed
	 * @param	string $name
	 * @param	string[optional] $identifier
	 */
	public function get($name, $identifier = null)
	{
		// redefine
		$name = (string) $name;
		$identifier = ($identifier !== null) ? (string) $identifier : $this->getIdentifier();

		// in local cache
		if(array_key_exists($identifier, $this->values) && array_key_exists($name, $this->values[$identifier]))
		{
			return $this->values[$identifier][$name];
		}

		// fetch data
		$originalData = (string) FrontendModel::getDB()->getVar('SELECT value
																FROM user_tracker_data
																WHERE id = ? AND name = ?
																LIMIT 1',
																array($identifier, $name));

		// actual data
		$actualData = @unserialize($originalData);

		// set data
		$this->values[$identifier][$name] = ($actualData === false && $originalData != 'b:0;') ? null : $actualData;

		// return actual data
		return $this->values[$identifier][$name];
	}


	/**
	 * Fetch the identifier for this visitor.
	 *
	 * @return	string
	 */
	public function getIdentifier()
	{
		// no identifier defined
		if($this->identifier === null) $this->setIdentifier();

		// fetch identifier
		return $this->identifier;
	}


	/**
	 * Checks if this identifier is considered valid.
	 *
	 * @return	bool
	 * @param	string $value
	 */
	public function isValidIdentifier($value)
	{
		return (SpoonFilter::isAlphaNumeric($value) && strlen($value) == 32);
	}


	/**
	 * Writes to the log.
	 *
	 * @return	FrontendUserTracker
	 */
	public function logPageview()
	{
		// init var
		$referrer = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : null;
		$status = Spoon::get('page')->getStatusCode();

		// create message
		$message = '[id]' . $this->getIdentifier() . '[/id]';
		$message .= ' [session_id]' . SpoonSession::getSessionId() . '[/session_id]';
		$message .= ' [url]' . 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '[/url]';
		$message .= ' [referrer]' . $referrer . '[/referrer]';
		$message .= ' [status]' . $status . '[/status]';

		// write to the log
		$this->log->write($message);
	}


	/**
	 * Stores a value under a certain key for a specific user or the current one.
	 *
	 * @return	FrontendUserTracker
	 * @param	string $name
	 * @param	mixed[optional] $value
	 * @param	string[optional] $identifier
	 */
	public function set($name, $value = null, $identifier = null)
	{
		// redefine values
		$name = (string) $name;
		$identifier = ($identifier !== null) ? (string) $identifier : $this->getIdentifier();

		// store in local cache
		$this->values[$identifier][$name] = $value;

		// build data
		$data['id'] = $identifier;
		$data['name'] = $name;
		$data['value'] = serialize($value);
		$data['added_on'] = FrontendModel::getUTCDate();

		// store in database
		FrontendModel::getDB(true)->insert('user_tracker_data', $data);

		// return self
		return $this;
	}


	/**
	 * Sets and creates the identifier.
	 *
	 * @return	FrontendUserTracker
	 */
	public function setIdentifier()
	{
		// identifier seems to already exist
		if(SpoonCookie::exists('user_tracker_id'))
		{
			// fetch identifier
			try
			{
				$identifier = (string) SpoonCookie::get('user_tracker_id');
			}

			// couldn't fetch identifier cookie
			catch(SpoonCookieException $e)
			{
				// @todo reset cookie and call this method again
				Spoon::dump('er ging iets mis bij het opvragen van de cookie...');
			}

			// valid identifier
			if($this->isValidIdentifier($identifier)) $this->identifier = $identifier;

			// invalid
			else
			{
				// reset cookie
				SpoonCookie::set('user_tracker_id', null);

				// lets try again
				Spoon::dump('er ging ietes mis, we proberen het opnieuw, want de waarde die we uit je cookie ophaalden stonk"' . $identifier . '".');
				$this->setIdentifier();
			}
		}

		// no cookie exists
		else
		{
			// create identifier
			$this->identifier = md5(SpoonSession::getSessionId());

			// set cookie (1 year)
			SpoonCookie::set('user_tracker_id', $this->identifier, 86400 * 365);
		}

		return $this;
	}
}


/*
// status!
if(!defined('HEADERS_404')) $status = '200';
else $status = '404';

// get cookie
if(SpoonCookie::exists('cookie_id')) $cookieId = SpoonCookie::get('cookie_id');
if(SpoonCookie::exists('site_id')) $siteId = SpoonCookie::get('site_id');

// set cookie
else
{
	$cookieId = md5(SpoonSession::getSessionId());
	SpoonCookie::set('cookie_id', $cookieId);
	$siteId = 1;
	SpoonCookie::set('site_id', $siteId);
}

// create record
$aStatistics['status'] = (string) $status;
$aStatistics['date'] = date('Y-m-d H:i:s');
$aStatistics['ip'] =  SpoonHTTP::getIp();
$aStatistics['session_id'] = SpoonSession::getSessionId();
$aStatistics['cookie_id'] = $cookieId;
$aStatistics['site_id'] = $siteId;

// get browser info if browscap is available
if(ini_get('browscap') !== false)
{
	$aBrowserInfo = get_browser(null, true);
	$aStatistics['browser_name'] = isset($aBrowserInfo['browser']) ? $aBrowserInfo['browser'] : 'unknown';
	$aStatistics['browser_version'] = isset($aBrowserInfo['version']) ? $aBrowserInfo['version'] : 0;
	$aStatistics['platform'] = isset($aBrowserInfo['platform']) ? $aBrowserInfo['platform'] : 'unknown';
}

// check if this is a referer or not
if(!SpoonFilter::isInternalReferrer()) $aStatistics['referrer_url'] = $_SERVER['HTTP_REFERER'];

// overwrite url & referrer
$aStatistics['url'] = trim('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], '/');

// insert record
$this->db->insert('statistics_temp', $aStatistics);
*/

?>