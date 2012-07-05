<?php
	if(isset($_GET['term']) && strlen($_GET['term']) >= 2)
	{
		//Dans toute les pages, définir le chemin relatif/absolu
		define('RELurl', '../');
		define('BASEurl', 'http://obscenauerba.algorythme.net');
		
		//On définit le type de page
		define('PageType', 'json');
		require_once(RELurl.'./inc/inc.general.php');
		
		//On prépare
		$req = $connectBDD->prepare("SELECT text_latin latin, id_latin id FROM ".Wprefix."LATIN WHERE text_latin LIKE ?");
		$array = array();
		
		//On execute
		$req->execute(array("%".$_GET['term']."%"));
		while($data = $req->fetch(PDO::FETCH_OBJ))
		{
			$array[] = array("value" => $data->latin, "id" => BASEurl."/word/".urlencode($data->latin)."/");
		}
		
		echo json_encode($array);
	}
	else
	{
		echo json_encode('Erreur');
	}
?>