<?php
	require("conexion.inc");
	require("estilos_administracion.inc");
 
echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_distrito.php?cod_territorio=$cod_territorio';
		}
		function eliminar_nav(f)
		{	
			var i;
			var j=0;
			datos=new Array();
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	datos[j]=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j==0)
			{	alert('Debe seleccionar al menos un distrito para proceder a su eliminación.');
			}
			else
			{	
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_distritos.php?datos='+datos+'&cod_territorio=$cod_territorio';
				}
				else
				{
					return(false);
				}
			}
		}
		function editar_nav(f)
		{
			var i;
			var j=0;
			var j_distrito;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_distrito=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un distrito para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un distrito para editar sus datos.');
				}
				else
				{
					location.href='editar_distritos.php?cod_distrito='+j_distrito+'&cod_territorio=$cod_territorio';
				}
			}
		}
		</script>";	
		$sql_cab="select descripcion from ciudades where cod_ciudad=$cod_territorio";
		$resp_cab=mysql_query($sql_cab);
		$dat_cab=mysql_fetch_array($resp_cab);
		$nombre_ciudad=$dat_cab[0];
	echo "<form method='post' action=''>";
	$sql="select * from distritos where cod_ciudad=$cod_territorio order by descripcion";
	$resp=mysql_query($sql);
	echo "<h1>Registro de Distritos<br>Territorio $nombre_ciudad</h1>";
	
	echo "<center><table class='texto'>";
	echo "<tr><th>&nbsp;</th><th>Distrito</th><th>Zonas</th><th>&nbsp;</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_distrito=$dat[1];
		$desc_distrito=$dat[2];
		$sql_zonas="select cod_zona, zona from zonas where cod_dist='$cod_distrito' order by zona";
		$resp_zonas=mysql_query($sql_zonas);
		$cadena_zonas="<table class='textomini'>";
		while($dat_zonas=mysql_fetch_array($resp_zonas))
		{	$cod_zona=$dat_zonas[0];
			$nombre_zona=$dat_zonas[1];
			$cadena_zonas="$cadena_zonas <tr><td>$nombre_zona ($cod_zona)</td></tr>";
		}
		$cadena_zonas="$cadena_zonas </table>";
		echo "<tr><td><input type='checkbox' name='codigo' value='$cod_distrito'></td>
			<td align='center'>$desc_distrito</td>
			<td>$cadena_zonas</td><td align='center'>
				<a href='navegador_zonas.php?cod_territorio=$cod_territorio&cod_distrito=$cod_distrito'>
					<img src='imagenes/puntomapa.png' width='40' title='Ver Zonas'>
				</a>
			</td></tr>";
	}
	echo "</table></center><br>";

	echo "<div class='divBotones'>
	<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
	<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
	<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
	<input type='button' value='Cancelar' name='eliminar' class='boton2' onclick='location.href=\"navegador_territorios.php\"'>
	</div>";

	echo "</form>";
?>