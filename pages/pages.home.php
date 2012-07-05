<div class="span9">
    <div class="hero-unit"><h2>Bienvenue sur Obscena uerba</h2>
	<p>	Obscena uerba est né d'un constat : le vocabulaire de la sexualité est très mal traité dans les dictionnaires, les sens cachés ignorés quelques fois. Alors m'est venue l'idée de développer un site mettant en place un vocabulaire glané au fil des recherches...</p>
	<p>	Les principales ressources du site sont pour l'instant issues de <i>Lasciua Venus, petit guide de l'amour romain</i> de Michel Dubuisson. Malheureusement, l'auteur a fait le choix de ne consacrer son ouvrage qu'à une sexualité hétéronormée et à deux, excluant en même temps les "homosexualités", la sexualité solitaire... Mais son travail est sans équivalent connu dans le monde universitaire francophone. En anglais, citons l'ouvrage d'Adams, J. N. <i>The Latin Sexual Vocabulary</i>. JHU Press, 1990.
</p>
	<p>	Ce site n'est pas voué à l'abandon : j'ai dans l'idée que l'avenir du dictionnaire est dans la collaboration, dans son ouverture vers d'autres langues. Peu à peu, le site internet s'ouvrira à de nouvelles données, puis aux inscriptions et enfin à la corédaction. Ces étapes se mettront en place lentement mais l'objectif final d'Obscena uerba est bien de réunir un groupe de chercheu-r-se-s et étudiant-e-s pour produire sans copyright un lexique.</p>
	<p> Enfin, car il s'agit de mon idée d'internet, ce projet est disponible en OpenSource et votre participation est la bienvenue. Je ne peux pas développer le projet tous les jours, mais sachez qu'une version installable "pour les nul-le-s" sera disponible et vous donnera ainsi accès à la création d'un lexique participatif sur un thème de votre choix.</p>
	<p> Ce site est propulsé par Twitter Bootstrap, Jquery, Jquery UI. Les technologies utilisées sont : php5, mysql, javascript.</p>
	<p>Clérice Thibault, étudiant à l'EHESS en <a target="_blank" href="http://anhima.fr/">Anthropologie et histoire des mondes antiques</a></p>
	</div>
	<div class="row-fluid">
	
	<?php
		//On récupère l'info du mot
		$word = $connectBDD->prepare("SELECT l.id_latin word, l.text_latin latin, g.text_grammaire gram FROM ".Wprefix."LATIN l, ".Wprefix."GRAMMAIRE g WHERE l.id_grammaire=g.id_grammaire ORDER BY RAND( ) LIMIT 1");
		$word->execute(array($query)) or (print_r($word->errorInfo()));
		$data1 = $word->fetch(PDO::FETCH_OBJ);
	?>
		<div class="span8 well">
			<h3>Au hasard : <?php echo $data1->latin; ?></h3>
			<p><b>Genre grammatical</b> : <?php echo $data1->gram; ?></p>
			<h4>Traductions</h4>
			<ol>
			<?php
				printtrads($data1->word);
			?>
			</ol>
			<h4>Mots clefs :</h4>
			<?php
				$kws = $connectBDD->prepare("SELECT kd.text_keyword data, kd.id_keyword id FROM ".Wprefix."filtrer kl, ".Wprefix."KEYWORD kd WHERE kd.id_keyword=kl.id_keyword AND kl.id_latin = ?");
				$kws->execute(array($data1->word));
				while($kw = $kws->fetch(PDO::FETCH_OBJ))
				{
					echo '<a href="'.BASEurl.'/keyword/'.urlencode($kw->data).'/">'.keyword($kw->data, $kw->id, 0).'</a>'; //Filtre désactivé par le 0
				}
			?>
		</div>
		<div class="span3 ">
		<script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>
		<script>
		new TWTR.Widget({
		  version: 2,
		  type: 'search',
		  search: '#obscenauerba',
		  interval: 30000,
		  title: 'Suivez la vie d\'Obscena Uerba sur Twitter !',
		  subject: 'Obscena uerba',
		  width: 250,
		  height: 300,
		  theme: {
			shell: {
			  background: '#9668a3',
			  color: '#ffffff'
			},
			tweets: {
			  background: '#ffffff',
			  color: '#444444',
			  links: '#cc3dc2'
			}
		  },
		  features: {
			scrollbar: true,
			loop: false,
			live: true,
			behavior: 'all'
		  }
		}).render().start();
		</script>
		</div>
	</div>
</div>