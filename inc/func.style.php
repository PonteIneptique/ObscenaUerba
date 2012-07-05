<?php
function keyword($text, $id, $target = "home")
{
	return '<span data-active="0" data-target="'.$target.'" data-src="'.$id.'" class="keyword '.$target.'-kw-filter kw-filter label label-info">'.$text.'</span> ';
}

?>