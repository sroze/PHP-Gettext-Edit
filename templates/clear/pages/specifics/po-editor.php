<form id="msgeditorform" method="POST" action="">
	<table id="po_datagrid"></table>
	<div id="msgeditor" class="flexigrid my">
		<div class="mDiv">
			<div class="title"><?php echo _('Édition de la séléction'); ?></div>
		</div>
		<div class="my grid">
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
			<div><input id="right_fuzzy" type="checkbox" name="fuzzy" value="true" /> Fuzzy</div>
			<div><input id="right_row_id" type="hidden" name="rowid" /><input type="submit" value="<?php echo _('Sauvegarder'); ?>" /></div>
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
			{name: '<?php echo _('Ajouter'); ?>', bclass: 'add', onpress : function (a,grid){
				var new_id = $("#po_datagrid").editAdd({id:0,cell:[0, '', ''],comments:[],references:[],fuzzy:false});
				$('tr#row'+new_id, grid).dblclick();
			}},
			{name: '<?php echo _('Supprimer'); ?>', bclass: 'delete', onpress : function (a,grid){
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
			{separator: true},
			{name: 'Éditer', bclass: 'edit', onpress: function (a,grid){
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
		height: 400
	});

	$('div#msgeditor').width(gridWidth);
	$('form#msgeditorform').submit(function(){
		$('div#right_message').slideUp('fast');
		$("#po_datagrid").editSave(
			$('textarea#right_msgid').val(),
			$('textarea#right_msgstr').val(),
			$('textarea#right_comments').val(),
			$('input#right_fuzzy').attr('checked')
		);
		
		return false;
	});
});
</script>
<div class="clear" />