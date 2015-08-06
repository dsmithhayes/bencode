<?php

namespace DSH\Bencode\Core;

interface Element
{
	/**
	 * Returns an encoded stream of the value in the buffer.
	 */
	public function encode();
	
	/**
	 * Reads a stream and extracts the valid encoding of an element. 
	 */
	public function decode($stream);
}
