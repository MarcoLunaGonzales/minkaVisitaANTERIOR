<?php
header("Content-type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=archivo.xls"); 	
$global_agencia=$rpt_territorio;
//$global_linea=$linea_rpt;
require("conexion.inc");
require("estilos_reportes_central_xls.inc");
	$sql_cabecera_gestion=mysql_query("select nombre_gestion from gestiones where codigo_gestion='$gestion_rpt' and codigo_linea='$global_linea'");
	$datos_cab_gestion=mysql_fetch_array($sql_cabecera_gestion);
	$nombre_cab_gestion=$datos_cab_gestion[0];
	$sql_cab="select cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";$resp1=mysql_query($sql_cab);
	$dato=mysql_fetch_array($resp1);
	$nombre_territorio=$dato[1];	
	echo "<center>Reporte Medicos listado general<br>";
	if($global_agencia!=0)
	{	echo "Territorio: $nombre_territorio<br>";
	}
	else
	{	echo "Todos los territorios<br>";
	}
//ORDEN ALFABETICO
if($rpt_vista==0)
{	if($rpt_parametro==0){$orden="asc";}
	else{$orden="desc";}
	if($global_agencia==0)
	{	$sql="select distinct cod_med, ap_pat_med, ap_mat_med, nom_med, fecha_nac_med, telf_med, telf_celular_med,
		email_med, hobbie_med, estado_civil_med, nombre_secre_med, perfil_psicografico_med, cod_ciudad
	 from medicos order by ap_pat_med $orden, ap_mat_med asc, nom_med asc";
	}
	else
	{	$sql="select distinct cod_med, ap_pat_med, ap_mat_med, nom_med, fecha_nac_med, telf_med, telf_celular_med,
		email_med, hobbie_med, estado_civil_med, nombre_secre_med, perfil_psicografico_med, cod_ciudad
	 from medicos
	 where cod_ciudad='$global_agencia' order by ap_pat_med $orden, ap_mat_med asc, nom_med asc";
	}
	$resp=mysql_query($sql);
}
//especialidad
if($rpt_vista==1)
{	if($global_agencia==0)
	{	$sql="select distinct m.cod_med,m.ap_pat_med,m.ap_mat_med,m.nom_med,m.fecha_nac_med,m.telf_med,m.telf_celular_med,
		m.email_med,m.hobbie_med,m.estado_civil_med,m.nombre_secre_med,m.perfil_psicografico_med, cod_ciudad
		 from medicos m, especialidades_medicos e
		 where m.cod_med=e.cod_med and e.cod_especialidad='$rpt_parametro' order by m.ap_pat_med";
	}
	else
	{	$sql="select distinct m.cod_med,m.ap_pat_med,m.ap_mat_med,m.nom_med,m.fecha_nac_med,m.telf_med,m.telf_celular_med,
		m.email_med,m.hobbie_med,m.estado_civil_med,m.nombre_secre_med,m.perfil_psicografico_med, cod_ciudad
		 from medicos m, especialidades_medicos e
		 where m.cod_ciudad='$global_agencia' and m.cod_med=e.cod_med and e.cod_especialidad='$rpt_parametro' order by m.ap_pat_med";
	}
	$resp=mysql_query($sql);
	$sql_cab=mysql_query("select desc_especialidad from especialidades where cod_especialidad='$rpt_parametro'");
	$dat_cab=mysql_fetch_array($sql_cab);
	$especialidad=$dat_cab[0];
	$filas=mysql_num_rows($resp);
	if($filas!=0)
	{
		echo "<center>Especialidad $especialidad</center><br>";
	}
}
//ruc
if($rpt_vista==2)
{	if($rpt_parametro==0){$orden="asc";}
	else{$orden="desc";}
	if($global_agencia==0)
	{	$sql="select distinct cod_med,ap_pat_med,ap_mat_med,nom_med,fecha_nac_med,telf_med,telf_celular_med,
		email_med,hobbie_med,estado_civil_med,nombre_secre_med,perfil_psicografico_med, cod_ciudad
		 from medicos
		 order by cod_med $orden";
	}
	else
	{	$sql="select distinct cod_med,ap_pat_med,ap_mat_med,nom_med,fecha_nac_med,telf_med,telf_celular_med,
		email_med,hobbie_med,estado_civil_med,nombre_secre_med,perfil_psicografico_med, cod_ciudad
		 from medicos
		 where cod_ciudad='$global_agencia' order by cod_med $orden";
	}
	$resp=mysql_query($sql);
	echo "<center>Medicos ordenados por Codigo</center><br>";
}
//sacamos los medicos y los listamos
$indice_tabla=1;
	$filas=mysql_num_rows($resp);
	if($filas!=0)
	{
		if($rpt_formato==0)
		{	echo "<center><table border='1' class='textomini' cellspacing='0' width='100%'>";
			if($global_agencia==0)
			{	echo "<tr><th>&nbsp;</th><th>Codigo</th><th>Nombre</th><th>Especialidades</th><th>Asignado a</th><th>Territorio</th></tr>";
			}
			else
			{	echo "<tr><th>&nbsp;</th><th>Codigo</th><th>Nombre</th><th>Especialidades</th><th>Asignado a</th></tr>";
			}
		}
		else
		{	echo "<center><table border='1' class='textomini' cellspacing='0'>";
			if($global_agencia==0)
			{	echo "<tr><th>&nbsp;</th><th>Codigo</th><th>Nombre</th><th>Nacimiento</th><th>Especialidades</th><th>Asignado a</th><th>Direcciones</th><th>Tel�fonos</th><th>C�lular</th><th>Correo Electr�nico</th><th>Secretaria</th><th>Perfil Psicografico</th><th>Estado Civil</th><th>Hobbie</th><th>Territorio</th></tr>";
			}
			else
			{	echo "<tr><th>&nbsp;</th><th>Codigo</th><th>Nombre</th><th>Nacimiento</th><th>Especialidades</th><th>Asignado a</th><th>Direcciones</th><th>Tel�fonos</th><th>C�lular</th><th>Correo Electr�nico</th><th>Secretaria</th><th>Perfil Psicografico</th><th>Estado Civil</th><th>Hobbie</th></tr>";
			}
		}
	}
	while($dat=mysql_fetch_array($resp))
	{
		$cod=$dat[0];
		$pat=$dat[1];
		$mat=$dat[2];
		$nom=$dat[3];
		$fecha_nac=$dat[4];
		$telf=$dat[5];
		$cel=$dat[6];
		$email=$dat[7];
		$hobbie=$dat[8];
		$est_civil=$dat[9];
		$secre=$dat[10];
		$perfil=$dat[11];
		$cod_ciudad=$dat[12];
		$sql_ciudad="select cod_ciudad,descripcion from ciudades where cod_ciudad='$cod_ciudad'";
		$resp1=mysql_query($sql_ciudad);
		$dato=mysql_fetch_array($resp1);
		$nombre_ciudad=$dato[1];
		$nombre_completo="$pat $mat $nom";
		$sql1="select direccion, descripcion from direcciones_medicos where cod_med=$cod order by descripcion asc";
		$resp1=mysql_query($sql1);
		$direccion_medico="<table border=0 class='textosupermini' width='100%'>";
			while($dat1=mysql_fetch_array($resp1))
			{
				$dir=$dat1[0];
				$desc_dir=$dat1[1];
				$direccion_medico="$direccion_medico<tr><th>$desc_dir:</th></tr><tr><td align='left'>$dir</td></tr>";
			}
			$direccion_medico="$direccion_medico</table>";
		$sql2="select cod_especialidad, descripcion from especialidades_medicos where cod_med=$cod order by descripcion asc";
		$resp2=mysql_query($sql2);
		$especialidad="<table border=0 class='textomini' width='50%'>";
		while($dat2=mysql_fetch_array($resp2))
		{
			$espe=$dat2[0];
			$desc_espe=$dat2[1];
			$especialidad="$especialidad<tr><td align='left'>$espe</td></tr>";
		}
		$especialidad="$especialidad</table>";
		$lineas_medico="select distinct(l.nombre_linea) from lineas l, categorias_lineas c where c.cod_med='$cod' and l.codigo_linea=c.codigo_linea";
		$resp_lineas=mysql_query($lineas_medico);
		$cad_lineas="<table class='textomini' border='0'>";
		while($dat_lineas=mysql_fetch_array($resp_lineas))
		{	$nombre_linea=$dat_lineas[0];	
			$cad_lineas="$cad_lineas <tr><td>$nombre_linea</td></tr>";
		}
		$cad_lineas="$cad_lineas</table>";
		if($rpt_formato==0)
		{	echo "<tr><td align='center'>$indice_tabla</td><td align='center'>$cod</td><td class='texto'>$nombre_completo</th><td align='center'>&nbsp;$especialidad</td><td>$cad_lineas</td>";
			if($global_agencia==0)
			{	echo "<td>$nombre_ciudad</td>";
			}
			echo "</tr>";
		}
		else
		{	echo "<tr><td align='center'>$indice_tabla</td><td align='center'>$cod</td><td class='texto'>$nombre_completo</th><td align='center'>&nbsp;$fecha_nac</th><td align='center'>&nbsp;$especialidad</th><td>$cad_lineas</td><td align='center'>&nbsp;$direccion_medico</th><td align='center'>&nbsp;$telf</th><td align='center'>&nbsp;$cel</th><td align='center'>&nbsp;$email</th><td align='center'>&nbsp;$secre</th><td align='center'>&nbsp;$perfil</th><td align='center'>&nbsp;$est_civil</th><td align='center'>&nbsp;$hobbie</th>";
			if($global_agencia==0)
			{	echo "<td>$nombre_ciudad</td>";
			}
			echo "</tr>";
		}
		$indice_tabla++;
	}
	echo "</table></center><br>";
	
echo "<br><center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";
	
?>