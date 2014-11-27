#!/usr/bin/php
<?php

$input = fopen('/tmp/1-raw-bs.csv', 'w');
$columns = array('Date','Amount','Description','No contacts match this line');
fputcsv($input, $columns);
$columns = array('1/06/2014','100.00','This is a single','A single match on contact 1');
fputcsv($input, $columns);
$columns = array('1/06/2014','200.00','This is a double trouble bubble','Multiple matches on contacts 2, 3, and 4');
fputcsv($input, $columns);
fclose($input);

$lookup = fopen('/tmp/2-map-bs.csv', 'w');
$columns = array('Id','Name','Pattern', 'Default account');
fputcsv($lookup, $columns);
$columns = array('1','West, Ken','single','ABC123');
fputcsv($lookup, $columns);
$columns = array('2','Jacobs, David','do.ble','ABC124');
fputcsv($lookup, $columns);
$columns = array('3','Kaldor, Peter','tr[ou]+ble','ABC125');
fputcsv($lookup, $columns);
$columns = array('4','Josling, Craig','200.00,.*bubble','ABC126');
fputcsv($lookup, $columns);
$columns = array('5','Stewart, Al','300.00,.*bubble','ABC127');
fputcsv($lookup, $columns);
fclose($lookup);

echo "To test, run ...\n";
echo "  ./parse-bs.php /tmp/1-raw-bs.csv /tmp/2-map-bs.csv /tmp/3-cooked-bs.csv\n";
echo "  more /tmp/3-cooked-bs.csv\n";
echo "    - no contacts should match line 1\n";
echo "    - one contact should match line 2 and have the right Name, Id and Account appended\n";
echo "    - three contacts should match line 3 and a comment to that effect appended\n";
