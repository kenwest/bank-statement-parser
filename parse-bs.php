#!/usr/bin/php
<?php

$input = fopen($argv[1], 'r');
$lookup = fopen($argv[2], 'r');
$output = fopen($argv[3], 'w');


if ($input === false || $lookup === false || $output === false) {
	return;
}

$map = array();
while ( ($columns = fgetcsv($lookup)) !== false ) {
	if ( is_numeric( $columns[0] ) ) {
		$pattern = $columns[2];
		$contact = array();
		$contact['id'] = $columns[0];
		$contact['name'] = $columns[1];
		$contact['account'] = $columns[3];
		$map[$pattern] = $contact;
	}
}

while ( ($columns = fgetcsv($input)) !== false ) {
	$contact = $account = $comment = false;
	$line = implode(',', $columns);
	foreach ($map as $mapPattern => $mapContact) {
		if ( preg_match('/' . $mapPattern . '/i', $line) == 1 ) {
			if ( $contact === false && $comment === false ) {
				$contact = $mapContact['id'];
				$account = $mapContact['account'];
				$comment = $mapContact['name'];
			} else if ($contact !== false) {
				$contact = false;
				$account = false;
				$comment = 'Multiple contacts match: ' . $comment . '; ' . $mapContact['name'];
			} else {
				$comment = $comment . '; ' . $mapContact['name'];
			}
		}
	}

	if ( $comment !== false ) {
		$columns[] = $comment;

		if ( $contact !== false ) {
			$columns[] = $contact;
			$columns[] = $account;
		}
	}

	fputcsv($output, $columns);
}

fclose($input);
fclose($lookup);
fclose($output);

