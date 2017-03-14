<?php
namespace EasyAI\Examples;

class Film extends ImDbItem
{
	public $name;
	public $year;

	private $actors = [];

	public function download()
	{
		$dom = parent::download();

		$this->actors = [];

		$url = $this->url . '/fullcredits';
		$credits = $this->getDom($url);
		foreach ($credits->find('table.cast_list [itemprop=actor]') as $item) {
			$actor = new Actor($item->find('[itemprop=url]', 0)->href);
			$actor->name = $item->find('[itemprop=name]', 0)->text;

			$this->actors[] = $actor;
		}
	}

	public function findActors($limit = null)
	{
		if (!$limit) {
			return $this->actors;
		}
		return array_slice($this->actors, 0, $limit);
	}
}