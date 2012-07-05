
		<div class="span6 well">
			<h2>Index latin</h2>
			<ul>
		<?php
		//Tableau des lettres
		$letters = array();
		//On récupère l'info du mot
		$word = $connectBDD->prepare("SELECT w.id_latin word, w.text_latin latin, g.text_grammaire gram FROM ".Wprefix."LATIN w, ".Wprefix."GRAMMAIRE g WHERE w.id_grammaire=g.id_grammaire ORDER BY w.text_latin ASC");
		$word->execute(array($query)) or (print_r($word->errorInfo()));
		while($data1 = $word->fetch(PDO::FETCH_OBJ))
		{
			$first = strtoupper(substr($data1->latin, 0, 1));
			if($first != ' ')
			{
				if(!in_array($first, $letters))
				{
					if(count($letters) > 0)
					{
						echo '</ul></li>';
					}
					echo '<li>'.$first.'<ul>';
					$letters[] = $first;
				}
				echo '<li><a href="'.BASEurl.'/word/'.urlencode($data1->latin).'/">'.$data1->latin.'</a> ('.$data1->gram.') </li>';
			}
		}
	?>
			</ul></li></ul>
		</div>
	</div>