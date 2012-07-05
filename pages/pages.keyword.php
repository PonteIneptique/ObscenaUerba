		<div class="span6 well">
	<?php
		//Si un mot clef est séléctionné
		if(isset($query))
		{
		//On récupère l'info du mot clef
		$kword = $connectBDD->prepare("SELECT kw.text_keyword data, kw.id_keyword id FROM ".Wprefix."KEYWORD kw WHERE kw.text_keyword= ? LIMIT 1");
		$kword->execute(array($query)) or (print_r($kword->errorInfo()));
		$data1 = $kword->fetch(PDO::FETCH_OBJ);
		
		//On récupère les termes liés
		$results = printwordsfromkeyword($data1->id, true);
	?>
		<h2><?php echo $data1->data; ?></h2>
			<h3>Termes liés :</h3>
			<ul class="home-kw-list">
			<?php
				echo $results[0];
			?>
			</ul>
	<?
		}
	?>
		</div>
		<div class="span3">
			<h3>Filtres:</h3>
			<?php
			if(isset($query))
			{
				foreach($results[1] as $id => $kw)
				{
					echo keyword($kw, $id);
				}
			}
			?>
		</div>
	</div>