<?php

require __DIR__ . '/..' . '/vendor/autoload.php';

use EasyAI\Pattern;
use EasyAI\Stack;
use EasyAI\Examples\Actor;
use EasyAI\Examples\Film;


// Create patterns

// Download html pages from imdb or similar (kinopoisk.ru)
$films = new Stack;
$actors = new Stack;

// here we add random url of first movie to start with its actors
$baseUrl = 'http://www.imdb.com/title/tt0330373';
$baseUrl = 'http://www.imdb.com/title/tt2771200';
$films->add($baseUrl, new Film);


$count = 300;
while ($count > 0) {

	if (!$actors->length() && !$films->length()) {
		// no more unparsed urls for actors and films :(
		// so we did enclosed search
		// try another staring endpoint/url
		print("--- No more URls to parse ---\n");
		break;
	}

	if (!$actors->length()) {
		// pop up next film URL and parse it to find new actors
		$film = $films->pop();

		// downloading list of actors from film webpage
		$film->download();

		printf("Downloading film: %s\n", $film->title);

		// taking only top 10 actors from every film
		foreach ($film->findActors(10) as $actor) {
			printf("Downloading actor: %-30s %s\n", $actor->name, $actor->id);
			$actor->download();
			$actors->add($actor);

			print((string) $actor);
		}

		// complete current film item
		$film->process();
		// move from open to complete/done
		$films->reveal($film);
		printf("Film %s added ..\n", $film->title);
		continue;
	}
	$count--;
}