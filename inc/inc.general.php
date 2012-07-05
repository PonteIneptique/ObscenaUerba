<?php
	if(!defined('RELurl')) { exit(); }
	
	//Config et Sessions sont toujours demandées.
	
	//Appel aux sessions
	require_once(RELurl.'./inc/class.OPSession.php');
	OPSession::Start();
	
	//Appel fichier config
	require_once(RELurl.'./inc/inc.conf.php');
	
	//Appel à la connexion
	switch(PageType) {
		case 'index':
			require_once(RELurl.'/inc/inc.conn.php');
			require_once(RELurl.'/inc/func.prints.php');
			require_once(RELurl.'/inc/func.style.php');
			break;
		case 'register':
			require_once(RELurl.'/inc/inc.conn.php');
			require_once(RELurl.'/inc/func.sets.php');
			break;
		case 'json':
			require_once(RELurl.'/inc/inc.conn.php');
			break;
	}
	
?>