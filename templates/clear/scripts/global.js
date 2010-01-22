$(document).ready(function(){
	var playerVersion = swfobject.getFlashPlayerVersion();
	if (!playerVersion || playerVersion.major <= 0) {
		$('body').prepend($('<div class="information error">Flash Player doit être installé et activé</div>'));
	} else if (playerVersion.major < 9) {
		$('body').prepend($('<div class="information error">Votre version de Flash Player n\'est pas assez récente. Mettez-la à jour.'));
	}
});

/**
 * Callback for template type <select> changes.
 * 
 * @param select_object
 * @return void
 */
function templateTypeChange (select_object)
{
	var idx = select_object.selectedIndex; 
	 // get the value of the selected option 
	var which = select_object.options[idx].value; 
	
	if (which == '@other@') {
		$('div#other_type').slideDown();
	} else {
		$('div#other_type').slideUp();
	}
}