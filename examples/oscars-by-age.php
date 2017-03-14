<?php

use EasyAI\Pattern;
use EasyAI\Stack;
use PHPHtmlParser\Dom;


// Create patterns

// Download html pages from imdb or similar (kinopoisk.ru)
$films = new Stack;
$actors = new Stack;

// here we add random url of first movie to start with its actors
$films->add('https://www.kinopoisk.ru/film/932068/');

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
		// $dom = new Dom;
		// $dom->loadFromUrl($film->url);
		$film->download();

		// taking only top 10 actors from every film
		foreach ($film->findActors(10) as $actor) {
			$actors->add($actor);

			// foreach ($actor->findFilms() as $film) {
			// 	// adding films into it's stack, it will add only new films
			// 	$films->add($film->url);
			// }
		}

		// complete current film item
		$film->process();
		// move from open to complete/done
		$films->reveal($film);
		printf("Film %s added ..\n", $film->title);
		continue;
	}

	$dom = new Dom;
	$dom->loadFromUrl('http://google.com');
	$html = $dom->outerHtml;
}