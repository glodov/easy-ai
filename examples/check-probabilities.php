<?php

require __DIR__ . '/..' . '/vendor/autoload.php';

use EasyAI\Pattern;
use EasyAI\Examples\Actor;

$actor = new Actor;
$actor->gender = 0;     // female
$actor->height = 1.75;  // 1.65m
$actor->salary = 350;
// if value cannot be found in knowledge base we need to find closest
// and we have to add new data into database so AI is learning


$p = new Pattern($actor);
$p->defineDistinct([
	'gender' => [0, 1],
	'height' => [1.50, 1.55, 1.60, 1.65, 1.75, 1.85, 1.90, 1.95, 2.0, 2.02, 2.03],
	'salary' => [50, 100, 300, 5000]
]);
$p->defineCriteria(['gender', 'height']);
$p->defineConclusion('salary');
$p->defineErrorRate(0.49); // 20%

printf("Closest height to %s => %s\n", $actor->height, $p->getClosestValue('height'));

foreach ($p->getRanges() as $name => $range) {
	printf("%-10s%s\n", $name, (string) $range);
}