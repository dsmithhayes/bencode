<?php

use Bencode\Bencode;

class BencodeTestCase extends PHPUnit_Framework_TestCase
{
    public function testIntegerFactory()
    {
        $int = Bencode::integerFactory();
        $this->assertEquals(0, $int->write());
    }
    
    public function testIntergerClosureFactory()
    {
        $int = Bencode::integerFactory(function () {
            return 5 + 10;
        });
        
        $this->assertEquals(15, $int->write());
    }
    
    public function testByteFactory()
    {
        $byte = Bencode::byteFactory();
        $this->assertEquals('0:', $byte->encode());
    }
    
    public function testByteClosureFactory()
    {
        $byte = Bencode::byteFactory(function () {
            return strtoupper("string");
        });
        
        $this->assertEquals('STRING', $byte->write());
    }
    
    public function testBListFactory()
    {
        $blist = Bencode::blistFactory();
        $this->assertEmpty($blist->write());
    }
    
    public function testBListClosureFactory()
    {
        $blist = Bencode::blistFactory(function () {
            $a = [];
            
            for ($i = 1; $i <= 3; $i++) {
                if ($i % 15 == 0) {
                    $a[] = 'fizzbuzz';
                } elseif ($i % 5 == 0) {
                    $a[] = 'buzz';
                } elseif ($i % 3 == 0) {
                    $a[] = 'fizz';
                } else {
                    $a[] = $i;
                }
            }
            
            return $a;
        });
        
        $this->assertEquals('fizz', $blist->write()[2]);
    }
    
    public function testDictionaryFactory()
    {
        $dictionary = Bencode::dictionaryFactory();
        $this->assertEmpty($dictionary->write());
    }
    
    public function testDictionaryClosureFactory()
    {
        $dictionary = Bencode::dictionaryFactory(function () {
            return ['key' => 'value'];
        });
        
        $this->assertEquals('d3:key5:valuee', $dictionary->encode());
    }
    
    public function testDictionaryEncodedFactory()
    {
        $dictionary = Bencode::dictionaryFactory('d3:key5:valuee', true);
        
        $this->assertEquals('value', $dictionary->write()['key']);
    }
}