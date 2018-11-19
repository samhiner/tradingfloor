<?php
include('logic/misc_funcs.php');

$status = mysqli_fetch_assoc(query('SELECT * FROM status'));

echo $status['round'] . ',' . $status['canTrade'];

?>