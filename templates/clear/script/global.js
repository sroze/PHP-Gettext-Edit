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