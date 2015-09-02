# Bencode

This is a library used for encoding and decoding bencoded streams. Bencoding
is primarily found in the makeup of `.torrent` files.

## Bencoding Basics

Pronounced bee-encoding, this language focuses on binary encoding with four
basic element datastructures.

1. `Integer`
2. `Byte`
3. `List`
4. `Dictionary`

The last two elements (`List`, `Dictionary`) are just collections of the
first two elements.

### Integer

The Integer element is a positive or negative integer that is always
prefixed with an `i`, and suffixed with an `e`.

    i45e
    i-1e

#### Usage

    <?php
    
    use DSH\Bencode\Integer;
    
    $int = new Integer(45);
    echo $int->encode();    // i45e

    $int->decode(i-445e);
    echo $int->write();     // -445

### Byte

Bytes are encoded with their size (in bytes), a `:` and then the sequence
of bytes.

    5:hello
    6:123456

Note that the second example will encode the character codes and not the
binary value of the integers.

#### Usage

    <?php
    
    use DSH\Bencode\Byte;
    
    $byte = new Byte('hello');
    echo $byte->encode();       // 5:hello
    
    $byte->decode('5:world');
    echo $byte->write();        // world

### List

A List is just that, a list of Byte and Integer elements. It has a siilar
encoding as the Integer. The List is prefixed with `l` and suffixed with
`e`. Within are encoded Bytes and integers.

    li45e5:hello5:worldi-45e

#### Usage

The class is named `BList` because `list` is a reserved keyword in PHP.

    <?php
    
    use DSH\Bencode\BList
    
    $list = new BList([45, 'hello', 'world', -45]);
    echo $list->encode();       // li45e5:hello5:worldi-45e
    
    $list->decode('li34e4:davee');
    print_r($list->write();     // [34, 'dave']

### Dictionary

Dictionaries are key-value lists of bytes. The first element in the list
is the key, and the second is the value. The Dictionary is prefixed with
a `d` and suffixed with an `e`.

    d3:key5:valuee

#### Usage

    <?php
    
    use DSH\Bencode\Dictionary;

    $dictionary = new Dictionary(['key' => 'value']);
    echo $dictionary->encode();     // d3:key5:valuee
    
    $dictionary->decode('d3:new6:valuese');
    print_r($dictionary->write());  // ['new' => 'values']

