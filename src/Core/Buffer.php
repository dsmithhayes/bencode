<?php namespace DSH\Bencode\Core;

interface Buffer
{
	/**
	 * Reads in raw data representing the element.
	 */
	public function read($in);
	
	/**
	 * Returns the raw data in the buffer
	 */
	public function write();
}
