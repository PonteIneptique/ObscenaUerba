	<?php
		//On récupère l'info du mot
		$word = $connectBDD->prepare("SELECT w.id_latin word, w.text_latin latin, g.text_grammaire gram FROM ".Wprefix."LATIN w, ".Wprefix."GRAMMAIRE g WHERE w.text_latin= ? AND w.id_grammaire=g.id_grammaire");
		$word->execute(array($query));
		$data1 = $word->fetch(PDO::FETCH_OBJ);
	?>
		<div class="span6 well">
			<h2><?php echo $data1->latin; ?></h2>
			<b>Genre grammatical</b> : <?php echo $data1->gram; ?>
			<h3>Traductions</h3>
			<ol>
			<?php
				printtrads($data1->word);
			?>
			</ol>
			<h3>Mots clefs :</h3>
			<?php
				$kws = $connectBDD->prepare("SELECT kd.text_keyword data, kd.id_keyword id FROM ".Wprefix."filtrer kl, ".Wprefix."KEYWORD kd WHERE kd.id_keyword=kl.id_keyword AND kl.id_latin = ?");
				$kws->execute(array($data1->word));
				while($kw = $kws->fetch(PDO::FETCH_OBJ))
				{
					echo '<a href="'.BASEurl.'/keyword/'.urlencode($kw->data).'/">'.keyword($kw->data, $kw->id, 0).'</a>'; //Filtre désactivé par le 0
				}
			?>
		</div>
		<div class="span3">
			
		</div>
	</div>