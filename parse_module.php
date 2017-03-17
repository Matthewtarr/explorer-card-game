#!/usr/bin/php
<?php 

$module_json_stream = shell_exec("/usr/local/bin/sqlite3 explorer2_eng-US.db 'SELECT json FROM modules;'");
$module_json = explode("\n", trim($module_json_stream));

$n=0;
$modules = array();
foreach ($module_json as $line) {								// loop over every line in JSON
    $j = json_decode($line); 								// decode 1 line at a time. why?
    for ($i=0; $i < count($j); $i++) { 						// each line has multiple items

print_r($j[$i]);

		// $temp_clean_json_object = array(				// create a clean array and fill it with stuff
		// 	'id'			=>$j[$i]->{'id'},
		// 	'type'			=>$j[$i]->{'type'},
		// 	'headline'		=>$j[$i]->{'headline'}
		// );
		// $modules[$j[$i]->{'exhibit_id'}][] = $temp_clean_json_object;
    }
}
//file_put_contents('Tol-Quiz-Data.json', json_encode($clean_json_object));
//print_r($clean_json_object);


 ?>