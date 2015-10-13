<?php

/**
 * This file will demonstrate opening a .torrent file and getting its
 * contents. Run it from the command line and see the comment from the
 * 'test.torrent' file in the tests/assets/ directory.
 */

require '../vendor/autoload.php';

use Bencode\Collection\Dictionary;

$dictionary = new Dictionary();
$torrent = file_get_contents('../tests/assets/test.torrent');

$dictionary->decode($torrent);

$buffer = $dictionary->write();

var_dump($buffer);

echo $buffer['comment'] . "\n";
