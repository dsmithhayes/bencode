<?php

use DSH\Bencode\Collection\BList;

class BListTestCase extends PHPUnit_Framework_TestCase
{
    public function testEmptyConstruction()
    {
        $list = new BList();
        $this->assertEmpty($list->write());

        return $list;
    }

    public function testBasicConstruction()
    {
        $list = new BList(1);
        $buffer = $list->write();
        $this->assertEquals(1, $buffer[0]);

        return $list;
    }

    public function testArrayConstruction()
    {
        $list = new BList([0, 1, 'hello']);
        $this->assertNotEmpty($list->write());

        return $list;
    }

    /**
     * @depends testBasicConstruction
     */
    public function testBasicEncoding(BList $list)
    {
        $this->assertEquals('li1ee', $list->encode());
    }

    /**
     * @depends testArrayConstruction
     */
    public function testArrayEncoding(BList $list)
    {
        $this->assertEquals('li0ei1e5:helloe', $list->encode());
    }

    /**
     * @depends testEmptyConstruction
     */
    public function testMixedDecoding(BList $list)
    {
        $stream = 'l4:testi35e5:helloe';
        $list->decode($stream);

        $this->assertEquals($stream, $list->encode());

        return $list;
    }

    /**
     * @depends testMixedDecoding
     */
    public function testArrayLength(BList $list)
    {
        // $stream = 'l4:testi35e5:helloe';
        $this->assertEquals(3 , count($list->write()));
    }
}
