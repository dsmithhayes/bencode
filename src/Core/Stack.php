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
		$temp = $this->_memory[$this->_sp];
		unset($this->_memory[$this->_sp]);
		
		$this->_sp = $this->_forceValidStackPoint(($this->_sp--));
		
		return $temp;
	}
	
	public function push($data)
	{
		if(is_array($data))
			foreach($data as $d)
				$this->_memory[] = $d;
		else
			$this->_memory[] = $data;
		
		$this->_sp = count($this->_memory) - 1;
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
	
	public function getStackPointer()
	{
		return $this->_sp;
	}
	
	public function setStackPointer($n)
	{
		$this->_sp = $this->_forceValidStackPoint($n);
	}
	
	protected function _forceValidStackPointer($n)
	{
		if($n < 0)
			$n = 0;
	
		$memorySize = count($this->_memory) - 1;
		
		if($n > $memorySize)
			$n = $memorySize;
		
		return $n;
	}
}
