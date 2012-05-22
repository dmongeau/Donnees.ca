$(function() {
	
	var CONTEXT = typeof($dialog) != 'undefined' ? $dialog:$('#content');
	
	/*
	 *
	 * Change type
	 *
	 */
	$('input[name=type]',CONTEXT).click(function() {
		
		var $form = $('.form.'+$(this).val(),CONTEXT).length ? $('.form.'+$(this).val(),CONTEXT):$('.form.file',CONTEXT);
		
		if($(this).is(':checked') && !$form.is(':visible')) {
			$('div.form:visible',CONTEXT).slideUp('fast');
			$form.slideDown('fast');
		}
		
	});
	$('input[name=type]:checked',CONTEXT).trigger('click');
	
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
	
	
	/*
	 *
	 * Schema columns
	 *
	 */
	var $colTemplate = $('#schema .cols .tmpl').clone();
	$('#schema .cols .tmpl').remove();
	
	function getColElement(data) {
		var $col = $colTemplate.clone();
		$col.find('h3 a').text(data.key);
		$col.find('input[name="cols_key[]"]').val(data.key);
		$col.find('input[name="cols_description[]"]').val(data.description);
		return $col;
	}
	
	function buildAccordion(cols) {
		
		resetAccordion();
		
		for(var i = 0; i < cols.length; i++) {
			
			if(typeof(cols[i]) == 'string') {
				cols[i] = {
					'key' : cols[i],
					'description' : ''
				};
			}
			
			var $col = getColElement(cols[i]);
			var $h3 = $col.find('h3');
			var $content = $col.find('div:eq(0)');
			$('#schema .cols .list').append($h3);
			$('#schema .cols .list').append($content);
			$content.find('input[name="cols_key[]"]').keyup(function(val) {
				
				var oldValue = val;
				return function() {
					var newValue = $(this).val();
					if(newValue != oldValue) {
						parser.changeColumn(oldValue,newValue);
						oldValue = newValue;
						$(this).parents('.ui-accordion-content').prev('h3').find('a').text(newValue);
						$('#schema .dataObject .inner').html('<pre>'+parser.pretty()+'\n</pre>');
					}
				}
				
			}($content.find('input[name="cols_key[]"]').val()));
		}
		$('#schema .cols .list').accordion({
			collapsible : true
		});
	}
	
	function resetAccordion() {
		if($('#schema .cols .list').accordion('widget')) {
			$('#schema .cols .list').accordion('destroy');
		}
		$('#schema .cols .list').html('');
	}
	
	
	
	function resetSchema() {
		resetAccordion();
		$('#schema .data .inner').html('');
	}
	
	/*
	 *
	 * Fetch URL
	 *
	 */
	function fetchURL(url) {
		
		var type = $('input[name=type]:checked').val();
		
		$('#source .selectForm').slideUp('fast');
		$('#source .selectedItem .label a').html('Chargement de '+url+'...');
		$('#source .selectedItem').show();
		$('#source .selectedItem .options:visible').hide();
		
		$.getJSON('/fetch/url.json?url='+escape(url)+'&type='+escape(type),function(data) {
			if(data.success) {
				
				$('#source .selectedItem .options.'+type).show();
				
				var content = type == 'html' ? htmlentities(data.response):data.response;
				$('#source .selectedItem .dataRaw .inner').css('height','auto');
				$('#source .selectedItem .dataRaw .inner').html('<pre>'+content+'\n</pre>');
				$('#source .selectedItem .dataRaw').slideDown('fast');
				if($('#source .selectedItem .dataRaw .inner pre').height() > 300) {
					$('#source .selectedItem .dataRaw .inner').css('height','300px');
				}
				
				selectSource({
					'name' : url,
					'type' : type
				});
				
				currentRawData = data.response;
				parseRawData(data.response,type);
				
			} else {
				resetSource();
			}
		});
		
	}
	
	var parser = new Parser();
	var currentRawData = null;
	function parseRawData(rawData,type,opts) {
		parser.setData(rawData,type,(opts || {}));
		var cols = parser.getColumns();
		if(cols.length) {
			buildAccordion(cols);
		}
		$('#schema .data .inner').html('<pre>'+parser.pretty()+'\n</pre>');
	}
	
	var editor = ace.edit("htmlParserEditor");
	editor.setTheme("ace/theme/textmate");
	editor.getSession().setMode("ace/mode/javascript");
	editor.on('blur', function() {
		parseRawData(currentRawData,'html',{
			'parser' : editor.getSession().getDocument().getAllLines().join("\n")
		});
	});
	$('#source .options.html .editor').css('fontSize','12px');
	
	/*
	 *
	 * Data source
	 *
	 */
	function selectSource(source) {
		var typeText = $.trim($('input[name=type][value="'+source.type+'"]').parents('label').eq(0).text());
		$('#source .selectedItem .label a').html('<strong>'+typeText+'</strong> '+source.name);
	}
	
	function resetSource() {
		$('#source .selectedItem .dataRaw').hide();
		$('#source .selectedItem').hide();
		$('#source .selectForm').slideDown('fast');
		resetSchema();
	}
	
	//Fetch data source
	$('.form.file .fetch button').click(function(e) {
		e.preventDefault();
		if($('input[name=url]').val().length) {
			fetchURL($('input[name=url]').val());
		}
	});
	
	//Reset data source
	$('#source .selectedItem .links a.change').button({
		'icons' : {primary:'ui-icon-close'}
	});
	$('#source .selectedItem .links a.change').click(function(e) {
		e.preventDefault();
		resetSource();
	});
	
});