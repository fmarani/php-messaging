<?php

require_once dirname(__FILE__).'/../../test-bootstrap.php';

/**
 * @package message
 */
class messaging_destination_TestQueue extends PHPUnit_Framework_TestCase
{
    public function testGetNameReturnsName()
    {
        $q= new messaging_destination_Queue('name');
        $this->assertSame('name', $q->getName());
    }
    
    public function testGetRealName()
    {
        $q= new messaging_destination_Queue('name');
        $this->assertSame('/queue/name', $q->getRealName());
    }

    public function testGetRealNameWhenTemp()
    {
        $q= new messaging_destination_Queue('name');
	    $q->setAsTemporary(true);
        $this->assertSame('/temp-queue/name', $q->getRealName());
    }
}
