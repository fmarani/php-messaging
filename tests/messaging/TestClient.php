<?php
require_once dirname(__FILE__).'/../test-bootstrap.php';
require_once dirname(__FILE__).'/ClientTests.php';

/**
 * @package url
 */
class messaging_TestClient extends messaging_ClientTests
{
    public function setUp()
    {
        $q = messaging_destination_Factory::createQueue('testclient.'.rand());
        $q->setAsTemporary(true);
        parent::setUp($q, new messaging_Client('ssl://localhost:61612'));
    }
}
