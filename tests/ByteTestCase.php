<?php

use Bencode\Byte;

class ByteTestCase extends PHPUnit_Framework_TestCase
{
	/**
	 * @expectedException IntegerException
	 */
	public function testConstructorException()
	{
		$int = new Integer();
	}
}
