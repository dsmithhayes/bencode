<?php namespace Bencode\Core;

interface Buffer
{
	/**
	 * Reads in raw data representing the element.
	 */
	public function read($input);
	
	/**
	 * Returns the raw data in the buffer
	 */
	public function write();
}
