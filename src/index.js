function adjustTradeTable() {
	currTrades = document.getElementById('trades');
	oldTrades = currTrades.childElementCount - 2;
	newTrades = document.getElementById('numTrades').value;

	if (newTrades > 10) {
		newTrades = 10;
		document.getElementById('numTrades').value = 10;
	} else if (newTrades < 0) {
		newTrades = 0;
		document.getElementById('numTrades').value = 0;
	}

	if (newTrades == 0) {
		document.getElementById('tradeTable').style.display = 'none';
	} else {
		document.getElementById('tradeTable').style.display = 'table';
	}

	if (newTrades > oldTrades) {
		for (var x = 0; x < newTrades - oldTrades; x++) {
			newTradeBox = document.getElementById('tradeBox').cloneNode(true);
			newTradeBox.style.display = 'table-row';
			document.getElementById('trades').appendChild(newTradeBox); //TODO make it so the original trade box doesn't appear
		}
	} else if (oldTrades > newTrades) {
		for (var x = 0; x < oldTrades - newTrades; x++) {
			currTrades.removeChild(currTrades.lastChild);
		}
	}
}

function numInputVal(element) {
	if (element.value <= 0) {
		element.value = '';
	}
}