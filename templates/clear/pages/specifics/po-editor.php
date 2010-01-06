<table id="po_datagrid"></table>
<div id="msgeditor" class="flexigrid my">
	<div class="mDiv">
		<div class="title">Édition de la séléction</div>
	</div>
	<div class="grid">
		<p>Données</p>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	var gridWidth = Math.round($('div#contents').width() / 2) - 20;
	var colWidth = (gridWidth - 15) / 2 - 28;
	
	$("#po_datagrid").flexigrid({
		url: '<?php echo LOCAL_PATH; ?>engines/po-edit.php',
		dataType: 'json',
		colModel: [
			{display: 'Fuzzy', name : 'fuzzy', width : 15, sortable : true, align: 'center'},
			{display: 'Chaine d\'origine', name : 'msgid', width : colWidth, sortable : true, align: 'left'},
			{display: 'Traduction', name : 'msgstr', width : colWidth, sortable : true, align: 'left'}
		],
		buttons: [
		    {name: 'Sauvegarder', bclass: 'save', onpress: test},
			{separator: true},
			{name: 'Ajouter', bclass: 'add', onpress : test},
			{name: 'Supprimer', bclass: 'delete', onpress : test},
			{separator: true},
			{name: 'Éditer', bclass: 'edit', onpress: test}
		],
		searchitems: [
			{display: 'Chaine d\'origine', name : 'msgid', isdefault: true},
			{display: 'Traduction', name : 'msgstr'}
		],
		params:[
			{name: 'project', value: '<?php echo $project->get('project_id'); ?>'},
			{name: 'language', value: '<?php echo $language->getCode(); ?>'},
			{name: 'file', value: '<?php echo $language_file->getName(); ?>'}
		],
		sortname: "msgid",
		sortorder: "asc",
		usepager: false,
		title: 'Traductions',
		useRp: false,
		showTableToggleBtn: false,
		width: gridWidth,
		height: 400
	});

	$('div#msgeditor').width(gridWidth);
});
function test(com,grid)
{
	/*if (com=='Delete')
		{
			confirm('Delete ' + $('.trSelected',grid).length + ' items?')
		}
	else if (com=='Add')
		{
			alert('Add New Item');
		}*/
	alert('Button pressed: '+com);
}
</script>
<div class="clear" />