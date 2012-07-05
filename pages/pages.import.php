<div class="span9">
<?php
	$import = array();
	
    $fd = @fopen(RELurl."./temp/srv.csv","r");
    if (!$fd) die("Impossible d'ouvrir le fichier");
    $i = 0;
    while (!feof($fd)) {
        $ligne = fgets($fd, 1024);
		if($i != 0) { $import[$i] = explode('|', $ligne); }
		$i++;
    }
    
    // On a fini, on ferme !!
    fclose($fd);
	
	$words = 0;
	
	//Et si on préparait des fonctions ?
	function setgramma($str)
	{
		global $connectBDD;
		//Préparations
		$verifgramma = $connectBDD->prepare("SELECT id_grammaire id FROM ".Wprefix."GRAMMAIRE WHERE text_grammaire=? LIMIT 1");
		$insertgramma = $connectBDD->prepare("INSERT INTO ".Wprefix."GRAMMAIRE VALUES ('', ?)");
		
		//Execute : on vérifie l'existence
		$verifgramma->execute(array($str));
		if($verifgramma->rowCount() == 0)
		{
			$insertgramma->execute(array($str));
			$gramma = $connectBDD->lastInsertId();
		}
		else
		{
			$tp = $verifgramma->fetch(PDO::FETCH_OBJ);
			$gramma = $tp->id;
		}
		return $gramma;
	}
	function setword($word, $gramma)
	{
		global $connectBDD;
		//Préparations
		$verifword = $connectBDD->prepare("SELECT id_latin id FROM ".Wprefix."LATIN WHERE text_latin = ? LIMIT 1");
		$insertword = $connectBDD->prepare("INSERT INTO ".Wprefix."LATIN (id_latin, text_latin, id_grammaire) VALUES ('', ?, ?)");
		
		//Executions
		$verifword->execute(array($word)) or die($verifword->errorInfo());
		if($verifword->rowCount() == 0)
		{
			//On s'occupe du genre grammatical
			if($gramma > 0)
			{
				$insertword->execute(array($word, $gramma)) or die($insertword->errorInfo());
				$w = $connectBDD->lastInsertId();
			}
		}
		else
		{
			$tp = $verifword->fetch(PDO::FETCH_OBJ);
			$w = $tp->id;
		}
		return $w;
	}
	function settrad($str)
	{
		global $connectBDD;
		//Préparation
		$veriftrad = $connectBDD->prepare("SELECT id_translation id FROM ".Wprefix."TRANSLATION WHERE text_translation = ? LIMIT 1");
		$inserttrad = $connectBDD->prepare("INSERT INTO ".Wprefix."TRANSLATION (id_translation, text_translation, id_lang) VALUES ('', ?, 1)");
		//Execution
		$veriftrad->execute(array($str));
		if($veriftrad->rowCount() == 0)
		{
			$inserttrad->execute(array($str));
			$trad = $connectBDD->lastInsertId();
		}
		else
		{
			$tp = $veriftrad->fetch(PDO::FETCH_OBJ);
			$trad = $tp->id;
		}
		return $trad;
	}
	function settradlink($word, $trad, $src, $page, $comment)
	{
		global $connectBDD;
				
		// Preparation
		$inserttradlink = $connectBDD->prepare("INSERT INTO ".Wprefix."traduire (id_latin, id_translation, id_source, page_traduire, comment_traduire) VALUES (?, ?, ?, ?, ?)");
		// Insertion
		$inserttradlink->execute(array($word, $trad, $src, $page, $comment)) or (print_r($inserttradlink->errorInfo()));
	}
	function setsrcdata($titre, $auteur, $edition, $annee)
	{
		global $connectBDD;
		
		//Préparation
		$verifsrc = $connectBDD->prepare("SELECT id_source id FROM ".Wprefix."SOURCE WHERE titre_source = ? AND auteur_source = ? AND editeur_source = ? AND date_source = ?");
		$insertsrc = $connectBDD->prepare("INSERT INTO ".Wprefix."SOURCE (id_source, titre_source, auteur_source, editeur_source, date_source VALUES ('', ?,?,?,?)");
		
		//Insertion
		$verifsrc->execute(array($titre, $auteur, $edition, $annee));
		if($verifsrc->rowCount() ==0)
		{
			$insertsrc->execute(array($titre, $auteur, $edition, $annee));
			$src = $connectBDD->lastInsertId();
		}
		else
		{
			$tp = $verifsrc->fetch(PDO::FETCH_OBJ);
			$src = $tp->id;
		}
		return $src;
	}
	
	function setkwdata($kw)
	{
		global $connectBDD;
		//Préparations
		$verifkword = $connectBDD->prepare("SELECT id_keyword id FROM ".Wprefix."KEYWORD WHERE text_keyword=? LIMIT 1");
		$insertkword = $connectBDD->prepare("INSERT INTO ".Wprefix."KEYWORD VALUES ('', ?)");
		
		//Executions
		$verifkword->execute(array($kw));
		if($verifkword->rowCount() == 0)
		{
			$insertkword->execute(array($kw));
			$keyword = $connectBDD->lastInsertId();
		}
		else
		{
			$tp = $verifkword->fetch(PDO::FETCH_OBJ) or (print_r($tp->errorInfo()));
			$keyword = $tp->id;
		}
		return $keyword;
	}
	function setkwlink($word, $kw)
	{
		global $connectBDD;
		//Préparations
		$insertklink = $connectBDD->prepare("INSERT INTO ".Wprefix."filtrer (id_keyword, id_latin) VALUES (?, ?)");
		
		$insertklink->execute(array($kw, $word));
	}
	//On traite les tableaux créés.
	foreach($import as $cle => $w)
	{
		//Gramma|Latin|Francais|MotsClef|MotsClef|Métaphore ""|Genre|Sens Métaphorique|Registre|Page
		//0		|1 		|2		|3			4		5			6		7				8		9
		$gramma = setgramma($w[0]);
		$word = setword($w[1], $gramma);
		if($word > 0)
		{
			echo $word;
			//On récupère la traduction
			$trad = settrad($w[2]);
			echo $trad;
			//Voire la métaphore
			if(strlen($w[5]) > 3)
			{
				$metaphore = settrad($w[7]);
				$tradlink =  settradlink($word, $metaphore, 1, $w[9], $w[10]);
			}
			
			//On passe à la suite : le lien !
			if($trad > 0)
			{
				$tradlink =  settradlink($word, $trad, 1, $w[9], $w[10]);
			}
			
			//Maintenant on s'occupe des mots clefs :
			if(strlen($w[3]) > 3)
			{
				$kw = setkwdata($w[3]);
				if($kw > 0) {	$kl = setkwlink($word, $kw); }
			}
			if(strlen($w[4]) > 4)
			{
				$kw = setkwdata($w[4]);
				if($kw > 0) {	$kl = setkwlink($word, $kw); }
			}
			if(strlen($w[5]) > 3)
			{
				$kw = setkwdata("Métaphore ".$w[5]);
				if($kw > 0) {	$kl = setkwlink($word, $kw); }
			}
			if(strlen($w[6]) > 3)
			{
				$kw = setkwdata($w[6]);
				if($kw > 0) {	$kl = setkwlink($word, $kw); }
			}
			if(strlen($w[8]) > 3)
			{
				$kw = setkwdata($w[8]);
				if($kw > 0) {	$kl = setkwlink($word, $kw); }
			}
		}
		++$words;
	}
	echo $words;
?> 
<pre><?php // print_r($import); ?> </pre>
</div>