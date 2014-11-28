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

Contacts with multiple patterns
-------------------------------

If a contact has multiple patterns that match them in the Bank Statement, use the '|' alternation syntax.

Thus, a contact whose pattern is "first|second" will be matched to Bank Statement lines containing either "first" or "second".

Patterns with multiple accounts
-------------------------------

It is possible for Bank Statement lines to be booked to different accounts. To do this use the "()" subpattern syntax in the pattern, and comma-separate the accounts in the account field.

Thus, a contact whose pattern is "(first)|(second)" and an account field containing "ABC123,DEF456" will be matched to Bank Statement lines containing either "first" or "second". If the Bank Statement line contains "first" the account will be "ABC123", and it will be "DEF456" if the line contains "second". The number of subpatterns must match the number of accounts supplied.

It's theoretically possible that more than one subpattern will match. This gets complex ...

 * In the case of the alternation pattern "(abc)|(def)|..." the alternatives are tried left to right so put the special cases before the broad ones. Only one subpattern will match.
 * In the case of nested subpatterns both can match. Say we wanted "broad" to map to account "ABC123" and "broad narrow" to map to "DEF456". In this case the pattern should be "(broad( narrow)?)" and the accounts "ABC123,DEF456".
 * Other cases should be tested before use!
