<?php

//have to go into logic folder again because this is only opened from controller
//and controller is outside of logic
include('logic/misc_funcs.php');

// BUSINESS LOGIC

//get the inverse of the trade submitted (should be the trade that the partner submitted)
function flipTradeSide($trade) {
	//flip trader and partner
	$partner = $trade['partner'];
	$trade['partner'] = $trade['trader'];
	$trade['trader'] = $partner;
	//flip the buy/sell status
	$trade['transactType'] = $trade['transactType'] ? 0 : 1;

	return $trade;
}

function execTrade($trade) {
	//make sure we are looking at the buyers perspective
	if ($trade['transactType'] === 0) {
		$trade = flipTradeSide($trade);
	}

	$buyer = mysqli_fetch_assoc(query('SELECT * FROM users WHERE username = ?', 's', $trade['trader']));
	$seller = mysqli_fetch_assoc(query('SELECT * FROM users WHERE username = ?', 's', $trade['partner']));

	if ($seller[$trade['stock']] - $trade['amt'] >= 0) {
		//update buyer's acct
		query("UPDATE users SET balance = balance - ?, " . $trade["stock"] . " = " . $trade["stock"] . " + ? WHERE username = ?", 'iis',  $trade["price"], $trade["amt"], $trade["trader"]);
		//update seller's acct
		query("UPDATE users SET balance = balance + ?, " . $trade["stock"] . " = " . $trade["stock"] . " - ? WHERE username = ?", 'iis',  $trade["price"], $trade["amt"], $trade["partner"]);
		return true;
	} else {
		return false;
	}
}

function matchTrades() {
	$trades = query('SELECT * FROM trades');
	$register = [];
	$failedTrades = [];
	while ($row = mysqli_fetch_array($trades, MYSQLI_ASSOC)) {
		$oppTrade = flipTradeSide($row);
		$match = array_search($oppTrade, $register);

		if ($match !== false) {
			array_splice($register, $match, 1);
			$tradeSuccess = execTrade($row);

			if (!$tradeSuccess) {
				$row['transactType'] = 2;
				array_push($failedTrades, $row);
			}
		} else {
			array_push($register, $row);
		}
	}

	query('DELETE FROM trades');

	//add the failed trades back into the database
	foreach (array_merge($register, $failedTrades) as $row) {
		query('INSERT INTO trades(trader, stock, price, amt, partner, transactType) VALUES(?, ?, ?, ?, ?, ?)', 'ssiisi', 
			$row['trader'],
			$row['stock'],
			$row['price'],
			$row['amt'],
			$row['partner'],
			$row['transactType']
		);
	}
}

//TODO refactor
function collectQuotas() {
	$allUsers = query('SELECT * FROM users');
	$companies = ['apple', 'nestle', 'walmart'];
	$quotas = ['aprice', 'nprice', 'wprice'];

	while ($user = mysqli_fetch_assoc($allUsers)) {
		if ($user['quotatypes'] === NULL) {
			continue;
		}

		$quotaTypes = str_split($user['quotatypes']);

		for ($x = 0; $x < 3; $x++) {
			if ($quotaTypes[$x] === '1') {
				if ($user[$companies[$x]] > 0) {
					query('UPDATE users SET ' . $companies[$x] . ' = ' . $companies[$x] . ' - 1 WHERE username = ?', 's', $user['username']);
				} else {
					query('UPDATE users SET balance = balance - ? WHERE username = ?', 'is', intval($user[$quotas[$x]] * 1.1), $user['username']);
				}
			} else {
				query('UPDATE users SET balance = balance - ? WHERE username = ?', 'is', $user[$quotas[$x]], $user['username']);
			}
		}

		query('UPDATE users SET quotatypes = NULL');
	}
}

function endRound() {
	query('UPDATE status SET canTrade = 0');

	matchTrades();
}

//get $numRand unique random numbers between $min and $max (inclusive)
//returns an array
function getRandNums($numRand, $min, $max, $randNums = []) {
	if (sizeof($randNums) < $numRand) {
		array_push($randNums, rand($min, $max));
		$randNums = array_unique($randNums);
		return getRandNums($numRand, $min, $max, $randNums);
	} else {
		return $randNums;
	}
}

function getQuota($round, $buying) {
	//add 4 to make the starting number more reasonable (11 instead of 1)
	$midPrice = intval(($round + 4) ** 1.5);

	if ($buying) {
		$quotaLims = [1.05, 1.25];
	} else {
		$quotaLims = [0.75, 0.95];
	}

	return rand($midPrice * $quotaLims[0], $midPrice * $quotaLims[1]);
}

//TODO refactor a lot
function orderAssign() {
	$round = mysqli_fetch_array(query('SELECT round FROM status'), MYSQLI_NUM)[0] + 1;

	$allUsers = query('SELECT * FROM users ORDER BY RAND()');
	$numUsers = mysqli_num_rows($allUsers);

	// FIGURE OUT QHO THE BUYERS WILL BE FOR EACH STOCK

	$buyers = [];
	//putting ceil on this means that there will be a buyer surplus if there is an odd number of people
	for ($x = 0; $x < 3; $x++) {
		array_push($buyers, getRandNums(ceil($numUsers / 2), 0, $numUsers - 1));
	}

	//ASSIGN EVERYONE THEIR QUOTA

	$x = 0;
	while ($user = mysqli_fetch_assoc($allUsers)) {
		//this means that if there are an odd number of people there will be one more seller than buyer
		
		$type = [];
		$quotas = [];
		for ($y = 0; $y < 3; $y++) {
			if (in_array($x, $buyers[$y])) {
				array_push($type, 1);
				$newQuota = getQuota($round, 1);
				array_push($quotas, $newQuota);

				query('UPDATE users SET balance = balance + ? WHERE username = ?', 'is', $newQuota, $user['username']);
			} else {
				array_push($type, 0);
				$newQuota = getQuota($round, 0);
				array_push($quotas, $newQuota);

				$currCompany = ['apple', 'nestle', 'walmart'][$y];
				query('UPDATE users SET ' . $currCompany . ' = ' . $currCompany . ' + 1 WHERE username = ?', 's', $user['username']);
			}
		}

		query('UPDATE users SET aprice = ?, nprice = ?, wprice = ?, quotatypes = ? WHERE username = ?', 'iiiss', 
			$quotas[0],
			$quotas[1],
			$quotas[2],
			strval(implode($type)), 
			$user['username']
		);
		$x++;
	}
}

function startRound() {
	//delete any remaining failed trades and quotas
	query('DELETE FROM trades');
	query("UPDATE users SET aprice = 0, nprice = 0, wprice = 0, quotatypes = NULL"); //TODO is this necessary?

	orderAssign();
	
	query('UPDATE status SET canTrade = 1, round = round + 1');
}

function startGame() {
	startRound();
}

function endGame() {
	query('UPDATE status SET canTrade = 0, round = 0');
	query('UPDATE `users` SET `balance`=100,`apple`=0,`nestle`=0,`walmart`=0,`aprice`=0,`nprice`=0,`wprice`=0,`quotatypes`=NULL WHERE 1');
	query('DELETE FROM trades');
}

?>