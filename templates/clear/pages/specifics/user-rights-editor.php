<div id="rights_boxes">
	<table id="groups_datagrid" class="datagrid"></table>
	<div id="rightseditor" class="flexigrid my">
		<div class="mDiv">
			<div class="title"><?php echo _('Droits supplémentaires'); ?></div>
		</div>
		<div class="my grid">
			<div id="right_grid_contents">
				<ul class="additional_user_rights">
					<?php 
					if (!isset($additional_rights_list)) {
						throw new GTE_Exception(
							_('La liste des droit additionnels n\'est pas établie')
						);
					}
					
					function printAdditionalRight ($right) {
						echo '<label><input class="right" type="checkbox" name="right['.$right.']" /> '.GTE::getRightName($right).'</label>';
					}
					
					function printAdditionalRights ($rights_array) {
						foreach ($rights_array as $key => $value) {
							echo '<li>';
							
							if (is_array($value)) {
								printAdditionalRight($key);
								echo '<ul>';
								printAdditionalRights($value);
								echo '</ul>';
							} else {
								printAdditionalRight($value);
							}
							echo '</li>';
						}
					}
					printAdditionalRights($additional_rights_list);
					?>
				</ul>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('div#rightseditor').width(
		$('div#contents').width() - 250 - 50
	);
	
	$('input.right').each(function(){
		var right = this.name.substring(
			6,
			this.name.length-1
		);
		var parent = $(this).parent();
		$(this).remove();
		parent.append('<div id="'+right+'" class="rightbutton loading" />');
	});

	// groups informations
	reloadInformations();
});

function reloadInformations ()
{
	var userId = $('#user_field').val();
	
	reloadDatagrid(userId);
	reloadRights(userId);
}

function reloadDatagrid (userId)
{
	var gridWidth = 250;
	var colWidth = gridWidth - 50;
		
	var parent = $('div#rights_boxes');
	var ppg = $('#groups_datagrid').parent().parent();
	if (ppg.hasClass('flexigrid')) {
		ppg.remove();
	} else {
		$("#groups_datagrid", parent).remove();
	}

	$(parent).prepend('<table id="groups_datagrid" class="datagrid" />');
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
		height: 100
	});
}

function reloadRights (userId)
{
	var rights_list = new Array();
	$('div.rightbutton').each(function(){
		var right = this.id;
		rights_list.push(right);
	});

	var param = [
	 	{name: 'project', value: <?php echo $project->get('project_id'); ?>},
		{name: 'user', value: userId},
		{name: 'query', value: 'select'},
	    {name: 'rights', value: $.toJSON(rights_list)}
	];
						
	$.ajax({
		type: 'POST',
		url: '<?php echo LOCAL_PATH; ?>engines/get-rights.php',
		data: param,
		dataType: 'json',
		success: function(data) {
			alert(data);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {}
	});
}
</script>