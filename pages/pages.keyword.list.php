
		<div class="span6 well">
			<h2>Index des mots-clefs</h2>
			<ul>
		<?php
		//Tableau des lettres
		$letters = array();
		//On récupère l'info du mot
		$word = $connectBDD->prepare("SELECT kw.text_keyword data FROM ".Wprefix."KEYWORD kw ORDER BY kw.text_keyword ASC");
		$word->execute(array($query)) or (print_r($word->errorInfo()));
		while($data1 = $word->fetch(PDO::FETCH_OBJ))
		{
			$first = strtoupper(substr($data1->data, 0, 1));
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
				echo '<li><a href="'.BASEurl.'/keyword/'.urlencode($data1->data).'/">'.$data1->data.'</a></li>';
			}
		}
	?>
			</ul></li></ul>
		</div>
	</div>