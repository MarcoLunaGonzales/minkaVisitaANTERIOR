<?php
require("conexion.inc");
if($global_usuario==1032)
{	require("estilos_gerencia.inc");	
}
else
{	require("estilos_inicio_adm.inc");
}	

$vector=explode(",",$vector_campos);
$n=sizeof($vector);
for($i=1;$i<=$n;$i++)
{	list($cod_territorio,$cod_producto,$cant_a_distribuir)=explode("|",$vector[$i]);
	
	
//	echo "$cod_territorio $cod_producto $cant_a_distribuir<br>";
	
	
//	$sql_visitador="select f.codigo_funcionario from funcionarios f, funcionarios_lineas fl
//	where f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea='$global_linea' and f.estado=1 
//	and f.cod_ciudad='$cod_territorio' and f.cod_cargo='1011' order by f.paterno, f.materno";	

	$sql_visitador="select distinct(cod_visitador) from distribucion_productos_visitadores 
		where territorio='$cod_territorio' and cod_ciclo='$global_ciclo_distribucion' 
		and codigo_gestion='$global_gestion_distribucion' and codigo_linea='$global_linea'";
	
	
//	echo "SQL VISITADOR: $sql_visitador<br>";
//	echo $sql_visitador;
	
	$resp_visitador=mysql_query($sql_visitador);
	$cant_aux=$cant_a_distribuir;
	while($dat_visitadores=mysql_fetch_array($resp_visitador))
	{	$cod_visitador=$dat_visitadores[0];
		$sql_cantidades="select cantidad_planificada, cantidad_distribuida from distribucion_productos_visitadores
		where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' and territorio='$cod_territorio' and 
		codigo_linea='$global_linea' and codigo_producto='$cod_producto' and cod_visitador='$cod_visitador'";
//		echo "SQL $sql_cantidades<br>";
//		echo $sql_cantidades;
		
		$resp_cantidades=mysql_query($sql_cantidades);
		$dat_cantidades=mysql_fetch_array($resp_cantidades);
		$cant_plan=$dat_cantidades[0];
		$cant_dist=$dat_cantidades[1];
		
//		echo "cantidad_plani $cant_plan cantidad_dist $cant_dist<br>";
		
		$maximo_a_insertar=$cant_plan-$cant_dist;
		if($cant_aux>$maximo_a_insertar)
		{	$cant_a_insertar=$maximo_a_insertar+$cant_dist;
			echo "entroOpcion 1<br>";
			$sql_cantidades="update distribucion_productos_visitadores set cantidad_distribuida='$cant_a_insertar'
			where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' and territorio='$cod_territorio' and 
			codigo_linea='$global_linea' and codigo_producto='$cod_producto' and cod_visitador='$cod_visitador'";
			
//			echo $sql_cantidades."<br>";
			echo $sql_cantidades.";";
			
			$resp_cantidades=mysql_query($sql_cantidades);
			$cant_aux=$cant_aux-$maximo_a_insertar;
		}
		else
		{	$cant_a_insertar=$cant_aux+$cant_dist;
			
			echo "entroOpcion 2<br>";
			$sql_cantidades="update distribucion_productos_visitadores set cantidad_distribuida='$cant_a_insertar'
			where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' and territorio='$cod_territorio' and 
			codigo_linea='$global_linea' and codigo_producto='$cod_producto' and cod_visitador='$cod_visitador'";

			echo $sql_cantidades.";";
			
			$resp_cantidades=mysql_query($sql_cantidades);
			$cant_aux=0;	
		}
	}
}
/*echo "<script language='JavaScript'>
		alert('Los datos se guardaron correctamente.');
		location.href='registro_distribucion_lineasterritorios1.php?global_linea=$global_linea';
</script>";*/
?>