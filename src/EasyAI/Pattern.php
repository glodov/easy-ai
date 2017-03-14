<?php
namespace EasyAI;

class Pattern
{
	public $attributes = [];

	public $distinct = [];
	public $criteria = [];
	public $conclusion;
	public $errorRate = 0;

	public $item;

	public function __construct(StackItem $item)
	{
		$this->item = $item;
	}

	public function defineCriteria(array $arr)
	{
		$this->criteria = $arr;
		// SELECT 
		// 	DISTINCT column  -- steps
		// ORDER BY column ASC
		// put in array
		// probability = 
	}

	public function defineDistinct(array $arr)
	{
		$this->distinct = $arr;
	}

	public function defineConclusion($name)
	{
		$this->conclusion = $name;
	}

	public function defineErrorRate($value = 0)
	{
		if ($value > 1) {
			$value = 1;
		}
		if ($value < 0) {
			$value = 0;
		}
		$this->errorRate = $value;
	}

	public function getClosestIndex($name)
	{
		$arr = $this->distinct[$name];
		if (empty($arr)) {
			return false;
		}
		$value = $this->item->$name;
		$first = reset($arr);
		$last  = end($arr);

		$result = 0;

		$min = min(abs($value - $first), abs($value - $last));
		foreach ($arr as $index => $val) {
			if (abs($val - $value) <= $min) {
				$min = abs($val - $value);
				$result = $index;
			}
		}

		return $result;
	}

	public function getClosestValue($name)
	{
		$index = $this->getClosestIndex($name);
		return $this->distinct[$name][$index];
	}

	public function getRange($name)
	{
		$index  = $this->getClosestIndex($name);
		if (false === $index) {
			return null;
		}
		$values = $this->distinct[$name];
		$range  = new Range;

		$count  = count($values);
		$err    = $this->errorRate / 2;
		$margin = $err * $count;
		$left   = round($index - $margin);
		if ($left < 0) {
			$left = 0;
		}
		$range->from = $values[$left];

		$right  = round($index + $margin);
		if ($right >= $count) {
			$right = $count - 1;
		}
		$range->to = $values[$right];
		return $range;
	}

	public function getRanges()
	{
		$result = [];
		foreach ($this->criteria as $name) {
			$result[$name] = $this->getRange($name);
		}
		return $result;
	}
}