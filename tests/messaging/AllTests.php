<?php

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/message/TestTextMessage.php';
require_once dirname(__FILE__).'/message/TestJsonMessage.php';
require_once dirname(__FILE__).'/destination/TestQueue.php';
require_once dirname(__FILE__).'/destination/TestTopic.php';
require_once dirname(__FILE__).'/TestClient.php';
require_once dirname(__FILE__).'/pattern/TestFireAndForget.php';

class messaging_AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Testing messaging functionality');
        $suite->addTestSuite('messaging_message_TestTextMessage');
        $suite->addTestSuite('messaging_message_TestJsonMessage');
        $suite->addTestSuite('messaging_destination_TestQueue');
        $suite->addTestSuite('messaging_destination_TestTopic');
        $suite->addTestSuite('messaging_TestClient');
        $suite->addTestSuite('messaging_pattern_TestFireAndForget');
        return $suite;
    }
}
