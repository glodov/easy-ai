<?php
namespace EasyAI;

class Range
{
	public $from;
	public $to;

	public function __toString()
	{
		return "{$this->from} << X << {$this->to}";
	}
}