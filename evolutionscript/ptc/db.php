<?php

// Configuration Section
$server = 'localhost'; //probably localhost but change if required
$username = 'arshadir_ptc';
$password = 'rafy5547m3553hs70';
$database = 'arshadir_ptc';



$new_charset = 'utf8'; // change to the required character set - you're probably changing to utf8 ?
$new_collation = 'utf8_general_ci'; // change to the required collation - you're probably changing to utf8_general_ci ?

// Connect to database
$db = mysql_connect($server, $username, $password); if(!$db) die("Cannot connect to database server -".mysql_error());
$select_db = mysql_select_db($database); if (!$select_db) die("could not select $database: ".mysql_error());

// change database collation
mysql_query("ALTER DATABASE $database DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");

// Loop through all tables changing collation
$result=mysql_query('show tables');
while($tables = mysql_fetch_array($result)) {
$table = $tables[0];
mysql_query("ALTER TABLE $table DEFAULT CHARACTER SET $new_charset COLLATE $new_collation");

// loop through each column changing collation
$columns = mysql_query("SHOW FULL COLUMNS FROM $table where collation is not null");
while($cols = mysql_fetch_array($columns)) {
$column = $cols[0];
$type = $cols[1];
mysql_query("ALTER TABLE $table MODIFY $column $type CHARACTER SET $new_charset COLLATE $new_collation");
}

print "changed collation of $table to $new_collation<br/>";
}
print '<br/>The collation of your database has been successfully changed!<br/>';
?>