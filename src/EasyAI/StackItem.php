<?php
namespace EasyAI;

use Gilbitron\Util\SimpleCache;

class StackItem
{
	const WAIT = 0;
	const DONE = 1;

	public $url;
	public $hash;
	public $title;
	public $state;

	public function __construct($url = null)	
	{
		if (null !== $url) {
			$this->url   = rtrim($url, '/');
			$this->hash  = substr(md5($url), 0, 10);
			$this->state = self::WAIT;			
		}
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

	protected function getDom($url = null)
	{
		$cache = new SimpleCache;
		$cache->cache_path = 'cache/';
		$cache->cache_time = 3600;

		$url = null === $url ? $this->url : $url;
		$dom = new Dom;

		if ($data = $cache->get_cache($url)) {
			$dom->load($data);
		} else {
			$dom->loadFromUrl($url);
			$cache->set_cache($url, $dom->getRawContent());
		}
		return $dom;
	}

	public function download()
	{
		$dom = $this->getDom();
		$element = $dom->find('head > title')[0];

		$this->title = $element->text;

		return $dom;
	}
}