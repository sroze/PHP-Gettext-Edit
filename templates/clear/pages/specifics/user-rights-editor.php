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
					<p></p>
					<div class="rightbutton yes"></div> <?php echo _('Droit impliqué'); ?>
					<p></p>
					<div class="rightbutton yes user"></div> <?php echo _('Droit accordé à l\'utilisateur'); ?>
					<p></p>
					<div class="rightbutton yes group"></div> <?php echo _('Droit accordé à un de ses groupes'); ?>
					<p></p>
					<div class="rightbutton no"></div> <?php echo _('Droit non-accordé'); ?>
					<p></p>
					<div class="rightbutton no user"></div> <?php echo _('Droit refusé à l\'utilisateur'); ?>
					<p></p>
					<div class="rightbutton no group"></div> <?php echo _('Droit refusé au groupe'); ?>
					<p></p>
					<label class="modified">label</label> <?php echo _('Valeur modifiée'); ?>
				</div>
				<form id="additionnal_rights" action="index.php?page=project-users&project=<?php echo $project->get('project_id'); ?>" method="POST">
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
				<input type="hidden" name="action" value="update-user" />
				<input type="hidden" name="project" value="<?php echo $project->get('project_id'); ?>" />
				</form>
			</div>
		</div>
	</div>
</div>
<a id="users_link"></a>
<script type="text/javascript">
$(document).ready(function(){
	$('div#rightseditor').width(
		$('div#contents').width() - 300
	);
	
	var userId = $('#user_field').val();
	var parent = $('div#rights_boxes');

	$("a#users_link").fancybox({
		hideOnOverlayClick: false,
		hideOnContentClick: false,
		centerOnScroll: false,
		frameWidth: 300,
		frameHeight: 140,
		callbackOnShow: function (obj) {
			$('form#groups_form').submit(function(){
				var group = $('select#select_group').val();
				$('.fancy_close', parent).click();

				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: '<?php echo LOCAL_PATH; ?>engines/get-groups.php',
					data: [
						{name: 'project', value: <?php echo $project->get('project_id'); ?>},
						{name: 'user', value: userId},
						{name: 'query', value: 'insert'},
						{name: 'group', value: group}
					],
					success: function (data) {
						if (data != 'ok') {
							alert('Erreur: '+data);
						} else {
							reloadDatagrid();
						}
					}
				});

				return false;
			});
		}
	}, parent);

	// groups informations
	reloadInformations();
});

function reloadInformations (userId)
{	
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
				$('a#users_link').attr('href', 
					'<?php echo LOCAL_PATH; ?>engines/get-groups.php?query=list&output=html&project=<?php echo $project->get('project_id'); ?>'
				);
				$('a#users_link').click();
			}},
			{name: '<?php echo _('Supprimer'); ?>', bclass: 'delete', position: 'left', onpress : function (a,grid){
				if ($('.trSelected',grid).length <= 0) {
					alert('<?php echo _('Vous devez sélectionner au moins un groupe'); ?>');
				} else {
					if ($('.trSelected',grid).length > 1) {
						var string = '<?php echo _('Êtes-vous sur de vouloir supprimer cet utilisateur de ces %d groupes ?'); ?>';
					} else {
						var string = '<?php echo _('Êtes-vous sur de vouloir supprimer cet utilisateur de ce groupe ?'); ?>';
					}
					
					if (confirm(string.replace(/%d/, $('.trSelected',grid).length))) {
						$("#groups_datagrid").editRemove($('.trSelected',grid), 'groups', -1);
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
		if (this.id.substr(0, 6) != 'right_') {
			return;
		}
		var right = this.id.substr(6);
		$(this).addClass('loading');

		param[3] = {name: 'right', value: right};
		$.ajax({
			type: 'POST',
			url: '<?php echo LOCAL_PATH; ?>engines/get-rights.php',
			data: param,
			dataType: 'json',
			success: function(data) {
				var div = $('div#right_'+right);
				div.removeClass('loading').removeClass('yes').removeClass('no');
				
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
				var label = $(div.parent().find('label')[0]);
				label.removeClass('modified');

				if (new_value == originals[0]) {
					$('input#i'+div.attr('id')).remove();
					if (originals[1] != '') {
						div.addClass(originals[1]);
					}

					if ($('label.modified', $('form#additionnal_rights')).length == 0) {
						$('input#submit').remove();
					}
				} else {
					var input = $('input#i'+div.attr('id'));
					if (input.length == 0) {
						$('form#additionnal_rights').append(
							'<input type="hidden" id="i'+div.attr('id')+'" name="rights['+right+']" value="'+new_value+'" />'
						);
					} else {
						input.val(new_value);
					}

					if ($('input#submit').length == 0) {
						$('form#additionnal_rights').append(
							'<input type="submit" id="submit" value="<?php echo _('Enregistrer'); ?>" />'
						);
					}
					label.addClass('modified');
				}
				div.addClass(new_value);
			}
		});

		$('form#additionnal_rights input#iuser').remove();
		$('form#additionnal_rights').append(
			'<input type="hidden" name="user" id="iuser" value="'+userId+'" />'
		);
	});
}
</script>