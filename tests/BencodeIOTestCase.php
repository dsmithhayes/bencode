<?php

use DSH\Bencode\Bencode;
use DSH\Bencode\Integer;
use DSH\Bencode\Byte;
use DSH\Bencode\Collection\BList;
use DSH\Bencode\Collection\Dictionary;

class BencodeIOTestCase extends PHPUnit_Framework_TestCase
{
    public function testBasicFileWriting()
    {
        $file_stream = trim(file_get_contents(__DIR__ . '/assets/list.be'));
        $blist = Bencode::blistFactory();
        
        $blist->decode($file_stream);
        
        $this->assertEquals('li1ei2e6:stringe', $blist->encode());
    }
}
