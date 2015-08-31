<?php

use DSH\Bencode\Byte;

class ByteTestCase extends PHPUnit_Framework_TestCase
{
    public function testEmptyConstruction()
    {
        $byte = new Byte();
        $this->assertEmpty($byte->write());

        return $byte;
    }

    /**
     * @depends testEmptyConstruction
     */
    public function testStreamDecoding(Byte $byte)
    {
        $byte->decode('4:test');
        $this->assertEquals('test', $byte->write());
    }

    /**
     * @depends testEmptyConstruction
     */
    public function testDecodingReturn(Byte $byte)
    {
        $stream = '4:failure';
        $this->assertEquals('ure', $byte->decode($stream));

        return $byte;
    }

    /**
     * @depends testDecodingReturn
     */
    public function testStreamEncoding(Byte $byte)
    {
        $this->assertEquals('4:fail', $byte->encode());
    }

    public function testIntegerStreamEncoding()
    {
        $byte = new Byte('25');
        $this->assertEquals('2:25', $byte->encode());
    }
}
