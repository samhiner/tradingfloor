<?php

//have to go into logic folder again because this is only opened from controller
//and controller is outside of logic
include('logic/misc_funcs.php');

// BUSINESS LOGIC

function execTrades() {
	$trades = query('SELECT * FROM trades');
	while ($row = mysqli_fetch_array($trades, MYSQLI_ASSOC)) {
		echo $row['partner'];
	}

	query('DELETE FROM trades');
}

function nextRound() {
	query('UPDATE status SET canTrade = 1');
	execTrades();
	query('UPDATE status SET canTrade = 1, round = round + 1');
}

function startGame() {
	query('UPDATE status SET canTrade = 1, round = 1');
}

function endGame() {
	query('UPDATE status SET canTrade = 0, round = -1');
}

?>