<?php
echo "<script language='Javascript'>
	function enviar_form(f)
	{	f.submit();
	}
	function validar(f)
	{
		var i,j,cantidad_material,nombre_tipo;
		variables=new Array(f.length-1);
		vector_material=new Array(30);
		vector_fechavenci=new Array(30);
		vector_cantidades=new Array(30);
		vector_tipomaterial=new Array(30);
		var indice,fecha, tipo_ingreso, observaciones;
		fecha=f.fecha.value;
		tipo_ingreso=f.tipo_ingreso.value;
		observaciones=f.observaciones.value;
		cantidad_material=f.cantidad_material.value;
		if(f.fecha.value=='')
		{	alert('El campo Fecha esta vacio.');
			f.fecha.focus();
			return(false);
		}
		for(i=3;i<=f.length-2;i++)
		{
			variables[i]=f.elements[i].value;
			if(f.elements[i].value=='')
			{
				alert('Algun elemento no tiene valor');
				return(false);
			}
		}
		indice=0;
		for(j=0;j<=f.length-1;j++)
		{
			if(f.elements[j].name.indexOf('materiales')!=-1)	
			{	vector_material[indice]=f.elements[j].value;
				indice++;	
			}
		}
		indice=0;
		for(j=0;j<=f.length-1;j++)
		{
			if(f.elements[j].name.indexOf('fecha_vencimiento')!=-1)	
			{	vector_fechavenci[indice]=f.elements[j].value;
				indice++;	
			}
		}
		indice=0;
		for(j=0;j<=f.length-1;j++)
		{
			if(f.elements[j].name.indexOf('cantidad_unitaria')!=-1)	
			{	vector_cantidades[indice]=f.elements[j].value;
				indice++;	
			}
		}
		var buscado,cant_buscado;
		for(k=0;k<=indice;k++)
		{	buscado=vector_material[k];
			cant_buscado=0;
			for(m=0;m<=indice;m++)
			{	if(buscado==vector_material[m])
				{	cant_buscado=cant_buscado+1;
				}
			}
			if(cant_buscado>1)
			{	alert('Los Materiales no pueden repetirse.');
				return(false);
			}
		}
		location.href='guarda_ingresoalmacenes.php?vector_material='+vector_material+'&vector_fechavenci='+vector_fechavenci+'&vector_cantidades='+vector_cantidades+'&fecha='+fecha+'&tipo_ingreso='+tipo_ingreso+'&observaciones='+observaciones+'&cantidad_material='+cantidad_material+'&vector_tipomaterial='+vector_tipomaterial+'';
	}
	</script>";
require("conexion.inc");
require("estilos_almacenes.inc");
if($fecha=="")
{	$fecha=date("d/m/Y");
}
echo "<form action='' method='post'>";
echo "<table border='0' class='textotit' align='center'><tr><td>Adicionar Ingreso a Almacen</td></tr></table><br>";
echo "<table border='1' class='texto' cellspacing='0' align='center' width='70%'>";
echo "<tr><th>Fecha</th><th>Tipo de Ingreso</th><th>Observaciones</th></tr>";
echo "<tr><td>";
	echo"<INPUT type='text' class='texto' value='$fecha' id='fecha' size='10' name='fecha'>";
	echo" <IMG id='imagenFecha' src='imagenes/fecha.bmp'>";
	echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
	echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
	echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
	echo" input_element_id='fecha'";
	echo" click_element_id='imagenFecha'></DLCALENDAR></td>";
$sql1="select cod_tipoingreso, nombre_tipoingreso from tipos_ingreso order by nombre_tipoingreso";
$resp1=mysql_query($sql1);
echo "<td align='center'><select name='tipo_ingreso' class='texto'>";
while($dat1=mysql_fetch_array($resp1))
{	$cod_tipoingreso=$dat1[0];
	$nombre_tipoingreso=$dat1[1];
	if($cod_tipoingreso==$tipo_ingreso)
	{	echo "<option value='$cod_tipoingreso' selected>$nombre_tipoingreso</option>";
	}
	else
	{	echo "<option value='$cod_tipoingreso'>$nombre_tipoingreso</option>";
	}
}
echo "</select></td>";
echo "<td align='center'><input type='text' class='texto' name='observaciones' value='$observaciones' size='60'></td></tr>";
echo "</table><br>";
echo "<table border=1 class='texto' width='90%' align='center'>";
$sql_detalle_salida="select * from salida_detalle_almacenes where cod_salida_almacen='$codigo_registro'";
$resp_detalle_salida=mysql_query($sql_detalle_salida);
$cantidad_materiales=mysql_num_rows($resp_detalle_salida);
echo "<input type='hidden' name='cantidad_material' value='$cantidad_materiales'>";
echo "<tr><th width='5%'>&nbsp;</th><th width='45%'>Material</th><th width='25%'>Fecha de Vencimiento</th><th width='25%'>Cantidad Unitaria</th></tr>";
$indice_detalle=1;
while($dat_detalle_salida=mysql_fetch_array($resp_detalle_salida))
{	$cod_material=$dat_detalle_salida[1];
	$fecha_de_vencimiento=$dat_detalle_salida[2];
	$cantidad_unitaria=$dat_detalle_salida[3];
	$tipo_material=$dat_detalle_salida[4];
	echo "<tr><td align='center'>$indice_detalle</td>";
	if($tipo_material==1)
	{	$sql_materiales="select codigo, descripcion, presentacion from muestras_medicas where codigo='$cod_material' order by descripcion";
		$resp_materiales=mysql_query($sql_materiales);
		$dat_materiales=mysql_fetch_array($resp_materiales);
		$nombre_material="$dat_materiales[1] $dat_materiales[2]";
	}
	else
	{	$sql_materiales="select codigo_material, descripcion_material from material_apoyo where codigo_material='$cod_material' order by descripcion_material";
		$resp_materiales=mysql_query($sql_materiales);
		$dat_materiales=mysql_fetch_array($resp_materiales);
		$nombre_material="$dat_materiales[1]";
	}
	echo "<td>$nombre_material</td>";
	echo "<input type='hidden' value='$cod_material' name='materiales$indice_detalle'>";
	echo "<td align='center'>$fecha_de_vencimiento</td>";
	echo "<input type='hidden' value='$fecha_de_vencimiento' name='fecha_vencimiento$indice_detalle'>";
	echo "<td align='center'><input type='text' name='cantidad_unitaria$indice_detalle' value='$cantidad_unitaria' class='texto' onKeypress='if (event.keyCode < 48 || event.keyCode > 57 ) event.returnValue = false;'></td>";
	$indice_detalle++;
}
/*for($indice_detalle=1;$indice_detalle<=$cantidad_material;$indice_detalle++)
{	echo "<tr><td align='center'>$indice_detalle</td>";
	$sql_materiales="select codigo, descripcion, presentacion from muestras_medicas order by descripcion";
	$resp_materiales=mysql_query($sql_materiales);
	//obtenemos los valores de las variables creadas en tiempo de ejecucion
	$var_material="materiales$indice_detalle";
	$valor_material=$$var_material;
	echo "<td align='center'><select name='materiales$indice_detalle' class='textomini'>";
	echo "<option></option>";
	while($dat_materiales=mysql_fetch_array($resp_materiales))
	{	$cod_material=$dat_materiales[0];
		$nombre_material=$dat_materiales[1];
		$presentacion_material=$dat_materiales[2];
		if($cod_material==$valor_material)
		{	echo "<option value='$cod_material' selected>$nombre_material $presentacion_material</option>";
		}
		else
		{	echo "<option value='$cod_material'>$nombre_material $presentacion_material</option>";
		}
	}
	echo "<option value=''>--------------------- Material Promocional ---------------------</option>";
	$sql_materiales="select * from material_apoyo where estado='1' and codigo_material<>0 order by descripcion_material";
	$resp_materiales=mysql_query($sql_materiales);
	while($dat_materiales=mysql_fetch_array($resp_materiales))
	{	$cod_material=$dat_materiales[0];
		$nombre_material=$dat_materiales[1];
		if($cod_material==$valor_material)
		{	echo "<option value='$cod_material' selected>$nombre_material</option>";
		}
		else
		{	echo "<option value='$cod_material'>$nombre_material</option>";
		}
	}
	echo "</select></td>";
	$var_fecha_vencimiento="fecha_vencimiento$indice_detalle";
	$valor_fecha_vencimiento=$$var_fecha_vencimiento;
	echo "<td align='center'>";
	echo" <INPUT type='text' class='texto' value='$valor_fecha_vencimiento' id='fecha_vencimiento$indice_detalle' size='10' name='fecha_vencimiento$indice_detalle'>";
	echo" <IMG id='imagenFecha$indice_detalle' src='imagenes/fecha.bmp'>";
	echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
	echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
	echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
	echo" input_element_id='fecha_vencimiento$indice_detalle'";
	echo" click_element_id='imagenFecha$indice_detalle'></DLCALENDAR></td>";
	$var_cant_unit="cantidad_unitaria$indice_detalle";
	$valor_cant_unit=$$var_cant_unit;
	echo "<td align='center'><input type='text' name='cantidad_unitaria$indice_detalle' value='$valor_cant_unit' class='texto' onKeypress='if (event.keyCode < 48 || event.keyCode > 57 ) event.returnValue = false;'></td>";
	echo "</tr>";
}
*/
echo "</table>";
echo"\n<table align='center'><tr><td><a href='navegador_ingresoalmacenes.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
echo "<center><input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
echo "</div></body>";
echo "<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";
?>