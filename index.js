//TODO make this whole thing account for having a 0th row of the title/info

function adjustTrades() {
	currTrades = document.getElementById('trades');
	oldTrades = currTrades.childElementCount;
	newTrades = document.getElementById('numTrades').value;
	if (newTrades > oldTrades) {
		for (var x = 0; x < newTrades - oldTrades; x++) {
			makeTradeBox();
		}
	} else if (oldTrades > newTrades) {
		for (var x = 0; x < oldTrades - newTrades; x++) {
			currTrades.removeChild(currTrades.childNodes[-1]);
		}
	}
}

function makeTradeBox() {
	currTrades = document.getElementById('trades');
	row = document.createElement('tr');
	currTrades.appendChild()
	for (x in cols) {//ISSUE fix
		document.createElement('td')
	}
}