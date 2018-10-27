function adjustTrades() {
	console.log('itworks')
	currTrades = document.getElementById('trades');
	oldTrades = currTrades.childElementCount - 2;
	newTrades = document.getElementById('numTrades').value;
	if (newTrades > oldTrades) {
		for (var x = 0; x < newTrades - oldTrades; x++) {
			newTradeBox = document.getElementById('tradeBox').cloneNode(true);
			newTradeBox.style.display = 'block';
			document.getElementById('trades').appendChild(newTradeBox); //TODO make it so the original trade box doesn't appear
		}
	} else if (oldTrades > newTrades) {
		for (var x = 0; x < oldTrades - newTrades; x++) {
			currTrades.removeChild(currTrades.lastChild);
		}
	} else {
		console.log('woah')
	}
}