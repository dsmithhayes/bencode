<?php namespace DSH\Bencode\Core;

/**
 * There are three generic and one special case when it comes to encoding
 * the four elements. This reader will help encode and decode these
 * three generic types (integers, lists, dictionaries). 
 */
abstract class Reader
{	
	/**
	 * Returns the first character of the encoding
	 */
	public function readFirst($in)
	{
		return substr($in, 0, 1);
	}
	
	/**
	 * Returns the last character of the encoding.
	 */
	public function readLast($in)
	{
		return substr($in, (strlen($in) - 1), 1);
	}
	
	/**
	 * Returns the element without the encoding characters.
	 */
	public function dropEncoding($in)
	{
		$in = substr($in, 1);
		return (int) substr($in, 0, (strlen($in) - 1));
	}
}
