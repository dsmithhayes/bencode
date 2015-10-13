<?php

use Bencode\Integer;

class IntegerTestCase extends PHPUnit_Framework_TestCase
{
    public function testEmptyConstruction()
    {
        $integer = new Integer();
        $this->assertEquals(0, $integer->write());
    }
    
    public function testNullConstruction()
    {
        $integer = new Integer(null);
        $this->assertEquals(0, $integer->write());
    }

    public function testZeroConstruction()
    {
        $integer = new Integer(0);
        $this->assertEquals(0, $integer->write());
    }
    
    public function testBasicConstruction()
    {
        $integer = new Integer(1);
        $this->assertEquals(1, $integer->write());

        return $integer;
    }

    public function testNegativeConstruction()
    {
        $integer = new Integer(-1);
        $this->assertEquals(-1, $integer->write());

        return $integer;
    }

    public function testStringConstruction()
    {
        $integer = new Integer('1');
        $this->assertEquals(1, $integer->write());
    }

    public function testNegativeStringConstruction()
    {
        $integer = new Integer('-1');
        $this->assertEquals(-1, $integer->write());
    }

    public function testFloatConstruction()
    {
        $integer = new Integer(1.5);
        $this->assertEquals(1, $integer->write());
    }

    /**
     * @expectedException \Bencode\Exception\IntegerException
     */
    public function testInvalidConstruction()
    {
        $integer_string = new Integer('words');
    }

    /**
     * @depends testBasicConstruction
     */
    public function testEncoding(Integer $int)
    {
        $this->assertEquals('i1e', $int->encode());
    }

    /**
     * @depends testNegativeConstruction
     */
    public function testNegativeEncoding(Integer $int)
    {
        $int->read(-1);
        $this->assertEquals('i-1e', $int->encode());
    }

    /**
     * @depends testBasicConstruction
     * @expectedException \Bencode\Exception\IntegerException
     */
    public function testInvalidEncoding(Integer $int)
    {
        $int->read('hello!');
    }

    /**
     * @depends testBasicConstruction
     */
    public function testNegativeDecoding(Integer $int)
    {
        $int->decode('i-1337e');
        $this->assertEquals(-1337, $int->write());
    }

    public function testStreamReturn()
    {
        $int = new Integer();

        $this->assertEquals('5:hello', $int->decode('i35e5:hello'));
    }
}
