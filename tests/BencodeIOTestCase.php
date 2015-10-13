<?php

use Bencode\Bencode;
use Bencode\Integer;
use Bencode\Byte;
use Bencode\Collection\BList;
use Bencode\Collection\Dictionary;

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
    
    public function testOpenTorrentFile()
    {
        $file = __DIR__ . '/assets/test.torrent';
        $torrent = file_get_contents($file);
        
        $dictionary = Bencode::dictionaryFactory(function() use ($torrent) {
            return $torrent;
        }, true);
        
        $this->assertEquals($torrent, $dictionary->encode());
    }
}
