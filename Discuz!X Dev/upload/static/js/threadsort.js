function xmlobj() {
	var obj = new Object();
	obj.createXMLDoc = function(xmlstring) {
		var xmlobj = false;
		if(window.DOMParser && document.implementation && document.implementation.createDocument) {
			try{
				var domparser = new DOMParser();
				xmlobj = domparser.parseFromString(xmlstring, 'text/xml');
			} catch(e) {
			}
		} else if(window.ActiveXObject) {
			var versions = ["MSXML2.DOMDocument.5.0", "MSXML2.DOMDocument.4.0", "MSXML2.DOMDocument.3.0", "MSXML2.DOMDocument", "Microsoft.XmlDom"];
			for(var i=0; i<versions.length; i++) {
				try {
					xmlobj = new ActiveXObject(versions[i]);
					if(xmlobj) {
						xmlobj.async = false;
						xmlobj.loadXML(xmlstring);
					}
				} catch(e) {}
			}
		}
		return xmlobj;
	};

	obj.xml2json = function(xmlobj, node) {
		var nodeattr = node.attributes;
		if(nodeattr != null) {
			if(nodeattr.length && xmlobj == null) {
				xmlobj = new Object();
			}
			for(var i = 0;i < nodeattr.length;i++) {
				xmlobj[nodeattr[i].name] = nodeattr[i].value;
			}
		}
		var nodetext = "text";
		if(node.text == null) {
			nodetext = "textContent";
		}

		var nodechilds = node.childNodes;
		if(nodechilds != null) {
			if(nodechilds.length && xmlobj == null) {
				xmlobj = new Object();
			}
			for(var i = 0;i < nodechilds.length;i++) {
				if(nodechilds[i].tagName != null) {
					if(nodechilds[i].childNodes[0] != null && nodechilds[i].childNodes.length <= 1 && (nodechilds[i].childNodes[0].nodeType == 3 || nodechilds[i].childNodes[0].nodeType == 4)) {
						if(xmlobj[nodechilds[i].tagName] == null) {
							xmlobj[nodechilds[i].tagName] = nodechilds[i][nodetext];
						} else {
							if(typeof(xmlobj[nodechilds[i].tagName]) == "object" && xmlobj[nodechilds[i].tagName].length) {
								xmlobj[nodechilds[i].tagName][xmlobj[nodechilds[i].tagName].length] = nodechilds[i][nodetext];
							} else {
								xmlobj[nodechilds[i].tagName] = [xmlobj[nodechilds[i].tagName]];
								xmlobj[nodechilds[i].tagName][1] = nodechilds[i][nodetext];
							}
						}
					} else {
						if(nodechilds[i].childNodes.length) {
							if(xmlobj[nodechilds[i].tagName] == null) {
								xmlobj[nodechilds[i].tagName] = new Object();
								this.xml2json(xmlobj[nodechilds[i].tagName], nodechilds[i]);
							} else {
								if(xmlobj[nodechilds[i].tagName].length) {
									xmlobj[nodechilds[i].tagName][xmlobj[nodechilds[i].tagName].length] = new Object();
									this.xml2json(xmlobj[nodechilds[i].tagName][xmlobj[nodechilds[i].tagName].length-1], nodechilds[i]);
								} else {
									xmlobj[nodechilds[i].tagName] = [xmlobj[nodechilds[i].tagName]];
									xmlobj[nodechilds[i].tagName][1] = new Object();
									this.xml2json(xmlobj[nodechilds[i].tagName][1], nodechilds[i]);
								}
							}
						} else {
							xmlobj[nodechilds[i].tagName] = nodechilds[i][nodetext];
						}
					}
				}
			}
		}
	};
	return obj;
}

var xml = new xmlobj();
var xmlpar = xml.createXMLDoc(forum_optionlist);
var forum_optionlist_obj = new Object();
xml.xml2json(forum_optionlist_obj, xmlpar);

function changeselectthreadsort(selectchoiceoptionid, optionid, type) {
	if(selectchoiceoptionid == '0') {
		return;
	}
	var soptionid = 's' + optionid;
	var sselectchoiceoptionid = 's' + selectchoiceoptionid;

	forum_optionlist = forum_optionlist_obj['forum_optionlist'];
	var choicesarr = forum_optionlist[soptionid]['schoices'];
	var lastcount = 1;
	var name = issearch = id = nameid = '';
	if(type == 'search') {
		issearch = ', \'search\'';
		name = ' name="searchoption[' + optionid + '][value]"';
		id = 'id="' + forum_optionlist[soptionid]['sidentifier'] + '"';
	} else {
		name = ' name="typeoption[' + forum_optionlist[soptionid]['sidentifier'] + ']"';
		id = 'id="typeoption_' + forum_optionlist[soptionid]['sidentifier'] + '"';
	}
	if((choicesarr[sselectchoiceoptionid]['slevel'] == 1 || type == 'search') && choicesarr[sselectchoiceoptionid]['scount'] == 1) {
		nameid = name + ' ' + id;
	}
	var selectoption = '<select' + nameid + ' class="ps vm" onchange="changeselectthreadsort(this.value, \'' + optionid + '\'' + issearch + ');checkoption(\'' + forum_optionlist[soptionid]['sidentifier'] + '\', \'' + forum_optionlist[soptionid]['srequired'] + '\', \'' + forum_optionlist[soptionid]['stype'] + '\')"><option value="0">��ѡ��</option>';
	for(var i in choicesarr) {
		nameid = '';
		if((choicesarr[sselectchoiceoptionid]['slevel'] == 1 || type == 'search') && choicesarr[i]['scount'] == choicesarr[sselectchoiceoptionid]['scount']) {
				nameid = name + ' ' + id;
		}
		if(choicesarr[i]['sfoptionid'] != '0') {
			var patrn = new RegExp("^" + choicesarr[i]['sfoptionid'], 'i');
			if(selectchoiceoptionid.match(patrn) == null) {
				continue;
			}
		}
		if(choicesarr[i]['scount'] != lastcount) {
			if(parseInt(choicesarr[i]['scount']) >= (parseInt(choicesarr[sselectchoiceoptionid]['scount']) + parseInt(choicesarr[sselectchoiceoptionid]['slevel']))) {
				break;
			}
			selectoption += '</select>' + "\r\n" + '<select' + nameid + ' class="ps vm" onchange="changeselectthreadsort(this.value, \'' + optionid + '\'' + issearch + ');checkoption(\'' + forum_optionlist[soptionid]['sidentifier'] + '\', \'' + forum_optionlist[soptionid]['srequired'] + '\', \'' + forum_optionlist[soptionid]['stype'] + '\')"><option value="0">��ѡ��</option>';

			lastcount = parseInt(choicesarr[i]['scount']);
		}
		var patrn = new RegExp("^" + choicesarr[i]['soptionid'], 'i');
		var isnext = '';
		if(parseInt(choicesarr[i]['slevel']) != 1) {
			isnext = '&raquo;';
		}
		if(selectchoiceoptionid.match(patrn) != null) {
			selectoption += "\r\n" + '<option value="' + choicesarr[i]['soptionid'] + '" selected="selected">' + choicesarr[i]['scontent'] + isnext + '</option>';
		} else {
			selectoption += "\r\n" + '<option value="' + choicesarr[i]['soptionid'] + '">' + choicesarr[i]['scontent'] + isnext + '</option>';
		}
	}
	selectoption += '</select>';
	if(type == 'search') {
		selectoption  += "\r\n" + '<input type="hidden" name="searchoption[' + optionid + '][type]" value="select">';
	}
	$('select_' + forum_optionlist[soptionid]['sidentifier']).innerHTML = selectoption;
}

function checkoption(identifier, required, checktype, checkmaxnum, checkminnum, checkmaxlength) {
	if(checktype != 'image' && checktype != 'select' && !$('typeoption_' + identifier) || !$('check' + identifier)) {
		return true;
	}

	var ce = $('check' + identifier);
	ce.innerHTML = '';

	if(checktype == 'select') {
		if(required != '0' && $('typeoption_' + identifier) == null) {
			warning(ce, '������Ŀû����д');
			return false;
		} else if(required == '0' && ($('typeoption_' + identifier) == null || $('typeoption_' + identifier).value == '0')) {
			ce.innerHTML = '<img src="' + IMGDIR + '/check_error.gif" width="16" height="16" class="vm" /> ��ѡ����һ��';
			ce.className = "warning";
			return true;
		}
	}

	if(checktype == 'image') {
		var checkvalue = $('sortaid_' + identifier).value;
	} else {
		var checkvalue = $('typeoption_' + identifier).value;
	}

	if(required != '0') {
		if(checkvalue == '' || checkvalue == '0') {
			warning(ce, '������Ŀû����д');
			return false;
		} else {
			ce.innerHTML = '<img src="' + IMGDIR + '/check_right.gif" width="16" height="16" class="vm" />';
		}
	}

	if(checkvalue) {
		if((checktype == 'number' || checktype == 'range') && isNaN(checkvalue)) {
			warning(ce, '������д����ȷ');
			return false;
		} else if(checktype == 'email' && !(/^[\-\.\w]+@[\.\-\w]+(\.\w+)+$/.test(checkvalue))) {
			warning(ce, '�ʼ���ַ����ȷ');
			return false;
		} else if((checktype == 'text' || checktype == 'textarea') && checkmaxlength != '0' && mb_strlen(checkvalue) > checkmaxlength) {
			warning(ce, '��д��Ŀ���ȹ���');
			return false;
		} else if((checktype == 'number' || checktype == 'range')) {
			if(checkmaxnum != '0' && parseInt(checkvalue) > parseInt(checkmaxnum)) {
				warning(ce, '�����������ֵ');
				return false;
			} else if(checkminnum != '0' && parseInt(checkvalue) < parseInt(checkminnum)) {
				warning(ce, 'С��������Сֵ');
				return false;
			}
		} else {
			ce.innerHTML = '<img src="' + IMGDIR + '/check_right.gif" width="16" height="16" class="vm" />';
		}
	}
	return true;
}