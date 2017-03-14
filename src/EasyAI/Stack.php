<?php
namespace EasyAI;

class Stack
{
	private $open = [];
	private $done = [];

	public function clear()
	{
		$this->open = [];
		$this->done = [];
	}

	/**
	 * Function adds URL into stack and if it is new or existing but not yet parsed
	 * returns StackItem object 
	 * if old and parsed already returns FALSE.
	 * 
	 * @access public
	 * @param string $url The url of the endpoint.
	 * @return StackItem The new object to work with, or FALSE if object done already.
	 */
	public function add($url)
	{
		$item = new StackItem($url);

		if (isset($this->done[$item->hash])) {
			return false;
		}

		$this->open[$item->hash] = $item;
		return $item;
	}

	/**
	 * Function checks for open/waiting item in stack and if it is so it is moving
	 * to complete/done stack, so less items left opened.
	 * 
	 * @access public
	 * @param StackItem $item The item to reveal.
	 * @return TRUE if item moved from open to complete, FALSE if complete already.
	 */
	public function reveal(StackItem $item)
	{
		if (isset($this->open[$item->hash])) {
			$this->done[$item->hash] = $item;
			unset($this->open[$item->hash]);
			return true;
		}
		return false;
	}

	/**
	 * To be fare we work with FIFO model.
	 * So returns the first item which were added.
	 * Php does not sort assoc arrays by keywors as js does with objects,
	 * so we can easily return [first in] item.
	 * 
	 * @access public
	 * @return StackItem The item if exists and FALSE if not.
	 */
	public function pop()
	{
		if (count($this->open)) {
			return 
		}
	}

	private function length()
	{
		$length = 0;
		foreach ($this->data as $hash => $item) {
			if ($item->isWaiting()) {
				$length++;
			}
		}
		return $length;
	}

}