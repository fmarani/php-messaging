<?php

require_once dirname(__FILE__).'/../test-bootstrap.php';

/**
 * @package messaging
 */
class messaging_ClientTests extends PHPUnit_Framework_TestCase
{
    private $queue;

    private $message;

    private $client;

    public function setUp($queue, $client)
    {
        if (!$queue || !$client) $this->markTestSkipped();
        $this->queue = $queue;
        $this->client = $client;
	    $this->message = new messaging_message_TextMessage("test");
        try {
            $this->client->connect();
            $this->client->subscribe($queue);

            // Need to purge the queue to ensure tests don't affect
            // each other.
            $this->purgeDestination();
        } catch (Stomp_Exception $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }

    public function tearDown()
    {
        if ($this->client) $this->client->disconnect();
    }

    private function purgeDestination()
    {
        $this->client->setTimeoutInSeconds(0.5);
        while ($message = $this->client->read()) {
            $this->client->acknowledge($message);
        }
    }

    private function sendMessage($destination, $message)
    {
        $this->client->send($destination, $message);
    }

    public function testMessagesAreCorrectlySentAndRead()
    {
        $this->sendMessage($this->queue, $this->message);
        $readMessage = $this->client->read();
        $this->client->acknowledge($readMessage);
        $this->assertSame($this->message->getBody(), $readMessage->getBody());
    }

    public function testReadReturnsFalseWhenNoPendingMessages()
    {
        $this->client->setTimeoutInSeconds(0.1);
        $this->assertNull($this->client->read());
    }

    public function testReadMessagesHaveId()
    {
        $this->sendMessage($this->queue, $this->message);
        $readMessage = $this->client->read();
        $this->assertTrue(strlen($readMessage->getId()) > 0);
    }

    public function testGetSessionId()
    {
        //That session id regex is only valid for stomp sessions
        //$this->assertRegExp('/^ID:[\w\.]+-\d+-\d+-\d:\d+$/', $this->client->getSessionId());
        $sessionId = $this->client->getSessionId();
        $this->assertTrue(!empty($sessionId));
    }

    public function testAcknowledgedMessagesCannotBeReadAgain()
    {
        $this->sendMessage($this->queue, $this->message);

        $this->client->setTimeoutInSeconds(0.1);
        $firstReadMessage = $this->client->read();
        $this->client->acknowledge($firstReadMessage);
        $this->client->disconnect();

        $this->client->connect();
        $secondMessage = $this->client->read();
        $this->assertNull($secondMessage);
    }

    public function testMessageOfCorrectTypeIsReturnedByDefault()
    {
        $this->sendMessage($this->queue, $this->message);

        $message = $this->client->read();
        $this->assertType('messaging_message_TextMessage', $message);
    }

    public function _testMessageOfCorrectTypeIsReturnedWhenJson()
    {
        $this->sendMessage($this->queue, new messaging_message_JsonMessage(array(1,2,3)));
        $message = $this->client->read();
	    $this->assertType('messaging_message_JsonMessage', $message);
    }

    public function testReadMessageHasId()
    {
        $this->sendMessage($this->queue, $this->message);
        $readMessage = $this->client->read();
        $this->client->acknowledge($readMessage);
        $this->assertNotNull($readMessage->getId());
    }
}
