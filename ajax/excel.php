<?php

if($_GET['action'] == 'cliente') {
    $sql="SELECT fac.`numero_factura`, 
            fac.`fecha_factura`,  cli.`documento_cliente`,
            cli.`nombre_cliente`, fac. `total_venta`
            FROM `facturas` as fac
            JOIN `clientes` as cli ON (fac.`id_cliente` = cli.`id_cliente`)
            WHERE fac.`id_cliente` = ".$_GET['id_cliente']."
            AND fac.`fecha_factura` >= '".$_GET['inicio']."' 
            AND fac.`fecha_factura` <= '".$_GET['fin']."'";
}else if($_GET['action'] == 'producto'){
    $sql="SELECT fac.`fecha_factura`, prod.`codigo_producto`, prod.`nombre_producto`,
                df.`cantidad`, fac.`total_venta`
                FROM `detalle_factura` as df 
                JOIN `facturas` as fac ON (df.`numero_factura` = fac.`numero_factura`) 
                JOIN `products` as prod ON (df.`id_producto` = prod.`id_producto`)
                WHERE df.`id_producto` = ".$_GET['id_producto']." 
                AND fac.`fecha_factura` >= '".$_GET['inicio']."' 
                AND fac.`fecha_factura` <= '".$_GET['fin']."'";
}

$servername = "localhost";
$username = "proco389_usersgb";
$password = "barlovento2018";
$dbname = "proco389_sgbbarlovento";
//mysql and db connection
//Para servidor
//define('DB_USER', 'proco389_usersgb');//Usuario de tu base de datos
//define('DB_PASS', 'barlovento2018');//Contraseña del usuario de la base de datos
//define('DB_NAME', 'proco389_sgbbarlovento');//Nombre de la base de datos

$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {  //error check
    die("Connection failed: " . $con->connect_error);
}
else
{

}

if($_GET['action'] == 'cliente'){
    $filename = "ConsultaCliente".$_GET['id_cliente']."del".$_GET['inicio']."al".$_GET['fin'];  //your_file_name
}else if($_GET['action'] == 'producto'){
    $filename = "ConsultaProducto".$_GET['id_producto']."del".$_GET['inicio']."al".$_GET['fin'];  //your_file_name
}

$DB_TBLName = "clientes"; 
$file_ending = "xls";   //file_extention

header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=$filename.xls");  
header("Pragma: no-cache"); 
header("Expires: 0");

$sep = "\t";
$resultt = $con->query($sql);
$utf8 = $con->set_charset("SET NAMES 'utf8'");
while ($property = mysqli_fetch_field($resultt)) { //fetch table field name
    echo $property->name."\t";
}

print("\n");    

while($row = mysqli_fetch_row($resultt))  //fetch_table_data
{
    $schema_insert = "";
    for($j=0; $j< mysqli_num_fields($resultt);$j++)
    {
        if(!isset($row[$j]))
            $schema_insert .= "NULL".$sep;
        elseif ($row[$j] != "")
            $schema_insert .= "$row[$j]".$sep;
        else
            $schema_insert .= "".$sep;
    }
    $schema_insert = str_replace($sep."$", "", $schema_insert);
    $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
    $schema_insert .= "\t";
    print(trim($schema_insert));
    print "\n";
}
	
?>