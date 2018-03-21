<script language='JavaScript'>
function nuevoAjax()
{	var xmlhttp=false;
	try {
			xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
	try {
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	} catch (E) {
		xmlhttp = false;
	}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
 	xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}
function ajaxCiclos(codigo){
	var codGestion=codigo.value;
	var contenedor;
	contenedor = document.getElementById('divCiclos');
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxCiclosMultiple.php?codGestion='+codGestion+'',true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}
function ajaxVisitadores(codigo){
	var codTerritorio=new Array();
	var j=0;
	for(var i=0;i<=codigo.options.length-1;i++)
	{	if(codigo.options[i].selected)
		{	codTerritorio[j]=codigo.options[i].value;
			j++;
		}
	}
	var contenedor;
	contenedor = document.getElementById('divVisitadores');
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxVisitadores.php?codTerritorio='+codTerritorio+'',true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}
function ajaxLineas(codigo){
	var codTerritorio=new Array();
	var j=0;
	for(var i=0;i<=codigo.options.length-1;i++)
	{	if(codigo.options[i].selected)
		{	codTerritorio[j]=codigo.options[i].value;
			j++;
		}
	}
	var contenedor1;
	contenedor1 = document.getElementById('divLineas');
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxLineasMultiple.php?codTerritorio='+codTerritorio+'',true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor1.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}
function ajaxVisitadorLinea(obj){
	ajaxLineas(obj); 
	ajaxVisitadores(obj);
}

function envia_formularioResumen(f)
{	var rpt_visitador,rpt_gestion, rpt_ciclo;
	rpt_gestion=f.gestion_rpt.value;
	var rpt_ciclo=new Array();
	var rpt_nombreciclo=new Array();
	var rpt_linea=new Array();
	var rpt_nombrelinea=new Array();
	var rpt_territorio=new Array();
	var rpt_nombreterritorio=new Array();
	var rpt_categoria=new Array();
	var rpt_reporte=f.rpt_reporte.value;
	var j=0;
	for(i=0;i<=f.ciclo_rpt.options.length-1;i++)
	{	if(f.ciclo_rpt.options[i].selected)
		{	rpt_ciclo[j]=f.ciclo_rpt.options[i].value;
			j++;
		}
	}
	j=0;
	var contador=0;
	for(i=0;i<=f.rpt_territorio.options.length-1;i++)
	{	if(f.rpt_territorio.options[i].selected)
		{	rpt_territorio[j]=f.rpt_territorio.options[i].value;
			rpt_nombreterritorio[j]=f.rpt_territorio.options[i].innerHTML;
			j++;
			contador++;
		}
	}
	j=0;
	for(i=0;i<=f.rpt_categoria.options.length-1;i++)
	{	if(f.rpt_categoria.options[i].selected)
		{	rpt_categoria[j]=f.rpt_categoria.options[i].value;
			j++;
		}
	}
	if(rpt_reporte==0){
		window.open('rptCoberturaResumen.php?rpt_nombreterritorio='+rpt_nombreterritorio+'&rpt_territorio='+rpt_territorio+'&rpt_gestion='+rpt_gestion+'&rpt_ciclo='+rpt_ciclo+'&rpt_categoria='+rpt_categoria+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
	}
	if(rpt_reporte==3){
		window.open('rptCoberturaResumenTiposBaja.php?rpt_nombreterritorio='+rpt_nombreterritorio+'&rpt_territorio='+rpt_territorio+'&rpt_gestion='+rpt_gestion+'&rpt_ciclo='+rpt_ciclo+'&rpt_categoria='+rpt_categoria+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
	}
	if(rpt_reporte==1){
		window.open('rptCoberturaResumenArea.php?rpt_nombreterritorio='+rpt_nombreterritorio+'&rpt_territorio='+rpt_territorio+'&rpt_gestion='+rpt_gestion+'&rpt_ciclo='+rpt_ciclo+'&rpt_categoria='+rpt_categoria+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
	}
	if(rpt_reporte==4){
		window.open('rptCoberturaResumenAreaTiposBaja.php?rpt_nombreterritorio='+rpt_nombreterritorio+'&rpt_territorio='+rpt_territorio+'&rpt_gestion='+rpt_gestion+'&rpt_ciclo='+rpt_ciclo+'&rpt_categoria='+rpt_categoria+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
	}
	
	if(rpt_reporte==2){
		if(contador==1){
			window.open('rptCoberturaResumenVisitador.php?rpt_nombreterritorio='+rpt_nombreterritorio+'&rpt_territorio='+rpt_territorio+'&rpt_gestion='+rpt_gestion+'&rpt_ciclo='+rpt_ciclo+'&rpt_categoria='+rpt_categoria+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
		}else{
			alert('Para este reporte debe seleccionar solamente 1 territorio.');
			return(false);
		}		
	}
	return(true);
}

</script>
<?php
require("conexion.inc");
require("estilos_administracion.inc");
echo "<center><table class='textotit'><tr><th>Reporte de Cobertura Semanal</th></tr></table><br>";
echo"<form method='post'>";
	echo"\n<table class='texto' border='0' align='center' cellSpacing='0' width='30%'>\n";
	echo "<tr><th align='left'>Gesti&oacute;n</th>";
	$sql_gestion="select distinct(codigo_gestion), nombre_gestion, estado from gestiones order by 1 desc";
	$resp_gestion=mysql_query($sql_gestion);
	echo "<td><select name='gestion_rpt' class='texto' onChange='ajaxCiclos(this)'>";
	$bandera=0;
	echo "<option></option>";
	while($datos_gestion=mysql_fetch_array($resp_gestion))
	{	$cod_gestion_rpt=$datos_gestion[0];$nom_gestion_rpt=$datos_gestion[1];$estado_gestion_rpt=$datos_gestion[2];
		echo "<option value='$cod_gestion_rpt'>$nom_gestion_rpt</option>";
	}
	echo $cod_gestion_rpt;
	echo "</select></td></tr>";
	echo "<tr><th align='left'>Ciclo</th>";
	echo "<td><div id='divCiclos'></td></tr>";
		
	echo "<tr><th align='left'>Territorio</th>
	<td>
	<select name='rpt_territorio' class='texto' size='15' multiple>";
	$sql="select c.cod_ciudad, c.descripcion from ciudades c, `funcionarios_agencias` f where 
				f.`cod_ciudad`=c.`cod_ciudad` and f.`codigo_funcionario`=$global_usuario 
				and f.cod_ciudad<>115 order by c.descripcion";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";
	
	
	echo "<tr><th align='left'>Cat. Medicos</th>
	<td>
	<select name='rpt_categoria' class='texto' size='4' multiple>";
	$sql="select categoria_med from categorias_medicos";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo=$dat[0];
		$claveCodigo="|$codigo|";
		echo "<option value='$claveCodigo'>-$codigo-</option>";
	}
	echo "</select></td></tr>";
	
	echo "<tr><th align='left'>Ver Reporte</th>
	<td>
	<select name='rpt_reporte' class='texto' size='1'>
	<option value='0'>Resumen por Semana</option>
	<option value='3'>Resumen por Semana Con Tipos de Baja</option>
	<option value='1'>Resumen por Semana y Territorio</option>
	<option value='4'>Resumen por Semana y Territorio Con Tipos de Baja</option>
	<option value='2'>Resumen por Semana, Territorio y Visitador</option>
	</select></td></tr>";
	
	echo"\n </table><br>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formularioResumen(this.form)' class='boton'>";
	echo"</form>";
?>