//make sure all input meets certain requirements before allowing registration
//input is verified again on the backend, this just gives instant feedback to users
function inputVal() {
	var errors = document.getElementById('errors').innerHTML
	var nameLen = document.getElementById('username').value.length
	var password = document.getElementById('pass').value
	var passwordConf = document.getElementById('passConf').value

	//clear error message as it is filled in this function
	errors = ''
	var canSubmit = true

	if (nameLen < 1) {
		errors += "No username.<br>"
		canSubmit = false
	} else if (nameLen > 10) {
		errors += "Username too long.<br>"
		canSubmit = false
	}

	if (password != passwordConf) {
		errors += "Password and confirmation do not match.<br>"
		canSubmit = false
	}

	if (password.length < 1) {
		errors += "No password.<br>"
		canSubmit = false
	} else if (password.length > 10) {
		errors += "Password too long.<br>"
		canSubmit = false
	}

	if (canSubmit) {
		document.getElementById('submit').disabled = false;
	} else {
		document.getElementById('submit').disabled = true;
	}
}