/*
 * PHP-Gettext-Edit jQuery plugin for users.
 * 
 * Copyright (c) 2010 Samuel ROZE <samuel.roze@gmail.com>
 */

;(function($) {
	var elem, opts, paramstring;
	
	$.fn.gteusers = function(o) {
		var settings		= $.extend({}, $.fn.gteusers.defaults, o);
		var datagrid		= this;

		function _initialize() {
			elem = this;
			opts = $.extend({}, settings);

			var gridWidth = $('div#contents').width() - 20;
			var colWidth = (gridWidth - 65) / 3;
			
			paramstring = '';
			for (var i = 0; i < opts.params.length; i++) {
				paramstring += '&'+opts.params[i].name+'='+opts.params[i].value;
			}
			
			$("a#users_link").fancybox({
				hideOnOverlayClick: false,
				hideOnContentClick: false,
				centerOnScroll: false,
				frameWidth: gridWidth,
				frameHeight: $(window).height() - 100
			}, $('body'));
			
			datagrid.flexigrid({
				url: opts.localpath+'engines/get-users.php',
				dataType: 'json',
				colModel: [
					{display: opts.colNames[0], name : 'id', width : 15, sortable : false, align: 'left'},
					{display: opts.colNames[1], name : 'username', width: colWidth, sortable : false, align: 'left'},
					{display: opts.colNames[2], name : 'groups', width : colWidth, sortable : false, align: 'left'},
					{display: opts.colNames[3], name : 'rights', width : colWidth, sortable : false, align: 'left'}
				],
				buttons: [
					{name: opts.translations.ajouter, bclass: 'add', position: 'left', onpress : function (a,grid){
						$('a#users_link').attr('href', 
							opts.localpath+'index.php?only&page=project-users-add'+paramstring
						);
						$('a#users_link').click();
					}},
					{name: opts.translations.supprimer, bclass: 'delete', position: 'left', onpress : function (a,grid){
						if ($('.trSelected',grid).length <= 0) {
							alert(opts.translations.userdeleteminus);
						} else {
							if ($('.trSelected',grid).length > 1) {
								var string = opts.translations.userdeletemultiple;
							} else {
								var string = opts.translations.userdeletesingle;
							}
							
							if (confirm(string.replace(/%d/, $('.trSelected',grid).length))) {
								datagrid.editRemove($('.trSelected',grid));
							}
						}
					}},
					{separator: true, position: 'left'},
					{name: opts.translations.editer, bclass: 'edit', position: 'left', onpress: function (a,grid){
						if ($('.trSelected',grid).length == 0) {
							alert(opts.translations.selectoneuser);
						} else {
							$('.trSelected:first',grid).dblclick();
						}
					}}
				],
				params: opts.params,
				usepager: false,
				title: opts.translations.utilisateurs,
				useRp: false,
				showTableToggleBtn: false,
				width: gridWidth,
				height: 250,
				dblclickCallback: function (object) {
					var user_id = object.id.substr(3);
					
					$('a#users_link').attr('href', 
						opts.localpath+'index.php?only&page=project-users-edit&user='+user_id+paramstring
					);
					$('a#users_link').click();
				},
				onSuccess: function () {
					// We'll check for each tr rows when we will add them additionnal data
					$('tbody tr', datagrid).each(function(){
						if (this.id.substr(0, 3) != 'row') {
							return;
						}
						var userId = this.id.substr(3);
						var tr = this;
						var p = datagrid[0].p;

						var param = [
							{name: 'user', value: userId},
							{name: 'query', value: 'select-more'}
						];

						for (var pi = 0; pi < p.params.length; pi++) {
							param[param.length] = p.params[pi];
						}
											
						$.ajax({
							type: p.method,
							url: p.url,
							data: param,
							dataType: p.dataType,
							success: function(data) {
								$($('div', $('td', tr)[2])[0]).text(data.groups.join(', '));
								$($('div', $('td', tr)[3])[0]).text(data.rights.join(', '));
							},
							error: function(XMLHttpRequest, textStatus, errorThrown) {}
						});
					});
				}
			});
			

			return false;
		}
		
		_initialize();
	};
	
	$.fn.gteusers.defaults = {};
})(jQuery);