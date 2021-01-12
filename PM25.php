<?php
	$mysqli = new mysqli('localhost', 'user1', 'password', 'airquality') or die($mysqli->error);
	$PM25_sensor = array();
	$PM25_London = array();
	$x_axis_labels = array();
	$count = 1;

	$Initial_query = "SELECT * FROM Air_quality_data;";
	$query_answer = mysqli_query($mysqli, $Initial_query);
	$Total_rows_query = mysqli_num_rows($query_answer);
	$rows_minus_10 = $Total_rows_query - 10;

	$sql = "SELECT * FROM Air_quality_data
		LIMIT $rows_minus_10,10";
	$result = mysqli_query($mysqli, $sql);
	$resultCheck = mysqli_num_rows($result);
	if ($resultCheck > 0) {
		while ($row['PM2.5 sensor'] = mysqli_fetch_assoc($result)) {
			foreach ($row as $sensor_25_data) {
				array_push($PM25_sensor, $sensor_25_data['PM2.5 sensor']);
				array_push($PM25_London, $sensor_25_data['PM2.5 London']);
				array_push($x_axis_labels, $count);
				++$count;
			}
		}
		while ($row['PM2.5 London'] = mysqli_fetch_assoc($result)) {
			foreach($row as $London_25_data) {
				array_push($PM25_London, $London_25_data['PM2.5 London']);
			}
		}
	}

	$array_all = array();
	$array_all_London = array();
	$array_per_day = array();
	$array_per_day_London = array();
	$array_average_per_day = array();
	$array_average_per_day_London = array();
	$x_axis_average_per_day = array();

	$sqlall = "SELECT * FROM Air_quality_data;";
	$results_all = mysqli_query($mysqli, $sqlall);
	$resultCheck_all = mysqli_num_rows($results_all);
	$per_day_count = 1;

	if ($resultCheck_all > 0) {
		while ($row2['PM2.5 sensor'] = mysqli_fetch_assoc($results_all)) {
			foreach($row2 as $sensor_25_data2) {
				array_push($array_all, $sensor_25_data2['PM2.5 sensor']);
				array_push($array_all_London, $sensor_25_data2['PM2.5 London']);
			}
		}
		$Total_days = intdiv($resultCheck_all,24);
		foreach(range(0,$Total_days-1) as $Day) {
			array_push($x_axis_average_per_day, $Day);
			if ($Day === 0) {
				foreach(range(0,23) as $Day_zero) {
					array_push($array_per_day, $array_all[$Day_zero]);
					array_push($array_per_day_London, $array_all_London[$Day_zero]);
				}
				$temp_avg = array_sum($array_per_day) / 24;
				$temp_avg_London = array_sum($array_per_day_London) / 24;
				array_push($array_average_per_day, $temp_avg);
				array_push($array_average_per_day_London, $temp_avg_London);
				$array_per_day = array();
				$array_per_day_London = array();
			}
			else {
				foreach(range($Day*24,((($Day+1)*24)-1)) as $Hour_Value) {
					if ($Hour_Value <= sizeof($array_all)-1) {
						array_push($array_per_day, $array_all[$Hour_Value]);
						array_push($array_per_day_London, $array_all_London[$Hour_Value]);
					}
				}
				$temp_avg = array_sum($array_per_day) / count($array_per_day);
				$temp_avg_London = array_sum($array_per_day_London) / count($array_per_day_London);
				array_push($array_average_per_day, $temp_avg);
				array_push($array_average_per_day_London, $temp_avg_London);
				$array_per_day = array();
				$array_per_day_London = array();
			}
		}
	}
include 'correlation.php';
$correlation = Correlation($array_average_per_day, $array_average_per_day_London);
//print_r($correlation);
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
		<section class="Divide_line"></section>
        </section>

	<section class="Graph_section_1">
		<div class="divider3"></div>
		<div class="left_container">
			<canvas id="lineChart" width="520px" height="400px"></canvas>
		</div>
		<div class="right_container">
			<canvas id="lineChart2" width="520px" height="400px"></canvas>
		</div>
	</section>


	<script>
		const CHART = document.getElementById("lineChart").getContext('2d');
		const lineChart = new Chart(CHART, {
			type: 'line',
			data: {
				labels: ["-10Hrs","-9Hrs","-8Hrs","-7Hrs","-6Hrs","-5Hr","-4Hrs","-3Hrs","-2Hrs","-1Hr"],
				datasets: [{
					label: 'PM2.5 quantity at Home',
					fill: true,
					data: <?php echo json_encode($PM25_sensor) ?>,
					backgroundColor: 'rgba(111, 34, 50, 1)',
					hoverBorderColor: "rgba(111, 34, 50, 1)"
				},
				{
					label: 'PM2.5 quantity in London',
					data: <?php echo json_encode($PM25_London) ?>,
					backgroundColor: 'rgba(149, 7, 64, 1)',
					hoverBorderColor: "rgba(149, 7, 64, 1)"
				}]
			},
			options: {
				legend: {
					display: true,
					labels: {
						fontColor: 'White',
					}
				},
				scales: {
					yAxes:[{
						stacked: true,
						scaleLabel: {
							fontColor: "white",
							display: true,
							labelString: 'PM2.5 Quantity'
						},
						ticks: {
							fontColor: "White",
						},
						gridLines: {
                                               	        display: true ,
                                                       	color: "rgba(255, 255, 255, 0.08)"
                                              		}
					}],
					xAxes:[{
						scaleLabel: {
							fontColor: "White",
							display: true,
							labelString: 'Last 10 Hours'
						},
						ticks: {
                                       	                fontColor: "White",
                                       	        },
						gridLines: {
                                               	        display: true ,
                                                       	color: "rgba(255, 255, 255, 0.08)"
						}
					}]
				},
				responsive: false,
				maintainAspectRatio: false
			}
		});
	</script>

	<script>
		const CHART2 = document.getElementById("lineChart2").getContext('2d');
		const lineChart2 = new Chart(CHART2, {
			type: 'line',
			data: {
				labels: <?php echo json_encode($x_axis_average_per_day) ?>,
				datasets: [{
					label: 'PM2.5 Average at home per day',
					data: <?php echo json_encode($array_average_per_day); ?>,
					backgroundColor: 'rgba(111, 34, 50, 1)'
				},
				{
					label: 'PM2.5 Average in London per day',
					data: <?php echo json_encode($array_average_per_day_London) ?>,
					backgroundColor: 'rgba(149, 7, 64, 1)'
				}]
			},
			options: {
				legend: {
					display: true,
					labels: {
                                                fontColor: 'White',
                                        }
				},
				scales: {
					yAxes:[{
						stacked: true,
						scaleLabel: {
							display: true,
							labelString: 'PM2.5 Average per Day',
							fontColor: 'white'
						},
						ticks: {
                                                        fontColor: "White",
                                                },
						gridLines: {
							display: true ,
							color: "rgba(255, 255, 255, 0.08)"
						}
					}],
					xAxes: [{
						scaleLabel: {
							display: true,
							labelString: 'Day Number',
							fontColor: 'White'
						},
						ticks: {
                                                        fontColor: "White",
                                                },
						gridLines: {
                                                        display: true ,
                                                        color: 'rgba(255, 255, 255, 0.08)'
                                                }
					}]
				},
				labels: {
                                        fontColour: 'white'
                                },
				responsive: false,
				maintainAspectRatio: false
			}
		});
	</script>
</body>

</html>

