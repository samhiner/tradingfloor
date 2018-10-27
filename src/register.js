function inputVal(type) {
	if ((document.getElementById(type).value.length >= 1) && (document.getElementById('pass').value == document.getElementById('passConf').value)) {
		document.getElementById('submit').disabled = false;
	} else {
		document.getElementById('submit').disabled = true;
	}
}