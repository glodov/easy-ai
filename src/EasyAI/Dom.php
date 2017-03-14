<?php
namespace EasyAI;

class Dom extends \PHPHtmlParser\Dom
{
	public function getRawContent()
	{
		return $this->raw;
	}
}