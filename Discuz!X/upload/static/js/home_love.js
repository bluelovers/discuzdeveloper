var alreadySelect = {};

function initValue(obj, count, maxSel) {
	var id = obj.id;
	alreadySelect[id] = {
		maxSelect : 0,
		count : 0,
		value : ''
	};
	alreadySelect[id].count = isUndefined(count) ? 0 : count;
	alreadySelect[id].value = obj.value;
	alreadySelect[id].maxSelect = isUndefined(maxSel) ? 3 : maxSel;
}

function checkSelVal(id, obj) {
	var common = '';
	if(obj.checked) {
		alreadySelect[id].count++;
		if(alreadySelect[id].count == alreadySelect[id].maxSelect) {
			alreadySelect[id].value = '';
			var oObj = document.getElementsByName(obj.name);
			for(i = 0; i < oObj.length; i++) {
				if (!oObj[i].checked) {
					oObj[i].disabled = true;
				} else {
					alreadySelect[id].value += common + oObj[i].value;
					common = ' ';
				}
			}
		} else {
			alreadySelect[id].value += ' ' + obj.value;
		}
	} else {
		alreadySelect[id].count--;
		if(alreadySelect[id].count < alreadySelect[id].maxSelect) {
			alreadySelect[id].value = '';
			var oObj = document.getElementsByName(obj.name);
			for(i = 0; i < oObj.length; i++) {
				if(oObj[i].disabled) {
					oObj[i].disabled = false;
				}
				if(oObj[i].checked) {
					alreadySelect[id].value += common + oObj[i].value;
					common = ' ';
				}
			}
		}
	}
	if($(id) != null) {
		$(id).value = alreadySelect[id].value;
	}
}