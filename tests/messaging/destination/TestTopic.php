<?php

require_once dirname(__FILE__).'/../../test-bootstrap.php';

/**
 * @package message
 */
class messaging_destination_TestTopic extends PHPUnit_Framework_TestCase
{
    public function testGetNameReturnsName()
    {
        $t= new messaging_destination_Topic('name');
        $this->assertSame('name', $t->getName());
    }
    
    public function testGetRealName()
    {
        $t= new messaging_destination_Topic('name');
        $this->assertSame('/topic/name', $t->getRealName());
    }

    public function testGetRealNameWhenTemp()
    {
        $t= new messaging_destination_Topic('name');
        $t->setAsTemporary();
        $this->assertSame('/temp-topic/name', $t->getRealName());
    }
}
