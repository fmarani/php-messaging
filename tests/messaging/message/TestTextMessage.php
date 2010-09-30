<?php

require_once dirname(__FILE__).'/../../test-bootstrap.php';

/**
 * @package message
 */
class messaging_message_TestTextMessage extends PHPUnit_Framework_TestCase
{
    public function testMessageHasCorrectContentType()
    {
        $message= new messaging_message_TextMessage('test');
        $this->assertSame('text/plain', $message->getContentType());
    }
    
    public function testGetBody()
    {
        $message= new messaging_message_TextMessage('this is the body');
        $this->assertSame('this is the body', $message->getBody());
    }
    
    public function testSetBody()
    {
        $message= new messaging_message_TextMessage;
	    $message->setBody('this is the body');
        $this->assertSame('this is the body', $message->getBody());
    }

    public function testSetAndReadHeader()
    {
        $message= new messaging_message_TextMessage;
	    $message->setHeader('header-name', 'header-value');
        $this->assertSame('header-value', $message->getHeader('header-name'));
    }
    
    public function testGetMissingHeaderReturnsNull()
    {
        $message= new messaging_message_TextMessage;
        $this->assertNull($message->getHeader('header-name'));
    }
    
    public function testSerializedStringBodyIsString()
    {
        $message= new messaging_message_TextMessage('this is the body');
        $this->assertType('string', $message->getSerializedBody());
    }

    public function testMessageCanBeConvertedToString()
    {
        $message = new messaging_message_TextMessage('testing conversion');
        $this->assertSame('testing conversion', (string)$message);
    }
}
