<?php
	$conexion = mysqli_connect("localhost", "root", "", "ppm");
	mysqli_set_charset($conexion, "utf8");

	$chipid = $_POST ['chipId'];
	$ppm = $_POST ['ppm'];

	if(!mysqli_query($conexion, "INSERT INTO data (id, chipId, date, ppm) VALUES (NULL, '$chipid', CURRENT_TIMESTAMP, '$ppm')")){
		printf("Errormessage: %s\n", mysqli_error($conexion));
	}

	mysqli_close($conexion);
?>