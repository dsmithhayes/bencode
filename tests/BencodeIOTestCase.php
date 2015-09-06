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
        $blist = Bencode::dictionaryFactory(function () {
            return ['write' => 'this', 'to' => 'file'];
        });
        
        $file = __DIR__ . '/assets/dictionary.be';
        $res = file_put_contents($file, $blist->encode());
        
        $this->assertNotEquals(0, $res);
    }
}
