<?php

namespace DSH\Bencode\Core;

interface Element
{
	public function encode($stream);
	public function decode();
}
