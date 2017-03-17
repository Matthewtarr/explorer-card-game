#!/usr/bin/php
<?php 










$module_json_stream = shell_exec("/usr/local/bin/sqlite3 explorer2_eng-US.db 'SELECT json FROM modules;'");
$module_json = explode("\n", trim($module_json_stream));

$n=0;
$modules = array();
foreach ($module_json as $line) {								// loop over every line in JSON
    $j = json_decode($line); 								// decode 1 line at a time. why?
    for ($i=0; $i < count($j); $i++) { 						// each line has multiple items
		$temp_clean_json_object = array(				// create a clean array and fill it with stuff
			'id'			=>$j[$i]->{'id'},
			'type'			=>$j[$i]->{'type'},
			'headline'		=>$j[$i]->{'headline'}
		);
		$modules[$j[$i]->{'exhibit_id'}][] = $temp_clean_json_object;
    }
}


/* below here is parsoing the exhibit cards */

$cols = "cards.sqlite_id as card_id,
cards.id as exhibit_id,
cards.title,
cards.type,
cards.image_square, 
cards.notification_text,
cards.topics,
halls.image as hall_image,
halls.short_title as hall_title
";

$incoming_sqlite_stream = shell_exec("/usr/local/bin/sqlite3 explorer2_eng-US.db 'SELECT 
{$cols}
FROM cards INNER JOIN halls ON cards.hall_id = halls.id;'");
$incoming_sqlite_array = explode("\n", trim($incoming_sqlite_stream));

$n=0;
$clean_json_object = array();
$keys = cols_to_keys($cols);
foreach ($incoming_sqlite_array as $line) {								// loop over every line in JSON
	$row = array();
	foreach(explode('|', $line) as $i=>$attr) {
		$row[trim($keys[$i])] = $attr;
	}
	$row['modules'] = $modules[$row['exhibit_id']];
    $clean_json_object[] = $row;
}
file_put_contents('Explorer-Data.json', json_encode($clean_json_object));
//print_r($clean_json_object);


function cols_to_keys($cols) {
	$col_array = array();
	foreach (explode(',', $cols) as $col) {
		$col_parts = explode(' as ', $col);
		if (count($col_parts) >=2 ) {
			$col = $col_parts[1];
		}
		$col_name_id = str_replace('.', '_', trim($col));
		$col_array[] = $col_name_id;
	}
	return $col_array;
}

?>