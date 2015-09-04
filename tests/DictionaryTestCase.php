<?php

use DSH\Bencode\Collection\Dictionary;

class DictionaryTestCase extends PHPUnit_Framework_TestCase
{
    public function testBasicConstruction()
    {
        $dictionary = new Dictionary(['key' => 'value']);
        $this->assertEquals('d3:key5:valuee', $dictionary->encode());

        return $dictionary;
    }

    public function testStreamDecoding()
    {
        $stream = 'd3:key5:valuee';
        $dictionary = new Dictionary();
        
        $dictionary->decode($stream);

        $this->assertEquals($stream, $dictionary->encode());
    }

    public function testBigStreamReading()
    {
        $buffer = [
            'first' => 'value',
            'second' => 'another value',
            'third' => 'neat!'
        ];

        $stream = 'd5:first5:value6:second13:another value5:third5:neat!e';
        
        $dictionary = new Dictionary($buffer);
        $this->assertEquals($stream, $dictionary->encode());
    }

    /**
     * @expectedException \DSH\Bencode\Exception\DictionaryException
     */
    public function testImproperEncoding()
    {
        $dictionary = new Dictionary();
        $stream = 'd3:key4:valueel';

        $dictionary->decode($stream);
    }

    /**
     * @expectedException \DSH\Bencode\Exception\DictionaryException
     */
    public function testImproperByteEncoding()
    {
        $dictionary = new Dictionary();
        $stream = 'd3:key4:valuee';

        $dictionary->decode($stream);
    }
    
    /**
     * Reads a Bencoded file and puts it into and array.
     */
    public function testReadingFile()
    {
        $stream = file_get_contents(__DIR__ . '/assets/test.be');
        
        if (substr($stream, -1) === "\n") {
            $stream = substr($stream, 0, -1);
        }
        
        $dictionary = new Dictionary();
        $dictionary->decode($stream);
        
        $this->assertEquals($stream, $dictionary->encode());
    }
}
