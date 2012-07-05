<?php
	if(isset($_GET['word'])) { $query = urldecode($_GET['word']); }
	$title .= " | Dictionnaire : ".$query;
?>