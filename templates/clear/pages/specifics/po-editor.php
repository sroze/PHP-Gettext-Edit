<form id="msgeditorform" method="POST" action="">
	<table id="po_datagrid"></table>
	<div id="msgeditor" class="flexigrid my">
		<div class="mDiv">
			<div class="title"><?php echo _('Édition de la séléction'); ?></div>
		</div>
		<div class="my grid">
			<div id="right_grid_contents" style="display: none;">
				<div id="right_message" class="message" style="display: none;"></div>
				<fieldset>
					<legend><?php echo _('Message d\'origine'); ?></legend>
					<textarea id="right_msgid" name="msgid"></textarea>
				</fieldset>
				<fieldset>
					<legend><?php echo _('Traduction'); ?></legend>
					<textarea id="right_msgstr" name="msgstr"></textarea>
				</fieldset>
				<fieldset>
					<legend><?php echo _('Commentaires'); ?></legend>
					<textarea id="right_comments" name="comments"></textarea>
				</fieldset>
				<fieldset>
					<legend><?php echo _('Références'); ?></legend>
					<ul id="right_references"></ul>
				</fieldset>
				<div><label><input id="right_fuzzy" type="checkbox" name="fuzzy" value="true" /> Fuzzy</label></div>
				<div><input id="right_row_id" type="hidden" name="rowid" /><input type="submit" value="<?php echo _('Sauvegarder'); ?>" /></div>
			</div>
			<div id="right_grid_no_contents">
				<p align="center"><?php echo _('Double-cliquez sur un message pour le traduire et afficher les informations complémentaires.'); ?></p>
			</div>
		</div>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function() {
	var gridWidth = Math.round($('div#contents').width() / 2) - 20;
	var colWidth = (gridWidth - 15) / 2 - 28;
	
	$("#po_datagrid").flexigrid({
		url: '<?php echo LOCAL_PATH; ?>engines/po-edit.php',
		dataType: 'json',
		colModel: [
			{display: 'Fuzzy', name : 'fuzzy', width : 15, sortable : false, align: 'center'},
			{display: '<?php echo str_replace('\'', '\\\'', _('Chaine d\'origine')); ?>', name : 'msgid', width : colWidth, sortable : true, align: 'left'},
			{display: '<?php echo _('Traduction'); ?>', name : 'msgstr', width : colWidth, sortable : true, align: 'left'}
		],
		buttons: [
			{name: '<?php echo _('Ajouter'); ?>', bclass: 'add', position: 'left', onpress : function (a,grid){
				var new_id = $("#po_datagrid").editAdd({id:0,cell:[0, '', ''],comments:[],references:[],fuzzy:false});
				$('tr#row'+new_id, grid).dblclick();
			}},
			{name: '<?php echo _('Supprimer'); ?>', bclass: 'delete', position: 'left', onpress : function (a,grid){
				if ($('.trSelected',grid).length <= 0) {
					alert('<?php echo _('Vous devez sélectionner au moins un élément'); ?>');
				} else {
					if ($('.trSelected',grid).length > 1) {
						var string = '<?php echo _('Êtes-vous sur de vouloir supprimer ces %d éléments ?'); ?>';
					} else {
						var string = '<?php echo _('Êtes-vous sur de vouloir supprimer cet élément ?'); ?>';
					}
					
					if (confirm(string.replace(/%d/, $('.trSelected',grid).length))) {
						$("#po_datagrid").editRemove($('.trSelected',grid));
					}
				}
			}},
			{name: '<?php echo _('Copier à droite'); ?>', bclass: 'to_right', position: 'right', onpress: function (a, grid) {
				$("#po_datagrid").copyToMsgstr($('.trSelected',grid));
			}},
			{separator: true, position: 'left'},
			{name: 'Éditer', bclass: 'edit', position: 'left', onpress: function (a,grid){
				if ($('.trSelected',grid).length == 0) {
					alert('<?php echo _('Vous devez sélectionner un élément'); ?>');
				} else {
					$('.trSelected:first',grid).dblclick();
				}
			}}
		],
		searchitems: [
			{display: '<?php echo str_replace('\'', '\\\'', _('Chaine d\'origine')); ?>', name : 'msgid', isdefault: true},
			{display: '<?php echo _('Traduction'); ?>', name : 'msgstr'}
		],
		params:[
			{name: 'project', value: '<?php echo $project->get('project_id'); ?>'},
			{name: 'language', value: '<?php echo $language->getCode(); ?>'},
			{name: 'file', value: '<?php echo $language_file->getName(); ?>'}
		],
		sortname: "msgid",
		sortorder: "asc",
		usepager: false,
		title: '<?php echo _('Traductions'); ?>',
		useRp: false,
		showTableToggleBtn: false,
		width: gridWidth,
		height: 400,
		dblclickCallback: function (object) {
			if ($('textarea#right_msgid').val() != '') {
				$("#po_datagrid").editSave(
					$('textarea#right_msgid').val(),
					$('textarea#right_msgstr').val(),
					$('textarea#right_comments').val(),
					$('input#right_fuzzy').attr('checked'),
					false
				);
			}
			
			$('tr.trSelected').removeClass('trSelected');
			$(object).toggleClass('trSelected').focus();
			$('div#right_message').slideUp();
			
			var div_contents = $('div#right_grid_contents');
			if (div_contents.attr('display') != 'block') {
				div_contents.slideDown('fast');
				$('div#right_grid_no_contents').slideUp();
			}
			
			var trId = object.id.substr(3);
			var id = searchRowId('id', trId);
			var row = $("#po_datagrid")[0].grid.storedData.rows[id];

			$('input#right_row_id').val(trId);
			$('textarea#right_msgid').val(row.cell[1]);
			$('textarea#right_msgstr').val(row.cell[2]);
			$('textarea#right_comments').val(row.comments);
			
			$('ul#right_references').empty();
			if (row.references.length == 0) {
				$('ul#right_references').append('<li><em>Aucune référence</em></li>');
			} else {
				for (var iref = 0; iref < row.references.length; iref++) {
					$('ul#right_references').append('<li>'+row.references[iref]+'</li>');
				}
			}
			
			if (row.fuzzy) {
				$('input#right_fuzzy').attr('checked', true);
			} else {
				$('input#right_fuzzy').removeAttr('checked');
			}
		}
	});

	$('div#msgeditor').width(gridWidth);
	$('form#msgeditorform').submit(function(){
		$('div#right_message').slideUp('fast');
		$("#po_datagrid").editSave(
			$('textarea#right_msgid').val(),
			$('textarea#right_msgstr').val(),
			$('textarea#right_comments').val(),
			$('input#right_fuzzy').attr('checked'),
			true
		);
		
		return false;
	});
});
</script>
<div class="clear" />