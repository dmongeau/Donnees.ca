$(function() {
	
	var CONTEXT = typeof($dialog) != 'undefined' ? $dialog:$('#content');
	
	/**
	 *
	 * Init Tabs
	 *
	 */
	$('#import, #options .formats, #result .item',CONTEXT).tabs();
	
	/**
	 *
	 * Init editors
	 *
	 */
	var editors = {};
	function initEditor(id,mode) {
		if(!editors[id]) {
			editors[id] = ace.edit(id);
			editors[id].setTheme("ace/theme/textmate");
			editors[id].getSession().setMode("ace/mode/"+(!mode ? 'text':mode));
			
		}
		return editors[id];
	}
	//Editor for raw data
	var editorRaw = initEditor('editorRaw','text');
	editorRaw.on('blur', function() {
		if(rawData && rawData.content) {
			rawData.content = editorRaw.getSession().getDocument().getAllLines().join("\n");
			updateCurrentFormatData();
		}
	});
	
	//Editor for html format
	var editorHTML = initEditor('editorHTML','javascript');
	editorHTML.on('blur', function() {
		if(rawData && rawData.content) {
			$('#format-html input[name=parser]').val(editorHTML.getSession().getDocument().getAllLines().join("\n"));
			updateCurrentFormatData();
		}
	});
	//Editor for other formats
	var editorOther = initEditor('editorOther','javascript');
	editorOther.on('blur', function() {
		if(rawData && rawData.content) {
			$('#format-other input[name=parser]').val(editorOther.getSession().getDocument().getAllLines().join("\n"));
			updateCurrentFormatData();
		}
	});
	
	

	/**
	 *
	 * Callback when a file is imported
	 *
	 */
	var rawData = null;
	window.fileImportCallback = function(response) {

		if(!response.success) {
			alert(response.error);
		} else {

			var data = response.response;
			rawData = data;
			
			if(data.type == 'file') var type = 'Fichier';
			else var type = 'URL';
			$('#source .type').text(type);
			$('#source .name').text(data.name);
			$('#source .size').text((data.size/1024).toFixed(2)+' Ko');
			$('.source-hide').slideUp('fast');
			$('.source-show').show();
			editors['editorRaw'].getSession().setValue(data.content);
			
			var format = 'other';
			for(var key in FORMATS_SUPPORTED) {
				var index = $.inArray(data.extension,FORMATS_SUPPORTED[key]['extensions']);
				if(index != -1) {
					format = FORMATS_SUPPORTED[key]['extensions'][index];
				}
			}
			$('#options .formats').tabs('select','format-'+format);
			
		}

	};
	
	/**
	 *
	 * Reset the current data source
	 *
	 */
	function resetSource() {
		
		$('#source').find('.type').text('');
		$('#source').find('.name').text('');
		$('#source').find('.size').text('');
		$('#result-cols ul').html('');
		$('#result-rows .total').html('');
		$('#result-rows .count').html('');
		$('#result-rows ul').html('');
		
		$('.source-show').hide();
		$('.source-hide').slideDown('fast');
	}
	$('#source a.edit').click(function(e) {
		e.preventDefault();
		resetSource();
	});
	
	/**
	 *
	 * Update data
	 *
	 */
	var $colTemplate = $('#result-cols ul li.tmpl').clone().removeClass('tmpl');
	$('#result .cols ul li.tmpl').remove();
	
	var $rowTemplate = $('#result-rows ul li.tmpl').clone().removeClass('tmpl');
	$('#result-rows ul li.tmpl').remove();
	var $rowColTemplate = $rowTemplate.find('.col').clone();
	$rowTemplate.find('.col').remove();
	
	//Update data with the current format tab
	function updateCurrentFormatData() {
		updateData($('#options .ui-tabs-panel:not(.ui-tabs-hide)'));
	}
	
	//Update data in a certain format
	function updateData($format) {
		//Get format
		var format = $format.attr('id').match(/^format\-(.*)$/)[1];
		//Get fields for the format
		var opts = {};
		$format.find(':input').each(function() {
			if($(this).is('input[type=checkbox],input[type=radio]')) {
				opts[$(this).attr('name')] = $(this).is(':checked');
			} else {
				opts[$(this).attr('name')] = $(this).val();
			}
		});
		//Parse data
		parseRawData(format, opts, updateResult)
	}
	
	//Update the result of the parsing
	function updateResult(cols,items) {
		if(items && items.length > 1) {
			
			//Update columns
			$('#result-cols .total').text(cols.length);
			$('#result-cols ul').html('');
			for(var i = 0; i < cols.length; i++) {
				var $col = $colTemplate.clone();
				$col.find('input[name="cols_name[]"]').val(cols[i]);
				$col.find('input').blur(attachColumnChangeEvent(cols[i]));
				$('#result-cols ul').append($col);
			}
			
			//Update rows
			$('#result-rows .total').text(items.length);
			$('#result-rows ul').html('');
			function createRow(row,index) {
				var $row = $rowTemplate.clone();
				$row.find('.name').text('Item #'+(index+1))
				for(var key in row) {
					var $c = $rowColTemplate.clone();
					$c.find('.key').text(key);
					$c.find('.value').text(row[key]);
					$row.append($c);
				}
				$row.find('.col:last').addClass('last');
				return $row;
			}
			$('#result-rows ul').append(createRow(items[0],0));
			if(items.length > 2) {
				var between = (items.length-2);
				var s = between > 1 ? 's':'';
				$('#result-rows ul').append('<li class="between"><a href="#">'+between+' autre'+s+' item'+s+'...</a></li>');
				$('#result-rows ul li.between a').click(function(betweenItems) {
					var remainingItems = betweenItems.length;
					var startIndex = 1;
					function showRows(e) {
						e.preventDefault();
						$('#result-rows ul li.between').remove();
						var maxIndex = remainingItems > 25 ? 25:remainingItems;
						for(var i = 0; i < maxIndex; i++){
							$('#result-rows ul li:last').before(createRow(betweenItems[i],i+startIndex));
						}
						betweenItems.splice(0,maxIndex);
						startIndex += maxIndex;
						remainingItems = betweenItems.length;
						if(remainingItems > 0) {
							var s = remainingItems > 1 ? 's':'';
							$('#result-rows ul li:last').before('<li class="between"><a href="#">'+remainingItems+' autre'+s+' item'+s+'...</a></li>');
							$('#result-rows ul li.between a').click(showRows);
						}
					}
					return showRows;
				}(items.slice(1,-1)))
			}
			var lastIndex = items.length-1;
			$('#result-rows ul').append(createRow(items[lastIndex],lastIndex));
			
			//Update section visibility
			$('#result .item').show();
			$('#result .waiting').hide();
		} else {
			//Update section visibility
			$('#result .waiting').show();
			$('#result .item').hide();
		}
	}
	
	/**
	 *
	 * Parse current data
	 *
	 */
	var parser = new Parser();
	var latestParser = null;
	var parseRawTimeout = null;
	function parseRawData(type,opts,callback) {
		
		opts = (opts || {});
		
		if(
			latestParser &&
			latestParser.data == rawData.content &&
			latestParser.type == type &&
			latestParser.opts == opts
		) return;
		
		if(parseRawTimeout) {
			clearTimeout(parseRawTimeout);
			parseRawTimeout = null;
		}
		
		latestParser = {
			'data': rawData.content,
			'type': type,
			'opts': opts
		};
		
		parseRawTimeout = window.setTimeout(function() {
			parser.setData(latestParser.data,latestParser.type,latestParser.opts);
			var cols = parser.getColumns();
			var items = parser.getItems();
			callback(cols,items);
		},300);
	}
	
	/**
	 *
	 * Attach events that will trigger data update
	 *
	 */
	function attachColumnChangeEvent(currentData) {
		var lastData = currentData;
		return function() {
			var data = $(this).val();
			if(lastData != data) {
				parser.changeColumn(lastData,data);
				lastData = data;
			}
			updateResult(parser.getColumns(),parser.getItems());
		}
	}
	$('#options input[type=text], #options textarea').blur(function() {
		var $format = $(this).parents('div.format').eq(0);
		updateData($format);
	});
	
	$('#options input[type=checkbox]').click(function() {
		var $format = $(this).parents('div.format').eq(0);
		updateData($format);
	});
	
	$('#options select').change(function() {
		var $format = $(this).parents('div.format').eq(0);
		updateData($format);
	});
	
	/*
	 *
	 * Change mysql method
	 *
	 */
	$('input[name=mysql_method]',CONTEXT).click(function() {
		
		var $input = $(this).parents('.option').eq(0).find('.input');
		
		if($(this).is(':checked') && !$input.is(':visible')) {
			$(this).parents('.field').eq(0).find('.input:visible').slideUp('fast');
			$input.slideDown('fast');
		}
		
	});
	$('input[name=mysql_method]:checked',CONTEXT).trigger('click');
	
	$('#import input[name=file]').click(function() {
		$('#import input[name=url]').val('');
	});
	$('#import input[name=url]').keyup(function() {
		$('#import input[name=file]').val('');
	});
	
	
});