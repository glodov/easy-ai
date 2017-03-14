<?php
namespace EasyAI\Examples;

class ImDbItem extends \EasyAI\StackItem
{
	public function root()
	{
		return 'http://www.imdb.com';
	}

	public function __construct($url = null)
	{
		if (null !== $url) {
			// removing trash from url
			$url = preg_replace('/\?.+$/', '', $url);
			$url = rtrim($url, '/');

			if (substr($url, 0, 1) == '/') {
				$url = $this->root() . $url;
			}
		}
		parent::__construct($url);
	}
}