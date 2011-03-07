<?php

// utf8
define('SPOON_CHARSET', 'utf-8');

// includes
require_once 'library/globals.php';
require_once 'spoon/spoon.php';
require_once 'default_www/frontend/modules/user_tracker/engine/model.php';

class FrontendUserTrackerTest extends PHPUnit_Framework_TestCase
{
	private $userTracker;

	public function testIsValidIdentifier()
	{
//		$this->assertFalse($this->userTracker->isValidIdentifier('F0rk Rul3s!!!'));
//		$this->assertFalse($this->userTracker->isValidIdentifier('Spaces and so on'));
//		$this->assertTrue($this->userTracker->isValidIdentifier(md5('Fork Rules')));
	}

	public function testSetIdentifier()
	{
//		SpoonCookie::set('user_tracker_id', 'This is not valid');
	}
}

?>