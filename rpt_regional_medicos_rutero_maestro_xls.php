<?php
header("Content-type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=archivo.xls"); 	
require("conexion.inc");
require("estilos_reportes_regional_xls.inc");
$sql_cab_rutero="select nombre_rutero from rutero_maestro_cab where cod_rutero='$rutero_maestro'";
	$resp_cab_rutero=mysql_query($sql_cab_rutero);
	$dat_cab_rutero=mysql_fetch_array($resp_cab_rutero);
	$nombre_rutero_maestro=$dat_cab_rutero[0];
	$sql_visitador="select paterno, materno, nombres
	from funcionarios where codigo_funcionario='$visitador'";
	$resp_visitador=mysql_query($sql_visitador);
	$dat_visitador=mysql_fetch_array($resp_visitador);
	$nombre_visitador="$dat_visitador[0] $dat_visitador[1] $dat_visitador[2]";
	$sql_especialidad="select desc_especialidad from especialidades where cod_especialidad='$parametro'";
	$resp_especialidad=mysql_query($sql_especialidad);
	$dat_espe=mysql_fetch_array($resp_especialidad);
	$nombre_especialidad=$dat_espe[0];
if($formato==0)
{	echo "<center>Visitador: $nombre_visitador<br>Medicos del rutero maestro: $nombre_rutero_maestro</center><br>";
	echo "<table class='texto' border='1' cellspacing='0' align='center'width='90%'><tr><th>Especialidad</th><th>Categoria A</th><th>Categoria B</th><th>Total Medicos</th><th>Contactos A</th><th>Contactos B</th><th>Total Contactos</th></tr>";
	$sql_especialidad="select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
	$resp_especialidad=mysql_query($sql_especialidad);
	$numero_total_medicos=0;
	$numero_total_contactos=0;
	while($dat_espe=mysql_fetch_array($resp_especialidad))
	{	$cod_especialidad=$dat_espe[0];
		$desc_especialidad=$dat_espe[1];
		$sql_medicos="select DISTINCT (rmd.cod_med), m.ap_pat_med, m.ap_mat_med, m.nom_med, rmd.categoria_med
		from rutero_maestro_cab rmc, rutero_maestro rm, rutero_maestro_detalle rmd, medicos m
		where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and m.cod_med=rmd.cod_med and rmc.cod_rutero='$rutero_maestro' and rmc.codigo_linea='$global_linea' and rmc.cod_visitador='$visitador' and rmd.cod_visitador='$visitador' and rmd.cod_especialidad='$cod_especialidad' order by m.ap_pat_med, m.ap_mat_med, m.nom_med";
		$resp_medicos=mysql_query($sql_medicos);
		$num_filas=mysql_num_rows($resp_medicos);
		$numero_total_medicos=$numero_total_medicos+$num_filas;
		$numero_a=0;
		$numero_b=0;
		$cant_contactos_a=0;
		$cant_contactos_b=0;
		while($dat_medicos=mysql_fetch_array($resp_medicos))
		{	$cod_med=$dat_medicos[0];
			$sql_cant_contactos="select rmd.cod_med
			from rutero_maestro_cab rmc, rutero_maestro rm, rutero_maestro_detalle rmd, medicos m
			where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and m.cod_med=rmd.cod_med and rmc.cod_rutero='$rutero_maestro' and rmc.codigo_linea='$global_linea' and rmc.cod_visitador='$visitador' and rmd.cod_especialidad='$cod_especialidad' and rmd.cod_med='$cod_med'";
			$resp_cant_contactos=mysql_query($sql_cant_contactos);
			$num_contactos=mysql_num_rows($resp_cant_contactos);
			//echo "$cod_med $num_contactos<br>";
			$categoria_med=$dat_medicos[4];
			if($categoria_med=="A")
			{	$numero_a++;
				$cant_contactos_a=$cant_contactos_a+$num_contactos;
			}
			if($categoria_med=="B")
			{	$numero_b++;
				$cant_contactos_b=$cant_contactos_b+$num_contactos;			
			}
		}
		if($num_filas!=0)
		{	$total_medicos_espe=$numero_a+$numero_b;
			$total_contactos=$cant_contactos_a+$cant_contactos_b;
			$numero_total_contactos=$numero_total_contactos+$total_contactos;
			echo "<tr><td>$desc_especialidad</td><td align='center'>$numero_a</td><td align='center'>$numero_b</td><td align='center'>$total_medicos_espe</td><td align='center'>$cant_contactos_a</td><td align='center'>$cant_contactos_b</td><td align='center'>$total_contactos</td></tr>";
		}
	}
	echo "<tr><th colspan='3' align='left'>Medicos en rutero maestro:</th><th>$numero_total_medicos</th><th colspan='2' align='left'>Contactos en rutero maestro:</th><th>$numero_total_contactos</th></tr>";
	echo "</table>";
}
else
{	echo "<center>Visitador: $nombre_visitador<br>Especialidad: $nombre_especialidad<br>Medicos del rutero maestro: $nombre_rutero_maestro</center><br>";
	$sql_medicos="select DISTINCT (rmd.cod_med), m.ap_pat_med, m.ap_mat_med, m.nom_med, rmd.categoria_med
	from rutero_maestro_cab rmc, rutero_maestro rm, rutero_maestro_detalle rmd, medicos m
	where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and m.cod_med=rmd.cod_med and rmc.cod_rutero='$rutero_maestro' and rmc.cod_visitador='$visitador' and rmd.cod_visitador='$visitador' and rmd.cod_especialidad='$parametro' order by rmd.categoria_med, m.ap_pat_med, m.ap_mat_med, m.nom_med";
	$resp_medicos=mysql_query($sql_medicos);
	echo "<center><table border='1' class='textomini' cellspacing='0' width='60%'>";
	echo "<tr><th>&nbsp;</th><th>Codigo</th><th>Nombre</th><th>Categoria</th><th>Contactos</th></tr>";
	$indice_tabla=1;
	$numero_a=0;
	$numero_b=0;
	$numero_total_contactos=0;
	$cant_contactos_a=0;
	$cant_contactos_b=0;	
	while($dat_medicos=mysql_fetch_array($resp_medicos))
	{	$codigo_medico=$dat_medicos[0];
		$nombre_medico="$dat_medicos[1] $dat_medicos[2] $dat_medicos[3]";
		$sql_cant_contactos="select rmd.cod_med
			from rutero_maestro_cab rmc, rutero_maestro rm, rutero_maestro_detalle rmd, medicos m
			where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and m.cod_med=rmd.cod_med and rmc.cod_rutero='$rutero_maestro' and rmc.codigo_linea='$global_linea' and rmc.cod_visitador='$visitador' and rmd.cod_especialidad='$parametro' and rmd.cod_med='$codigo_medico'";
		$resp_cant_contactos=mysql_query($sql_cant_contactos);
		$num_contactos=mysql_num_rows($resp_cant_contactos);
		$categoria_med=$dat_medicos[4];
		if($categoria_med=="A")
		{	$cant_contactos_a=$cant_contactos_a+$num_contactos;
			$numero_a++;
		}
		if($categoria_med=="B")
		{	$cant_contactos_b=$cant_contactos_b+$num_contactos;
			$numero_b++;
		}
		echo "<tr><td>$indice_tabla</td><td align='center'>$codigo_medico</td><td>$nombre_medico</td><td align='center'>$categoria_med</th><td align='center'>$num_contactos</td></tr>";
		$indice_tabla++;
	}
	$total_medicos_espe=$numero_a+$numero_b;
	$numero_total_contactos=$cant_contactos_a+$cant_contactos_b;
	echo "</table>";
	echo "<table border='1' cellspacing='0' width='60%' class='texto'>";
	echo "<tr><th align='left'>Medicos Categoria A</th><td align='center'>$numero_a</td><th align='left'>Contactos Categoria A</th><td align='center'>$cant_contactos_a</td></tr>";
	echo "<tr><th align='left'>Medicos Categoria B</th><td align='center'>$numero_b</td><th align='left'>Contactos Categoria B</th><td align='center'>$cant_contactos_b</td></tr>";
	echo "<tr><th align='left'>Cantidad total de Medicos</th><td align='center'>$total_medicos_espe</td><th align='left'>Cantidad total de contactos</th><td align='center'>$numero_total_contactos</td></tr>";
}
?>