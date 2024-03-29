<script language='JavaScript'>
    function totales(){
        var main=document.getElementById('main');
        var numFilas=main.rows.length;
        var numCols=main.rows[2].cells.length;
        for(var j=1; j<=numCols-1; j++) {
            var subtotal=0;
            for(var i=2; i<=numFilas-2; i++) {
                var dato=parseInt(main.rows[i].cells[j].innerHTML);
                subtotal=subtotal+dato;
            }
            var fila=document.createElement('TH');
            main.rows[numFilas-1].appendChild(fila);
            main.rows[numFilas-1].cells[j].innerHTML=subtotal;
        }
    }
</script>
<?php
require("conexion.inc");
require("estilos_reportes.inc");

$rpt_visitador = $_GET["rpt_visitador"];
$rpt_gestion = $_GET["rpt_gestion"];
$rpt_ciclo = $_GET["rpt_ciclo"];
$rpt_territorio = $_GET["rpt_territorio"];
$rpt_linea = $_GET['rpt_linea'];
$rptNombreLinea = $_GET['rptNombreLinea'];
$rptVer = $_GET['rpt_ver'];
$tipo_distribucion = $_GET['rpt_ver_dis'];

$sql_cab = "SELECT cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";
$resp1 = mysql_query($sql_cab);
$dato = mysql_fetch_array($resp1);
$nombre_territorio = $dato[1];
$cad_territorio = "<br>Territorio: $nombre_territorio";

$sql_nombreGestion = mysql_query("SELECT nombre_gestion from gestiones where codigo_gestion=$rpt_gestion");
$dat_nombreGestion = mysql_fetch_array($sql_nombreGestion);
$nombreGestion = $dat_nombreGestion[0];
echo "<html><body onload='totales();'>";
echo "<table border='0' class='textotit' align='center'>";
echo "<tr><th>Reporte Distribucion x Ciclo x Visitador<br>
Gestion: $nombreGestion Ciclo: $rpt_ciclo $cad_territorio<br>
Linea: $rptNombreLinea
</th></tr></table></center><br>";

echo "<table border=0 class='texto' align='center' cellspacing=0 id='main'>";
$sql_visitadores="SELECT paterno, materno, nombres from funcionarios where cod_ciudad='$rpt_territorio'and estado=1 and cod_cargo='1011' and codigo_funcionario in ($rpt_visitador) order by paterno, materno";
$resp_visitadores=mysql_query($sql_visitadores);
echo "<tr><th>&nbsp;Producto</th>";
while($dat_visitadores=mysql_fetch_array($resp_visitadores))
{   $cod_visitador=$dat_visitadores[0];
    $nombre_visitador="$dat_visitadores[0] $dat_visitadores[2]";
    echo "<th>$nombre_visitador</th>";
}
echo "<th colspan=9>TOTALES</th></tr>";

$resp_visitadores=mysql_query($sql_visitadores);
echo "<tr><th>&nbsp;</th>";
while($dat_visitadores=mysql_fetch_array($resp_visitadores))
{   echo "<th>CP</th></th>";
}
echo "<th>CP</th>";
echo "</tr>";

if ($rptVer == 0) {
    /* $sql_productos="
      SELECT distinct (d.codigo_producto), concat(m.`descripcion`,' ',m.presentacion),
      d.grupo_salida from distribucion_productos_visitadores d, `muestras_medicas` m
      where d.cod_ciclo in ($rpt_ciclo) and d.codigo_gestion = $rpt_gestion and
      d.codigo_linea in ($rpt_linea) and m.`codigo`=d.`codigo_producto` order by m.`descripcion`";
     */
    $sql_productos = "SELECT * from (SELECT distinct (d.codigo_producto), concat(m.`descripcion`,' ',m.presentacion),
        d.grupo_salida from distribucion_productos_visitadores d, `muestras_medicas` m
        where d.cod_ciclo in ($rpt_ciclo) and d.codigo_gestion = $rpt_gestion and
        d.codigo_linea in ($rpt_linea) and m.`codigo`=d.`codigo_producto`
			union 
        SELECT distinct (d.codigo_producto), concat(m.`descripcion`,' ',m.presentacion),
        d.grupo_salida from distribucion_grupos_especiales d, `muestras_medicas` m
        where d.cod_ciclo in ($rpt_ciclo) and d.codigo_gestion = $rpt_gestion and
        m.`codigo`=d.`codigo_producto`
                                                        union
        SELECT distinct (d.codigo_producto), concat(m.`descripcion`,' ',m.presentacion),
        d.grupo_salida from distribucion_banco_muestras d, `muestras_medicas` m
        where d.cod_ciclo in ($rpt_ciclo) and d.codigo_gestion = $rpt_gestion and
        m.`codigo`=d.`codigo_producto`) as tabla order by 2";
} else {
    /* $sql_productos="
      SELECT distinct (d.codigo_producto), m.`descripcion_material`,
      d.grupo_salida from distribucion_productos_visitadores d, `material_apoyo` m
      where d.cod_ciclo in ($rpt_ciclo) and d.codigo_gestion = $rpt_gestion and
      d.codigo_linea in ($rpt_linea) and m.`codigo_material`=d.`codigo_producto`
      and m.codigo_material<>0 order by m.`descripcion_material`";
     */
    $sql_productos = "SELECT * from (SELECT distinct (d.codigo_producto), m.`descripcion_material`,
        d.grupo_salida from distribucion_productos_visitadores d, `material_apoyo` m
        where d.cod_ciclo in ($rpt_ciclo) and d.codigo_gestion = $rpt_gestion and
        d.codigo_linea in ($rpt_linea) and m.`codigo_material`=d.`codigo_producto`
        and m.codigo_material<>0
			union 
		SELECT distinct (d.codigo_producto), m.`descripcion_material`,
        d.grupo_salida from `distribucion_grupos_especiales` d, `material_apoyo` m
        where d.cod_ciclo in ($rpt_ciclo) and d.codigo_gestion = $rpt_gestion and
        m.`codigo_material`=d.`codigo_producto`
        and m.codigo_material<>0
            union
		SELECT distinct (d.codigo_producto), m.`descripcion_material`,
        d.grupo_salida from `distribucion_banco_muestras` d, `material_apoyo` m
        where d.cod_ciclo in ($rpt_ciclo) and d.codigo_gestion = $rpt_gestion and
        m.`codigo_material`=d.`codigo_producto`
        and m.codigo_material<>0) as tabla order by 2";
}

// echo $sql_productos;
$resp_productos = mysql_query($sql_productos);
while ($dat_productos = mysql_fetch_array($resp_productos)) {
    $cod_prod = $dat_productos[0];
    $nombre_producto = $dat_productos[1];
    $grupo_salida = $dat_productos[2];
    $cad_mostrar = "<tr>";
    $cad_mostrar.="<td>$nombre_producto</td>";
    $sql_visitadores = "SELECT codigo_funcionario, paterno, materno, nombres from funcionarios where cod_ciudad='$rpt_territorio'and estado=1 and cod_cargo='1011' and codigo_funcionario in ($rpt_visitador) order by paterno, materno";
//    echo $sql_visitadores."<br />";
    $resp_visitadores = mysql_query($sql_visitadores);
    //
    $suma_cantidadfaltante = 0;
    $suma_cantidadfaltante2 = 0;
    $suma_cantidadfaltante2_b = 0;
    $totalPlanificado = 0;
    $totalPlanificado2 = 0;
    $totalPlanificado2_b = 0;
    $totalDistribuido = 0;
    $totalDistribuido2 = 0;
    $totalDistribuido2_b = 0;
    $totalFaltante = 0;
    $totalFaltante2 = 0;
    $totalFaltante2_b = 0;
    //
    while ($dat_visitadores = mysql_fetch_array($resp_visitadores)) {
        $cod_visitador = $dat_visitadores[0];
        //
        $sql_dist = "SELECT sum(cantidad_planificada), sum(cantidad_distribuida) from distribucion_productos_visitadores where codigo_gestion='$rpt_gestion' and cod_ciclo in ('$rpt_ciclo') and codigo_producto='$cod_prod' and territorio='$rpt_territorio'and cod_visitador='$cod_visitador' and codigo_linea in ($rpt_linea) group by territorio, cod_visitador";
       // echo $sql_dist."<br />";
        $resp_dist = mysql_query($sql_dist);
        $dat_dist = mysql_fetch_array($resp_dist);
        $cantidad_planificada = $dat_dist[0];
        $cantidad_distribuida = $dat_dist[1];
        $cantidad_faltante = $cantidad_planificada - $cantidad_distribuida;
        $suma_cantidadfaltante = $suma_cantidadfaltante + $cantidad_faltante;
        $totalPlanificado = $totalPlanificado + $cantidad_planificada;
        $totalDistribuido = $totalDistribuido + $cantidad_distribuida;
        $totalFaltante = $totalFaltante + $cantidad_faltante;
        if ($cantidad_planificada == "") {
            $cantidad_planificada = 0;
        }
        if ($cantidad_distribuida == "") {
            $cantidad_distribuida = 0;
        }
        $cad_mostrar.="<td align='center'>$cantidad_planificada</td>";
        //COLUMNAS DISTRIBUCION DE GRUPOS ESPECIALES
        $sql_dist = "SELECT sum(cantidad_planificada), sum(cantidad_distribuida) from distribucion_grupos_especiales where codigo_gestion='$rpt_gestion' and cod_ciclo in ('$rpt_ciclo') and codigo_producto='$cod_prod' and territorio='$rpt_territorio'and cod_visitador='$cod_visitador' and codigo_linea in ($rpt_linea) group by territorio, cod_visitador";
//        echo $sql_dist."<br />";
        $resp_dist = mysql_query($sql_dist);
        $dat_dist = mysql_fetch_array($resp_dist);
        $cantidad_planificada = $dat_dist[0];
        $cantidad_distribuida = $dat_dist[1];
        $cantidad_faltante = $cantidad_planificada - $cantidad_distribuida;
        $suma_cantidadfaltante2 = $suma_cantidadfaltante2 + $cantidad_faltante;
        $totalPlanificado2 = $totalPlanificado2 + $cantidad_planificada;
        $totalDistribuido2 = $totalDistribuido2 + $cantidad_distribuida;
        $totalFaltante2 = $totalFaltante2 + $cantidad_faltante;
        if ($cantidad_planificada == "") {
            $cantidad_planificada = 0;
        }
        if ($cantidad_distribuida == "") {
            $cantidad_distribuida = 0;
        }
        
        //COLUMNAS DISTRIBUCION DE BANCO DE MUETRAS
        $sql_dist_b = "SELECT sum(cantidad_planificada), sum(cantidad_distribuida) from distribucion_banco_muestras where codigo_gestion='$rpt_gestion' and cod_ciclo in ('$rpt_ciclo') and codigo_producto='$cod_prod' and territorio='$rpt_territorio'and cod_visitador='$cod_visitador' and codigo_linea in ($rpt_linea) group by territorio, cod_visitador";
        $resp_dist_b = mysql_query($sql_dist_b);
        $dat_dist_b = mysql_fetch_array($resp_dist_b);
        $cantidad_planificada_b = $dat_dist_b[0];
        $cantidad_distribuida_b = $dat_dist_b[1];
        $cantidad_faltante_b = $cantidad_planificada_b - $cantidad_distribuida_b;
        $suma_cantidadfaltante2_b = $suma_cantidadfaltante2_b + $cantidad_faltante_b;
        $totalPlanificado2_b = $totalPlanificado2_b + $cantidad_planificada_b;
        $totalDistribuido2_b = $totalDistribuido2_b + $cantidad_distribuida_b;
        $totalFaltante2_b = $totalFaltante2_b + $cantidad_faltante_b;
        if ($cantidad_planificada_b == "") {
            $cantidad_planificada_b = 0;
        }
        if ($cantidad_distribuida_b == "") {
            $cantidad_distribuida_b = 0;
        }
        
        //$cad_mostrar.="<td>$cantidad_planificada</td><td>$cantidad_distribuida</td><td>$cantidad_faltante</td>";
        //$cad_mostrar .="<td>$cantidad_planificada_b</td><td>$cantidad_distribuida_b</td><td>$cantidad_faltante_b</td>";
        //
    }
    $cad_mostrar.="<th>$totalPlanificado</th>";
    //
    $cad_mostrar.="</tr>";
    echo $cad_mostrar;
}
echo "<tr><th>TOTALES</th></tr>";
echo "</table><br>";
echo "<center>";

require("imprimirInc.php");

echo "</center>";

echo "</form></body></html>";
?>
