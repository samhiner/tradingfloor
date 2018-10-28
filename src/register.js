//make sure all input meets certain requirements before allowing registration
//input is verified again on the backend, this just gives instant feedback to users
function inputVal() {
	var nameLen = document.getElementById('username').value.length
	var password = document.getElementById('password').value
	var passwordConf = document.getElementById('passwordConf').value

	//clear error message as it is filled in this function
	document.getElementById('errors').innerHTML = ''
	var canSubmit = true

	if (nameLen < 1) {
		document.getElementById('errors').innerHTML += "No username.<br>"
		canSubmit = false
	} else if (nameLen > 10) {
		document.getElementById('errors').innerHTML += "Username too long.<br>"
		canSubmit = false
	}

	if (password != passwordConf) {
		document.getElementById('errors').innerHTML += "Password and confirmation do not match.<br>"
		canSubmit = false
	}

	if (password.length < 1) {
		document.getElementById('errors').innerHTML += "No password.<br>"
		canSubmit = false
	}

	if (canSubmit) {
		document.getElementById('submit').disabled = false;
	} else {
		document.getElementById('submit').disabled = true;
	}
}