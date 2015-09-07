<?php

use DSH\Bencode\Bencode;
use DSH\Bencode\Integer;
use DSH\Bencode\Byte;
use DSH\Bencode\Collection\BList;
use DSH\Bencode\Collection\Dictionary;

class BencodeIOTestCase extends PHPUnit_Framework_TestCase
{
    public function testBasicFileReading()
    {
        $file_stream = trim(file_get_contents(__DIR__ . '/assets/list.be'));
        $blist = Bencode::blistFactory();
        
        $blist->decode($file_stream);
        
        $this->assertEquals('li1ei2e6:stringe', $blist->encode());
    }
    
    public function testBasicFileWriting()
    {
        $dictionary = Bencode::dictionaryFactory(function () {
            return ['write' => 'this', 'to' => 'file'];
        });
        
        $file = __DIR__ . '/assets/dictionary.be';
        $res = file_put_contents($file, $dictionary->encode());
        
        $this->assertNotEquals(0, $res);
    }
    
    public function testClosureFileOpen()
    {
        $dictionary = Bencode::dictionaryFactory(function () {
            return trim(file_get_contents(__DIR__ . '/assets/dictionary.be'));
        }, true);
        
        $stream = trim(file_get_contents(__DIR__ . '/assets/dictionary.be'));
        
        $this->assertEquals($stream, $dictionary->encode());
    }
}
