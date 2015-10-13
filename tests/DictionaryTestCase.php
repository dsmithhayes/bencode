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
     * Reads a Bencoded file and puts it into and array.
     */
    public function testReadingFile()
    {
        // you should always trim an opened file, in case of a newline character
        // at the end, fucking some shit up
        $stream = trim(file_get_contents(__DIR__ . '/assets/dictionary.be'));
        
        $dictionary = new Dictionary();
        $dictionary->decode($stream);
        
        $this->assertEquals($stream, $dictionary->encode());
    }
    
    public function testIntegerAsValue()
    {
        $buffer = ['key' => 25];
        $encoded = 'd3:keyi25ee';
        
        $dictionary = new Dictionary($buffer);
        
        $this->assertEquals($encoded, $dictionary->encode());
    }
    
    public function testListAsValue()
    {
        $buffer = ['key' => [1, 2, 3]];
        $encoded = 'd3:keyli1ei2ei3eee';
        
        $dictionary = new Dictionary($buffer);
        
        $this->assertEquals($encoded, $dictionary->encode());
    }
    
    public function testDictionaryAsValue()
    {
        $buffer = ['key' => ['key2' => 'value']];
        $encoded = 'd3:keyd4:key25:valueee';
        
        $dictionary = new Dictionary($buffer);
        
        $this->assertEquals($encoded, $dictionary->encode());
    }
}
