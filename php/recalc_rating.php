<?php

// 	Перерасчёт рейтинга. Никуда не прикручен ещё, потому что уровни не реализованы.

	session_start();
	if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
		exit();
	}

	$con = mysqli_connect('localhost','tl','123123','colorray');
	if (!$con) {
		echo 'Could not connect: ' . mysqli_error($con);
		exit();
	}
	
	$u = $_SESSION['username'];
	$t = strval($_POST[timems]);
	$cl = strval($_POST[level]);
	
	$sql = "UPDATE users SET level$cl=$t WHERE userid=".$_SESSION['username'].";";
	$result = mysqli_query($con,$sql);
	
	mysqli_select_db($con,"colorray");
	$sql = "SELECT levels_num FROM info";
	$result = mysqli_query($con,$sql);
	$levels_num = mysqli_fetch_assoc($result);
	$l = $levels_num['levels_num'];
	
	$sql = "SELECT * FROM users WHERE username = '$u'";
	$result = mysqli_query($con,$sql);
	$row = mysqli_fetch_assoc($result);
	
	$rating = intval($row['rating']);
	
// 	$time = time() - strtotime(row['last_login']);
	for($i = 0; $i < $l; $i++) {
		$rating += 120000.0 / intval($row['level'.strval($i+1)]) + 1;
	}
	
	$sql = "UPDATE users SET rating=".strval($rating)." WHERE userid=$u;";
	$result = mysqli_query($con,$sql);
	
	mysqli_close($con);
?>