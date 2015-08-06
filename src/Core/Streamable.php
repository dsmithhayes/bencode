<?php

namespace DSH\Bencode\Core;

interface Streamable
{
	public function read($stream);
	public function write();
}
