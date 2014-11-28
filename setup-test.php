#!/usr/bin/php
<?php

$input = fopen('/tmp/1-raw-bs.csv', 'w');
$columns = array('Date','Amount','Description','No contacts match this line');
fputcsv($input, $columns);
$columns = array('1/06/2014','100.00','This is a single','A match on contact 1');
fputcsv($input, $columns);
$columns = array('1/06/2014','200.00','This is a double trouble bubble','Multiple matches on contacts 2, 3, and 4');
fputcsv($input, $columns);
$columns = array('1/06/2014','300.00','first account','Match 1st account for contact 6');
fputcsv($input, $columns);
$columns = array('1/06/2014','400.00','SECOND account','Match 2nd account for contact 6');
fputcsv($input, $columns);
$columns = array('1/06/2014','500.00','FIRST and second account','Match 1st account for contact 6, though both 1st and 2nd are listed');
fputcsv($input, $columns);
$columns = array('1/06/2014','600.00','broad','Match 1st account for contact 7');
fputcsv($input, $columns);
$columns = array('1/06/2014','700.00','broad narrow','Match 2nd account for contact 7');
fputcsv($input, $columns);
fclose($input);

$lookup = fopen('/tmp/2-map-bs.csv', 'w');
$columns = array('Id','Name','Pattern', 'Account');
fputcsv($lookup, $columns);
$columns = array('1','West, Ken','SINGLE','ABC123');
fputcsv($lookup, $columns);
$columns = array('2','Jacobs, David','do.ble','ABC124');
fputcsv($lookup, $columns);
$columns = array('3','Kaldor, Peter','tr[ou]+ble','ABC125');
fputcsv($lookup, $columns);
$columns = array('4','Josling, Craig','200.00,.*bubble','ABC126');
fputcsv($lookup, $columns);
$columns = array('5','Stewart, Al','300.00,.*bubble','ABC127');
fputcsv($lookup, $columns);
$columns = array('6','Spencer, Caroline','(first)|(second)','ABC128,ABC129');
fputcsv($lookup, $columns);
$columns = array('7','Luu, Tho','(broad( narrow)?)','ABC130,ABC131');
fputcsv($lookup, $columns);
fclose($lookup);

echo "To test, run ...\n";
echo "  ./parse-bs.php /tmp/1-raw-bs.csv /tmp/2-map-bs.csv /tmp/3-cooked-bs.csv\n";
echo "  more /tmp/3-cooked-bs.csv\n";
echo "    - no contacts should match line 1\n";
echo "    - contact 1 should match line 2 and have the right Name, Id and Account appended\n";
echo "    - contacts 2, 3 and 4 should match line 3 and a comment to that effect appended\n";
echo "    - contact 6 should match line 4 and account ABC128 is matched\n";
echo "    - contact 6 should match line 5 and account ABC129 is matched\n";
echo "    - contact 6 should match line 6 and account ABC129 is matched\n";
echo "    - contact 7 should match line 7 and account ABC130 is matched\n";
echo "    - contact 7 should match line 8 and account ABC131 is matched\n";
