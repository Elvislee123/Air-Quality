<?php

	$command = escapeshellcmd("/usr/bin/python3 scrape.py");
	$output = shell_exec($command);
	echo $output;

	$mysqli = new mysqli('localhost', 'user1', 'password', 'airquality') or die($mysqli->error);

        $Initial_query = "SELECT * FROM Cleanest_Cities;";
        $query_answer = mysqli_query($mysqli, $Initial_query);
        $Total_rows_query = mysqli_num_rows($query_answer);
        $rows_minus_1 = $Total_rows_query - 1;

	$sql = "SELECT * FROM Cleanest_Cities
                LIMIT $rows_minus_1,1";
	$result = mysqli_query($mysqli, $sql);
        $resultCheck = mysqli_num_rows($result);
        if ($resultCheck > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
			$City = $row['Cleanest city'];
			$Link = $row['link'];
			$Time = $row['Time'];
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
				<strong><center>City with the Cleanest air <b>LIVE</b></center></strong>
				<p></p>
				<scan><b><center><?php echo json_encode($City) ?></center></b></scan>
			</li>
			<li>
				<strong><center>Data was found and last updated at:</center></strong>
                                <p></p>
				<scan><b><center><?php echo json_encode($Time) ?></center></b></scan>
			</li>
			<li>
				<strong><center>Click the link below to find Flights!</center><strong>
				<p></p>
				<b><center><a href=<?php echo json_encode($Link) ?>>Check Flights!</a></center></b>
			</li>
			<li><strong><center>Updates every 30 seconds! Just refresh the page</center></strong></li>
		</ul>
	</div>

<body>

</html>

