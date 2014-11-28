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
		$accounts = explode(',', $columns[3]);
		if ( count($accounts) == 1 ) {
			/*
			 * Only one Account is listed. Note that preg_match() returns a
			 * list of Matches, the first of which corresponds to the full
			 * pattern.
			 */
			$contact['account'] = array( $accounts[0] );
		} else {
			/*
			 * Multiple Accounts are listed. If the Line matches the Pattern,
			 * we want an Account to correspond to each subpattern but not the
			 * full pattern. The indices of the accounts in this array should
			 * correspond to the indices of the subpattern matches which might
			 * be returned by preg_match().
			 */
			$contact['account'] = array_merge( array( '' ), $accounts );
		}
		$map[$pattern] = $contact;
	}
}

while ( ($columns = fgetcsv($input)) !== false ) {
	$contact = $account = $comment = false;
	$line = implode(',', $columns);
	foreach ($map as $mapPattern => $mapContact) {
		if ( preg_match('/' . $mapPattern . '/i', $line, $matches) == 1 ) {
			if ( $contact === false && $comment === false ) {
				$contact = $mapContact['id'];
				foreach ($matches as $index => $match) {
					if ( !empty($match) && !empty($mapContact['account'][$index]) ) {
						$account = $mapContact['account'][$index];
					}
				}
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

