<?php
header("Content-type: application/vnd.ms-excel; name='excel';charset=UTF-8");
header("Content-Disposition: filename=Reporte.xls");
header("Pragma: no-cache");
header("Expires: 0");

$desde = $_POST['inicio'];
$hasta = $_POST['fin'];
if ($_POST['nombre_reporte'] == "cliente"){
    $nombre_reporte = "REPORTE AGRUPADO POR CLIENTES";
} else if($_POST['nombre_reporte'] == "producto"){
    $nombre_reporte = "REPORTE POR PRODUCTOS";
} else {
    $nombre_reporte = "REPORTE DETALLADO POR CLIENTES";
}

echo "<table>
        <tr bgcolor='#dcf3f9'>
            <td colspan='2'><font size=4><b>$nombre_reporte</b></font></td>
        </tr>
        <tr>
            <td></td>
        </tr>

</table>";


if (isset($_POST['datos_a_enviar']) && $_POST['datos_a_enviar'] != '') echo utf8_decode($_POST['datos_a_enviar']);
?>