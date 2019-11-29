<?php

function get_kom($kom) {
	foreach ($kom as $k) echo '<p class="text-info">'.$k.'</p>';
}

function get_menu($db, $id, $strona) {
	Baza::db_query('SELECT * FROM menu');
	foreach (Baza::$ret as $k => $t) {
		echo '
<li class="nav-item">
	<a class="nav-link';

		if ($t['id'] == $id) {
			echo ' active';
			$strona = $t;
		}

		echo '" href="?id='.$t['id'].'">'.$t['tytul'].'</a>
</li>';
	}
}

function get_page_title($strona) {
	if (array_key_exists('tytul', $strona)) {
		echo $strona['tytul'];
	} else {
		echo 'Aplikacja PHP';
	}
}

function get_page_content($strona) {
	if (array_key_exists('nazwa_pliku', $strona)) {
		if (file_exists($strona['nazwa_pliku'].'.html'))
			include($strona['nazwa_pliku'].'.html');
	} else {
		include('404.html');
	}
}

?>