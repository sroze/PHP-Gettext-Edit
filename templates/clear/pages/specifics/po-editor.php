<table id="po_datagrid"></table>
<script type="text/javascript">
$("#po_datagrid").flexigrid({
	url: '<?php echo LOCAL_PATH; ?>engines/po-edit.php',
	dataType: 'json',
	colModel: [
		{display: 'Fuzzy', name : 'fuzzy', width : 20, sortable : true, align: 'center'},
		{display: 'Chaine d\'origine', name : 'msgid', width : 240, sortable : true, align: 'left'},
		{display: 'Traduction', name : 'msgstr', width : 240, sortable : true, align: 'left'}
	],
	buttons: [
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
	showTableToggleBtn: true,
	width: 500,
	height: 400
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