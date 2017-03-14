<?php
namespace EasyAI;

use PHPHtmlParser\Dom;

class StackItem
{
	const WAIT = 0;
	const DONE = 1;

	public $url;
	public $hash;
	public $title;
	public $state;

	public function __construct($url)	
	{
		$this->url   = $url;
		$this->hash  = substr(md5($url), 0, 10);
		$this->state = self::WAIT;
	}

	public function hasDone()
	{
		return self::DONE == $this->status;
	}

	public function isWaiting()
	{
		return self::WAIT == $this->status;
	}

	public function process()
	{
		$this->status = self::DONE;
	}

	protected function getDom()
	{
		$dom = new Dom;
		$dom->loadFromUrl($this->url);
		return $dom;
	}

	public function download()
	{
		$dom = $this->getDom();
	}
}