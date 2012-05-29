// JavaScript Document


var Parser = function() {
	
	this.dataRaw = null;
	this.dataObject = [];
	
};

Parser.prototype.setData = function(data,type,opts) {
	this.dataRaw = data;
	this.dataObject = Parser._parsers[type].call(this,data,opts);
};

Parser.prototype.getColumns = function() {
	var cols = [];
	if(this.dataObject.length > 0) {
		for(var key in this.dataObject[0]) {
			cols.push(key);
		}
	}
	console.log('getColumns',this.dataObject,cols);
	return cols;
};

Parser.prototype.getItems = function() {
	return this.dataObject;
};

Parser.prototype.changeColumn = function(oldKey,newKey) {
	if(this.dataObject.length > 0) {
		var dataObject = [];
		for(var i = 0; i < this.dataObject.length; i++) {
			var obj = {};
			for(var key in this.dataObject[i]) {
				//console.log(typeof(key), key,typeof(oldKey), oldKey,typeof(newKey), newKey);
				if(key == oldKey) {
					console.log(newKey+'');
					obj[newKey+''] = this.dataObject[i][key];
				} else {
					console.log(key+'');
					obj[key+''] = this.dataObject[i][key];
				}
			}
			console.log(obj);
			dataObject.push(obj);
		}
		//this.dataObject = null;
		this.dataObject = dataObject;
		//console.log('changeColumn',oldKey,newKey,this.dataObject);
	}
};


Parser.prototype.pretty = function(obj, indent) {
	
	
	
	var result = [];
	if (obj == null) obj = this.dataObject;
	if (indent == null) indent = "";
	
	for (var property in obj) {
		var value = obj[property];
		if (typeof value == 'string') {
			value = htmlentities(value);
			value = value.length > 128 ? ("'" + value.substr(0,128) + "...'"):("'" + value + "'");
		} else if (value == null) {
			value = "null";
		} else if (typeof value == 'object') {
			var od = this.pretty(value, indent + "  ");
			if (value instanceof Array) {
				//value = "\n" + indent + "[\n" + od + "\n" + indent + "]";
				value = "[\n" + od + "\n" + indent + "]";
			} else {
				//value = "\n" + indent + "{\n" + od + "\n" + indent + "}";
				value = "{\n" + od + "\n" + indent + "}";
			}
		}
		result.push(indent, "'", property, "' : ", value, ",\n");
	}
	
	return result.join('').replace(/,\n$/, "");
};


/*
 *
 * Parsers
 *
 */
Parser._parsers = {

	'json' : function(str) {
		return ($.parseJSON(str) || []);
	},
	
	'csv' : function(e,o) {
		var o = jQuery.extend({
			'separator' : ',',
			'firstRowCols' : false
		},o);
		for(var b=o.separator,f=RegExp("(\\"+b+'|\\r?\\n|\\r|^)(?:"([^"]*(?:""[^"]*)*)"|([^"\\'+b+"\\r\\n]*))","gi"),c=[[]],a=null;a=f.exec(e);){var d=a[1];d.length&&d!=b&&c.push([]);a=a[2]?a[2].replace(RegExp('""',"g"),'"'):a[3];c[c.length-1].push(a)}
		
		if(o.firstRowCols && c && c.length) {
			var cols = [];
			for(var i = 0; i < c[0].length; i++) {
				cols.push(c[0][i]);	
			}
			var items = [];
			if(c.length > 1) {
				for(var i = 1; i < c.length; i++) {
					var item = {};
					for(var ii = 0; ii < cols.length; ii++) {
						item[cols[ii]] = typeof(c[i][ii]) != 'undefined' ? c[i][ii]:null;
					}
					items.push(item);
				}
			}
			c = items;
		}
		
		return c;
	},
	
	'html' : function(e,o) {
		var o = jQuery.extend({
			'parser' : ''
		},o);
		
		try {
			var $html = $(e);
			eval(o.parser);
		} catch(e) {}
		
		return typeof(items) == 'undefined' ? []:items;
	},
	
	'other' : function(e,o) {
		var o = jQuery.extend({
			'parser' : ''
		},o);
		
		try {
			var data = e;
			eval(o.parser);
		} catch(e) {}
		
		return typeof(items) == 'undefined' ? []:items;
	}

};


/*
 *
 * Utilities functions
 *
 */
function htmlentities(a,d,f,e){var b=this.get_html_translation_table("HTML_ENTITIES",d),c="",a=null==a?"":a+"";if(!b)return!1;d&&"ENT_QUOTES"===d&&(b["'"]="&#039;");if(e||null==e)for(c in b)b.hasOwnProperty(c)&&(a=a.split(c).join(b[c]));else a=a.replace(/([\s\S]*?)(&(?:#\d+|#x[\da-f]+|[a-zA-Z][\da-z]*);|$)/g,function(a,d,e){for(c in b)b.hasOwnProperty(c)&&(d=d.split(c).join(b[c]));return d+e});return a};

function get_html_translation_table(c,d){var a={},i={},e,g={},f={},b={},h={};g[0]="HTML_SPECIALCHARS";g[1]="HTML_ENTITIES";f[0]="ENT_NOQUOTES";f[2]="ENT_COMPAT";f[3]="ENT_QUOTES";b=!isNaN(c)?g[c]:c?c.toUpperCase():"HTML_SPECIALCHARS";h=!isNaN(d)?f[d]:d?d.toUpperCase():"ENT_COMPAT";if("HTML_SPECIALCHARS"!==b&&"HTML_ENTITIES"!==b)throw Error("Table: "+b+" not supported");a["38"]="&amp;";"HTML_ENTITIES"===b&&(a["160"]="&nbsp;",a["161"]="&iexcl;",a["162"]="&cent;",a["163"]="&pound;",a["164"]="&curren;",
a["165"]="&yen;",a["166"]="&brvbar;",a["167"]="&sect;",a["168"]="&uml;",a["169"]="&copy;",a["170"]="&ordf;",a["171"]="&laquo;",a["172"]="&not;",a["173"]="&shy;",a["174"]="&reg;",a["175"]="&macr;",a["176"]="&deg;",a["177"]="&plusmn;",a["178"]="&sup2;",a["179"]="&sup3;",a["180"]="&acute;",a["181"]="&micro;",a["182"]="&para;",a["183"]="&middot;",a["184"]="&cedil;",a["185"]="&sup1;",a["186"]="&ordm;",a["187"]="&raquo;",a["188"]="&frac14;",a["189"]="&frac12;",a["190"]="&frac34;",a["191"]="&iquest;",a["192"]=
"&Agrave;",a["193"]="&Aacute;",a["194"]="&Acirc;",a["195"]="&Atilde;",a["196"]="&Auml;",a["197"]="&Aring;",a["198"]="&AElig;",a["199"]="&Ccedil;",a["200"]="&Egrave;",a["201"]="&Eacute;",a["202"]="&Ecirc;",a["203"]="&Euml;",a["204"]="&Igrave;",a["205"]="&Iacute;",a["206"]="&Icirc;",a["207"]="&Iuml;",a["208"]="&ETH;",a["209"]="&Ntilde;",a["210"]="&Ograve;",a["211"]="&Oacute;",a["212"]="&Ocirc;",a["213"]="&Otilde;",a["214"]="&Ouml;",a["215"]="&times;",a["216"]="&Oslash;",a["217"]="&Ugrave;",a["218"]=
"&Uacute;",a["219"]="&Ucirc;",a["220"]="&Uuml;",a["221"]="&Yacute;",a["222"]="&THORN;",a["223"]="&szlig;",a["224"]="&agrave;",a["225"]="&aacute;",a["226"]="&acirc;",a["227"]="&atilde;",a["228"]="&auml;",a["229"]="&aring;",a["230"]="&aelig;",a["231"]="&ccedil;",a["232"]="&egrave;",a["233"]="&eacute;",a["234"]="&ecirc;",a["235"]="&euml;",a["236"]="&igrave;",a["237"]="&iacute;",a["238"]="&icirc;",a["239"]="&iuml;",a["240"]="&eth;",a["241"]="&ntilde;",a["242"]="&ograve;",a["243"]="&oacute;",a["244"]=
"&ocirc;",a["245"]="&otilde;",a["246"]="&ouml;",a["247"]="&divide;",a["248"]="&oslash;",a["249"]="&ugrave;",a["250"]="&uacute;",a["251"]="&ucirc;",a["252"]="&uuml;",a["253"]="&yacute;",a["254"]="&thorn;",a["255"]="&yuml;");"ENT_NOQUOTES"!==h&&(a["34"]="&quot;");"ENT_QUOTES"===h&&(a["39"]="&#39;");a["60"]="&lt;";a["62"]="&gt;";for(e in a)a.hasOwnProperty(e)&&(i[String.fromCharCode(e)]=a[e]);return i}