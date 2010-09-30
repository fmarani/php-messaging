<?php
/**
 * @package messaging
 * @subpackage destination
 * @copyright 2010 Federico Marani
 * @version SVN: $Id$
 */

/**
 * Queue representation
 * 
 * @package messaging
 * @subpackage destination
 */
class messaging_destination_Queue extends messaging_Destination
{
	/**
	 * @return string
	 */
	public function getRealName()
	{
		if ($this->isTemporary) {
			return "/temp-queue/".$this->getName();
		} else {
			return "/queue/".$this->getName();
		}
	}
}
