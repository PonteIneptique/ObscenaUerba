$(document).ready(function() {
	$( "#searchword" ).autocomplete({
		source: BASEurl+"/json/json.latin.php",
		minLength: 2,
		select: function( event, ui ) {
			location.href = ui.item.id;
		}
	});
	
	$('.kw-filter').live('click', function() {
		var target = $(this).attr('data-target');
		var kw = $(this).attr('data-src');
		var state = $(this).attr('data-active');
		if(target != 0) //Si on n'a pas désactivé la fonction de filtre
		{
			if(state == '0') { $(this).attr('data-active', '1'); } else { $(this).attr('data-active', '0'); $('.'+target+'-kw-filtered-'+kw).hide(); }
			$(this).toggleClass('label-info').toggleClass('label-success');
			//Hide all !
			$('.'+target+'-kw-filtered').hide();
			//Show the list !
			$('.'+target+'-kw-list').show();
			
			if($('.'+target+'-kw-filter[data-active="1"]').size() > 0)
			{
				//SET THE FILTER !
				var keywords = $('.'+target+'-kw-filter[data-active="1"]').map(function () {
					  return target+'-kw-filtered-'+$(this).attr('data-src');
				}).get().join(".");
				
				//Launch the show !
				$('.'+keywords).show();	
			}
			else
			{
				$('.'+target+'-kw-filtered').show();
			}
		}
	});
});