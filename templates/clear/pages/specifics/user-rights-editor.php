	<table id="groups_datagrid" class="datagrid"></table>
	<div id="rightseditor" class="flexigrid my">
		<div class="mDiv">
			<div class="title"><?php echo _('Droits supplémentaires'); ?></div>
		</div>
		<div class="my grid">
			<div id="right_grid_contents">
				<p>Rights list</p>
			</div>
		</div>
		<a id="groups_link"></a>
	</div>
<script type="text/javascript">
$(document).ready(function(){
	var gridWidth = $('div#contents').width() - 20;
	var colWidth = (gridWidth - 65) / 3;
	
	$("a#groups_link").fancybox({
		hideOnOverlayClick: false,
		hideOnContentClick: false,
		centerOnScroll: false,
		frameWidth: gridWidth,
		frameHeight: $(window).height() - 100
	});

	reloadDatagrid(gridWidth, colWidth);
});

function reloadDatagrid (gridWidth, colWidth)
{
	var userId = $('#user_field').val();
		
	var parent = $("#groups_datagrid").parent();
	$("#groups_datagrid").remove();

	$(parent).append('<table id="groups_datagrid" class="datagrid" />');
	$("#groups_datagrid").flexigrid({
		url: '<?php echo LOCAL_PATH; ?>engines/get-groups.php',
		dataType: 'json',
		colModel: [
			{display: '<?php echo _('ID'); ?>', name : 'id', width : 15, sortable : false, align: 'left'},
			{display: '<?php echo _('Nom du groupe'); ?>', name : 'groups', width : colWidth, sortable : false, align: 'left'}
		],
		buttons: [
			{name: '<?php echo _('Ajouter'); ?>', bclass: 'add', position: 'left', onpress : function (a,grid){
				alert('Add group');
			}},
			{name: '<?php echo _('Supprimer'); ?>', bclass: 'delete', position: 'left', onpress : function (a,grid){
				if ($('.trSelected',grid).length <= 0) {
					alert('<?php echo _('Vous devez sélectionner au moins un utilisateur'); ?>');
				} else {
					if ($('.trSelected',grid).length > 1) {
						var string = '<?php echo _('Êtes-vous sur de vouloir supprimer ces %d utilisateurs ?'); ?>';
					} else {
						var string = '<?php echo _('Êtes-vous sur de vouloir supprimer cet utilisateur ?'); ?>';
					}
					
					if (confirm(string.replace(/%d/, $('.trSelected',grid).length))) {
						$("#groups_datagrid").editRemove($('.trSelected',grid));
					}
				}
			}}
		],
		params:[
			{name: 'project', value: <?php echo $project->get('project_id'); ?>},
			{name: 'user', value: userId}
		],
		usepager: false,
		title: '<?php echo _('Groupes'); ?>',
		useRp: false,
		showTableToggleBtn: false,
		width: gridWidth,
		height: 250
	});
}
</script>