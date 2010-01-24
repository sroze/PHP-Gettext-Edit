<div id="rights_boxes">
	<table id="groups_datagrid" class="datagrid"></table>
	<div id="rightseditor" class="flexigrid my">
		<div class="mDiv">
			<div class="title"><?php echo _('Droits éffectifs de l\'utilisateur'); ?></div>
		</div>
		<div class="my grid">
			<div id="right_grid_contents">
				<div class="right_legend">
					<h3><?php echo _('Légende'); ?></h3>
					<p><div class="rightbutton yes"></div> <?php echo _('Droit impliqué'); ?></p>
					<p><div class="rightbutton yes user"></div> <?php echo _('Droit accordé à l\'utilisateur'); ?></p>
					<p><div class="rightbutton yes group"></div> <?php echo _('Droit accordé à un de ses groupes'); ?></p>
					<p><div class="rightbutton no"></div> <?php echo _('Droit non-accordé'); ?></p>
					<p><div class="rightbutton no user"></div> <?php echo _('Droit refusé à l\'utilisateur'); ?></p>
					<p><div class="rightbutton no group"></div> <?php echo _('Droit refusé au groupe'); ?></p>
				</div>
				<form id="additionnal_rights" action="" method="POST">
				<ul class="additional_user_rights">
					<?php 
					if (!isset($additional_rights_list)) {
						throw new GTE_Exception(
							_('La liste des droit additionnels n\'est pas établie')
						);
					}
					
					function printAdditionalRight ($right) {
						echo '<div id="right_'.$right.'" class="rightbutton loading"></div> <label>'.GTE::getRightName($right).'</label>';
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
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('div#rightseditor').width(
		$('div#contents').width() - 300
	);

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
	var colWidth = gridWidth - 41;
		
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
	var param = [
	 	{name: 'project', value: <?php echo $project->get('project_id'); ?>},
		{name: 'user', value: userId},
		{name: 'query', value: 'select'},
	];
	
	$('div.rightbutton').each(function(){
		var right = this.id.substr(6);

		param[3] = {name: 'right', value: right};
		$.ajax({
			type: 'POST',
			url: '<?php echo LOCAL_PATH; ?>engines/get-rights.php',
			data: param,
			dataType: 'json',
			success: function(data) {
				var div = $('div#right_'+right);
				div.removeClass('loading');
				
				if (data.grant) {
					var grant = 'yes';
					div.addClass('yes');
				} else {
					var grant = 'no';
					div.addClass('no');
				}

				if (data.from != '') {
					div.addClass(data.from);
				}
				
				div.attr('origin', grant+','+data.from);
				div.css('cursor', 'pointer');
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {}
		});

		$(this).click(function(){
			var div = $(this);
			if (div.attr('origin') != undefined) {
				if (div.hasClass('no')) {
					var new_value = 'yes';
				} else {
					var new_value = 'no';
				}

				div.removeClass('yes').removeClass('no').removeClass('user').removeClass('group');
				var originals = div.attr('origin').split(',');

				if (new_value == originals[0]) {
					$('input#i'+div.id).remove();
					if (originals[1] != '') {
						div.addClass(originals[1]);
					}
				} else {
					var input = $('input#i'+div.id);
					if (input.length < 0) {
						$('form#additionnal_rights').append(
							'<input type="hidden" id="i'+div.id+'" name="rights['+right+']" value="'+new_value+'" />'
						);
					} else {
						input.val(new_value);
					}

					if ($('input#submit').length < 0) {
						$('form#additionnal_rights').append(
							'<input type="submit" id="submit" value="<?php echo _('Enregistrer'); ?>" />'
						);
					}
				}
				div.addClass(new_value);
			}
		});
	});
}
</script>