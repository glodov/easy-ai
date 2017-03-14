<?php
namespace EasyAI\Examples;

class Actor extends ImDbItem
{
	public $id;          // imdb id
	public $age;         // the age
	public $birthday;    // the birth date
	public $year;        // the year of birth
	public $height;      // the phisical height
	public $publicity;   // amount of trivia items
	public $income;      // total amount of published salary/income 
	public $salary;      // average salary
	public $spouses;     // amount of spouses
	public $gender;      // gender, unknown for now

	public $won;         // amount of times won an award
	public $nominated;   // amount of times beign nomiated to an award

	private $films = [];

	public function __construct($url = null)
	{
		parent::__construct($url);
		if (preg_match('/\/nm([\d]+)$/', $this->url, $res)) {
			$this->id = $res[1];
		}
	}

	public function download()
	{
		$dom = parent::download();

		$url = $this->url . '/bio';
		$bio = $this->getDom($url);
		$overview = $bio->find('#overviewTable tr');

		$this->birthday  = $dom->find('[itemprop=birthDate]', 0)->datetime;
		$this->year      = substr($this->birthday, 0, 4);
		// calculcate rough age
		$this->age       = date('Y') - $this->year;
		foreach ($overview as $row) {
			if ('Height' != trim($row->find('td', 0)->text)) {
				continue;
			}
			$height        = trim($row->find('td', 1)->text);
			$height        = htmlspecialchars_decode($height);
			$height        = str_replace('&nbsp;', '', $height);
			$this->height  = $height;
			if (preg_match('/\((.+?)m\)/i', $height, $res)) {
				$this->height = floatval($res[1]);
			}
		}
		$this->publicity = $bio->find('#bio_content a[href=#trivia]', 0)->text;
		$this->salary    = 0;
		$this->income    = 0;
		$payments        = 0;
		foreach ($bio->find('#salariesTable tr') as $row) {
			$salary = trim($row->find('td', 1)->text);
			$salary = str_replace(['$', ','], ['', ''], $salary);
			$this->income += intval($salary);
			$payments++;
		}
		if ($payments) {
			$this->salary = $this->income / $payments;
		}
		$this->spouses   = $bio->find('#tableSpouses tr')->count();
	}

	public function __toString()
	{
		$columns = [
			'id', 
			'age', 
			'birthday', 
			'height', 
			'publicity', 
			'salary', 
			'spouses', 
			'won', 
			'nominated'
		];
		$str = ['Actor'];
		foreach ($columns as $name) {
			$str[] = sprintf("%-15s%s", $name, $this->$name);
		}
		return implode("\n  ", $str) . "\n";
	}
}