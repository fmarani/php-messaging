<?php
/**
 * @package messaging
 * @copyright 2010 Federico Marani
 * @version SVN: $Id$
 */

/**
 * Topic representation
 * 
 * @package messaging
 * @subpackage destination
 */
class messaging_destination_Topic extends messaging_Destination
{
	/**
	 * @return string
	 */
	public function getRealName()
	{
		if ($this->isTemporary) {
			return "/temp-topic/".$this->name;
		} else {
			return "/topic/".$this->name;
		}
	}
}
