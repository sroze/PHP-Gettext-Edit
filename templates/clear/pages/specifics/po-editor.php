<table id="po_datagrid"></table>
<div class="flexigrid">
	<div class="mDiv">
		<div class="title">Édition de la séléction</div>
	</div>
	<div class="grid">
		<p>Données</p>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$("#po_datagrid").flexigrid({
		url: '<?php echo LOCAL_PATH; ?>engines/po-edit.php',
		dataType: 'json',
		colModel: [
			{display: 'Fuzzy', name : 'fuzzy', width : 20, sortable : true, align: 'center'},
			{display: 'Chaine d\'origine', name : 'msgid', width : 240, sortable : true, align: 'left'},
			{display: 'Traduction', name : 'msgstr', width : 240, sortable : true, align: 'left'}
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
		sortname: "msgid",
		sortorder: "asc",
		usepager: false,
		title: 'Traductions',
		useRp: false,
		showTableToggleBtn: false,
		width: 500,
		height: 400
	});
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