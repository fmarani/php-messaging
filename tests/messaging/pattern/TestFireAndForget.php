<?php

require_once dirname(__FILE__).'/../../test-bootstrap.php';

/**
 * @package message
 */
class messaging_pattern_TestFireAndForget extends PHPUnit_Framework_TestCase
{
    public function testClientSendMethodIsCalled()
    {
        $mockClient = $this->getMock('messaging_Client', array('connect', 'send'));
        
        $mockClient->expects($this->once())
                   ->method('connect');
        
        $mockClient->expects($this->once())
                   ->method('send');
        
        $client = new messaging_pattern_FireAndForget($mockClient);
        $message = new messaging_message_JsonMessage('this is a test');
        $client->sendMessage($message);
    }
}
