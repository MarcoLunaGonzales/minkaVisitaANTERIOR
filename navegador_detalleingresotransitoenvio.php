<?php

	require("conexion.inc");
	require('estilos_almacenes_sincab.inc');
	$sql="select s.cod_salida_almacenes, s.fecha, ts.nombre_tiposalida, s.observaciones, s.grupo_salida
	FROM salida_almacenes s, tipos_salida ts
	where s.cod_tiposalida=ts.cod_tiposalida and s.cod_almacen='$almacen_origen' and s.cod_salida_almacenes='$codigo_salida' and s.grupo_salida=1";
	$resp=mysql_query($sql);

	echo "<h1>Detalle de Despacho</h1>";
	
	echo "<center><table class='texto'>";
	echo "<tr><th>Fecha</th><th>Tipo de Ingreso</th><th>Observaciones</th></tr>";
	$dat=mysql_fetch_array($resp);
	$codigo=$dat[0];
	$fecha_salida=$dat[1];
	$fecha_salida_mostrar="$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
	$nombre_tiposalida=$dat[2];
	$obs_salida=$dat[3];
	$grupo_salida=$dat[4];
	echo "<tr><td align='center'>$fecha_salida_mostrar</td><td>$nombre_tiposalida</td><td>&nbsp;$obs_salida</td></tr>";
	echo "</table><br>";
	
	echo "<table class='texto'>";
	echo "<form method='post' action=''>";
	$sql_detalle="select s.despacho_fecha, s.despacho_nroguia, t.nombre_tipotransporte, s.despacho_nrocajas, s.despacho_obs 
	from salida_almacenes s, tipos_transporte t 
	where s.cod_salida_almacenes='$codigo_salida' and t.cod_tipotransporte=s.despacho_codtipotransporte";
	$resp_detalle=mysql_query($sql_detalle);
	$indice=1;
	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{	$fecha_despacho=$dat_detalle[0];
		$numero_guia=$dat_detalle[1];
		$tipo_transporte=$dat_detalle[2];
		$nro_cajas=$dat_detalle[3];
		$obs=$dat_detalle[4];
		echo "<tr><th align='left'>Fecha Despacho</th><td>$fecha_despacho</td></tr>";
		echo "<tr><th align='left'>Nro. Guia</th><td>$numero_guia</td></tr>";
		echo "<tr><th align='left'>Tipo Transporte</th><td>$tipo_transporte</td></tr>";
		echo "<tr><th align='left'>Nro. Cajas</th><td>$nro_cajas</td></tr>";
		echo "<tr><th align='left'>Observaciones</th><td>$obs</td></tr>";
	}
	echo "</table></center></br>";
	require("imprimirInc.php");
	
?>