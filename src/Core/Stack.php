<?php namespace Bencode\Core;

use Bencode\Exception\StackException;

class Stack 
{
	private $_memory = [];
	private $_sp = 0;
	
	public function __construct($data = null)
	{
		if(isset($data))
			if(is_array($data))
				$this->_memory = $data;
			else
				$this->_memory[] = $data;
		
		$this->_sp = count($this->_memory) - 1;
	}
	
	public function pop()
	{
		return $this->_memory[$this->_sp--];
	}
	
	public function push($data)
	{
		$this->_memory[] = $data;
		$this->_sp++;
	}
	
	public function dump()
	{
		return $this->_memory;
	}
	
	public function restore($data)
	{
		if(!is_array($data))
			throw new StackException('Cannot restore from non-array.');
		
		$this->_memory = $data;
		$this->_sp = 0;
	}
}
