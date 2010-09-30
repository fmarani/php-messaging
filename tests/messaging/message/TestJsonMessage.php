<?php

require_once dirname(__FILE__).'/../../test-bootstrap.php';

/**
 * @package message
 */
class messaging_message_TestJsonMessage extends PHPUnit_Framework_TestCase
{
    public function testMessageHasCorrectContentType()
    {
        $message= new messaging_message_JsonMessage(array(1,2,3));
        $this->assertSame('application/json', $message->getContentType());
    }
    
    public function testSerializedArrayBodyIsString()
    {
        $message= new messaging_message_JsonMessage(array(1,2,3));
        $this->assertType('string', $message->getSerializedBody());
    }

    public function testGetBodyReturnsOriginalType()
    {
	    $message= new messaging_message_JsonMessage(array(1,2,3));
        $this->assertType('array', $message->getBody());
    }
}
