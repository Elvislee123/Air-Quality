<?php
	$mysqli = new mysqli('localhost', 'user1', 'password', 'airquality') or die($mysqli->error);
	$PM25_current = 0;
	$PM25_current_London = 0;
	$PM10_current = 0;
        $PM10_current_London = 0;

	$Initial_query = "SELECT * FROM Air_quality_data;";
        $query_answer = mysqli_query($mysqli, $Initial_query);
        $Total_rows_query = mysqli_num_rows($query_answer);
        $rows_minus_1 = $Total_rows_query - 1;

        $sql = "SELECT * FROM Air_quality_data
                LIMIT $rows_minus_1,1";

	$result = mysqli_query($mysqli, $sql);
	$resultCheck = mysqli_num_rows($result);

	if ($resultCheck > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$PM25_current = $row['PM2.5 sensor'];
			$PM25_current_London = $row['PM2.5 London'];
			$PM10_current = $row['PM10 sensor'];
			$PM10_current_London = $row['PM10 London'];
		}
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" href="Style.css">
	<meta charset="UTF-8">
	<script type ="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.min.js"></script>
</head>

<body>
	<header>
		<div class="container_nav">
			<span class="divider"></span>
			<span class="Title">Air Quality Analysis</span>
			<nav>
				<ul>
					<li><a href="index.php">Home</a></li>
					<li><a href="PM25.php">PM2.5</a></li>
					<li><a href="PM10.php">PM10</a></li>
					<li><a href="Flights.php">Flight Plans!</a></li>
				</ul>
			</nav>
		</div>

	</header>

	<section class="Dashboard">
		<div class="divider2"></div>
	</section>

        <div class="PM_reading">
		<div class="divider3"></div>
	        <ul>
			<li>
				<strong><center>Current PM 2.5 value at Home</center></strong>
				<p></p>
				<scan><b><center><?php echo json_encode(floatval($PM25_current)) ?></center></b></scan>
			</li>
			<li>
				<strong><center>Current PM 2.5 value in London</center></strong>
                                <p></p>
				<scan><b><center><?php echo json_encode(floatval($PM25_current_London)) ?></center></b></scan>
			</li>
			<li>
				<strong><center>Current PM 10 value at Home</center></strong>
                                <p></p>
				<scan><b><center><?php echo json_encode(floatval($PM10_current)) ?></center></b></scan>
			</li>
			<li>
				<strong><center>Current PM 10 value in London</center></strong>
                                <p></p>
				<scan><b><center><?php echo json_encode(floatval($PM10_current_London)) ?></center></b></scan>
			</li>
		</ul>
	</div>

<body>

</html>

