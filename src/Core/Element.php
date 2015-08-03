<?php namespace DSH\Bencode\Core;

interface Element
{
		
	/**
	 * Encodes an internal buffer of data representing the type of 
	 * element it is.
	 */
	public function encode();
	
	/**
	 * Decodes any given string representation of the encoded data
	 */
	public function decode($in);
	
	/**
	 * Returns true if the encoding is valid.
	 */
	public function valid($in);
}
