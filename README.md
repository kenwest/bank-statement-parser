Bank Statement Parser
=====================

Reads a CSV file containing a bank statement, scanning for patterns.

 * If only a single pattern matches a row, add some 'cells' indicating contact-id, contact-name, and account.
 * If multiple patterns match, add a cell listing the matching contact names.
 * If no patterns match, output the row unchanged

Command line
------------

./parse-bs.php input-csv pattern-map-csv output-csv

Pattern map
-----------

A CSV file with the following columns ...

 1. Id - unique identifier of the contact
 2. Name - name of the contact
 3. Pattern - a PHP regular expression tested against input rows in a case-insensitive manner
 4. Account - identifier of the account the item should be booked against
