<?php

	$mysqli = new mysqli('localhost', 'user1', 'password', 'airquality') or die($mysqli->error);
        $PM25_sensor = array();
	$PM25_London = array();
	$PM10_sensor = array();
        $PM10_London = array();

        $sql = "SELECT * FROM Air_quality_data";
        $result = mysqli_query($mysqli, $sql);
        $resultCheck = mysqli_num_rows($result);
        if ($resultCheck > 0) {
                while ($row['PM2.5 sensor'] = mysqli_fetch_assoc($result)) {
                        foreach ($row as $data) {
                                array_push($PM25_sensor, $data['PM2.5 sensor']);
                                array_push($PM25_London, $data['PM2.5 London']);
				array_push($PM10_sensor, $data['PM10 sensor']);
				array_push($PM10_London, $data['PM10 London']);
                        }
                }
        }

include 'correlation.php';
$correlation_25 = Correlation($PM25_sensor, $PM25_London);
$correlation_10 = Correlation($PM10_sensor, $PM10_London);
print_r("Correlation for PM2.5 readings");
print_r($correlation_25);
print_r("Correlation for PM10 readings");
print_r($correlation_10);

?>
