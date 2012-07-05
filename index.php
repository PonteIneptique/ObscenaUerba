<?php
	//Dans toute les pages, définir le chemin relatif/absolu
	define('RELurl', './');
	define('BASEurl', 'http://obscenauerba.algorythme.net');
	
	//On définit le type de page
	define('PageType', 'index');
	require_once(RELurl.'./inc/inc.general.php');
	
	$title = " | Accueil";
	
	//Appel de Header si Nécessaire
	if(isset($_GET['p']) && file_exists('./pages/headers.'.$_GET['p'].'.php')) {	include(RELurl.'pages/headers.'.$_GET['p'].'.php'); } #else { include(RELurl.'pages/headers.home.php'); } 
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    
    <base href="<?php echo BASEurl; ?>" />
    <title><?php echo Wsitename . $title; ?></title>
    
    <script type="text/javascript">
		var BASEurl = '<?php echo BASEurl; ?>';
	</script>

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

    <!-- Le styles -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link href="./css/jqueryui.css" rel="stylesheet">
    <style type="text/css">
		html, body {
			min-height:100%;
		}
		.keyword {
			cursor:pointer;
		}
		.hero-unit>p {
			text-align:justify;
			font-size:1em;
			text-indent:10%;
			line-height:1.1em;
		}
	</style>
  </head>

  <body>

    <div class="navbar">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#"><?php echo Wsitename; ?></a>
          <div class="nav-collapse">
            <ul class="nav">
              <li class="active"><a href="./">Accueil</a></li>
              <li><a href="./word/list.html">Index des mots latins</a></li>
              <li><a href="./keyword/list.html">Index des catégories</a></li>
              <li><a href="https://sourceforge.net/projects/obscenauerba/">SourceForge</a></li>
			  <li><a href="http://storify.com/PonteIneptique/obscenauerba-pre-alpha">Historique Storify</a></li>
            </ul>
			<form class="navbar-text pull-right form-search"><input type="text" id="searchword" placeholder="Cherchez un mot..." /></form>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span2" id="left">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li class="nav-header">Au hasard</li>
				<?php
					$kws = $connectBDD->prepare("SELECT text_keyword FROM ".Wprefix."KEYWORD ORDER BY RAND() LIMIT 10");
					$kws->execute(array());
					while($kw = $kws->fetch(PDO::FETCH_OBJ))
					{
						echo '<li><a href="./keyword/'.urlencode($kw->text_keyword).'/">'.$kw->text_keyword.'</a></li>'; //Filtre désactivé par le 0
					}
				?>
            </ul>            
          </div><!--/.well -->
        </div><!--/span-->
        <?php		
			if(isset($_GET['p']) && file_exists('./pages/pages.'.$_GET['p'].'.php')) {	include(RELurl.'pages/pages.'.$_GET['p'].'.php'); } else { include(RELurl.'pages/pages.home.php'); } 
		?>
	</div><!--/row-->
      <hr>

      <footer>
        <p>Creative Commons Attribution License - CLERICE Thibault 2012</p>
      </footer>

    </div><!--/.fluid-container-->
	
    <!-- Le javascript ============================================ -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.min.js"></script>
    <script type="text/javascript" src="./js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./js/jquery.ui.js"></script>
    <script type="text/javascript" src="./js/js.index.js"></script>
	
  </body>
</html>