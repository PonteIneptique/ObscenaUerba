<?php
//Fonction d'impression et de recherches de données 

//Impression des sources
function printsrc($src, $page = NULL)
{
	global $connectBDD;
	
	//Préparation
	$reqsrc = $connectBDD->prepare("SELECT auteur_source auteur, titre_source titre, editeur_source editions, date_source annee FROM ".Wprefix."SOURCE WHERE id_source=? LIMIT 1");
	
	//Execution
	$reqsrc->execute(array($src));
	$datas = $reqsrc->fetch(PDO::FETCH_OBJ);
	
	//Mise en page
	if($page != null)
	{
		$miseenpage = $datas->auteur.', <i>'.$datas->titre.'</i>, '.$datas->editions.', '.$datas->annee.', p. '.$page;
	}
	else
	{
		$miseenpage = $datas->auteur.', <i>'.$datas->titre.'</i>, '.$datas->editions.', '.$datas->annee;
	}
	//Retour
	return array($miseenpage, $datas);
}
//Affichage des traductions
function printtrads($id)
{
	global $connectBDD;
	$source = array();
	//Affichage des traductions
	$translations = $connectBDD->prepare("SELECT td.text_translation french, t.comment_traduire comment, t.page_traduire, t.id_source source, t.page_traduire page FROM ".Wprefix."TRANSLATION td, ".Wprefix."traduire t WHERE td.id_translation=t.id_translation AND t.id_latin = ?");
	$translations->execute(array($id));
	while($trads = $translations->fetch(PDO::FETCH_OBJ))
	{
		//A ce moment là on le lien de source, la traduction, la page
		if(!isset($source[$trads->id])) { $source[$trads->id]= printsrc($trads->source, $trads->page); }
		$temp = ""; 
		if(strlen($trads->comment) > 3) { $temp = '<li>Citation : <i>"'.$trads->comment.'"</i></li>'; }
		echo '<li>'.$trads->french.' <ul>'.$temp.'<li>'.$source[$trads->id][0].'</li></ul></li>';
	}
}
//Get keyword
function getkeyword($id)
{
	global $connectBDD;
	
	$kw = $connectBDD->prepare("SELECT kd.text_keyword data FROM ".Wprefix."KEYWORD kd WHERE kd.id_keyword = ? LIMIT 1");
	$kw->execute(array($id)) or (print_r($kw->errorInfo()));
	$data = $kw->fetch(PDO::FETCH_OBJ);
	
	return $data->data;
}
//Afichage des mots liés à un mot clef
function printwordsfromkeyword($id, $filter = false)
{
	global $connectBDD;
	$target = "home"; #temp
	
	//Préparation
	$words = $connectBDD->prepare("SELECT w.text_latin latin, w.id_latin id FROM ".Wprefix."LATIN w, ".Wprefix."filtrer kl WHERE kl.id_keyword=? AND kl.id_latin=w.id_latin ORDER BY w.text_latin");
	$kw = $connectBDD->prepare("SELECT kl.id_keyword kw FROM ".Wprefix."filtrer kl WHERE kl.id_latin = ?");
	$keywords = array(); #Servira à garder les mots clefs annexes
	$datas = "";
	//Execution
	$words->execute(array($id)) or (print_r($words->errorInfo()));
	while($w = $words->fetch(PDO::FETCH_OBJ))
	{
		//On récupère les autres mots clefs si $filter = true pour pouvoir filtrer en jquery
		if($filter == true)
		{
			$list = "";
			$kw->execute(array($w->id));
			while($d = $kw->fetch(PDO::FETCH_OBJ))
			{
				$list.= " ".$target."-kw-filtered-".$d->kw." ";
				if(!isset($keywords[$d->kw])) { $keywords[$d->kw] = getkeyword($d->kw); }
			}
		}
		$datas .= '<li class="'.$target.'-kw-filtered '.$list.'"><a href="./word/'.urlencode($w->latin).'/">'.$w->latin.'</a></li>';
	}
	return array($datas, $keywords);
}
?>