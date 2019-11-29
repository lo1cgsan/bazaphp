<?php

function get_kom($kom) {
	foreach ($kom as $k) echo '<p class="text-info">'.$k.'</p>';
}

function get_menu($db, $id, $strona) {
	$db->db_query('SELECT * FROM menu');
	foreach ($db->ret as $k => $t) {
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

?>