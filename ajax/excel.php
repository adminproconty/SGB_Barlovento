<?php

require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos

if($_GET['action'] == 'cliente') {
    if($_GET['metodo'] == 'byid'){
        $sql="SELECT fac.`numero_factura`, 
            fac.`fecha_factura`,  cli.`documento_cliente`,
            cli.`nombre_cliente`, fac. `total_venta`
            FROM `facturas` as fac
            JOIN `clientes` as cli ON (fac.`id_cliente` = cli.`id_cliente`)
            WHERE fac.`id_cliente` = ".$_GET['id_cliente']."
            AND fac.`fecha_factura` >= '".$_GET['inicio']."' 
            AND fac.`fecha_factura` <= '".$_GET['fin']."'";
    }else if($_GET['metodo'] == 'all'){
        $sql="SELECT fac.`numero_factura`, 
            fac.`fecha_factura`,  cli.`documento_cliente`,
            cli.`nombre_cliente`, fac. `total_venta`
            FROM `facturas` as fac
            JOIN `clientes` as cli ON (fac.`id_cliente` = cli.`id_cliente`)";
    }    
}else if($_GET['action'] == 'producto'){
    if($_GET['metodo'] == 'byid'){
        $sql="SELECT fac.`fecha_factura`, prod.`codigo_producto`, prod.`nombre_producto`,
                df.`cantidad`, fac.`total_venta`
                FROM `detalle_factura` as df 
                JOIN `facturas` as fac ON (df.`numero_factura` = fac.`numero_factura`) 
                JOIN `products` as prod ON (df.`id_producto` = prod.`id_producto`)
                WHERE df.`id_producto` = ".$_GET['id_producto']." 
                AND fac.`fecha_factura` >= '".$_GET['inicio']."' 
                AND fac.`fecha_factura` <= '".$_GET['fin']."'";
    }else if($_GET['metodo'] == 'all'){
        $sql="SELECT fac.`fecha_factura`, prod.`codigo_producto`, prod.`nombre_producto`,
                df.`cantidad`, fac.`total_venta`
                FROM `detalle_factura` as df 
                JOIN `facturas` as fac ON (df.`numero_factura` = fac.`numero_factura`) 
                JOIN `products` as prod ON (df.`id_producto` = prod.`id_producto`)";
    } 
}else if($_GET['action'] == 'all'){
    $sql="SELECT df.`numero_factura`, df.`id_producto`, df.`cantidad`, 
            df.`precio_venta`, fac.`id_cliente`, fac.`fecha_factura`,fac.`total_venta`, 
            fac.`estado_factura`, prod.`codigo_producto`, prod.`nombre_producto`, 
            cli.`documento_cliente`, cli.`nombre_cliente` 
            FROM `detalle_factura` as df 
            JOIN `facturas` as fac ON (df.`numero_factura` = fac.`numero_factura`) 
            JOIN `products` as prod ON (df.`id_producto` = prod.`id_producto`) 
            JOIN `clientes` as cli ON (fac.`id_cliente` = cli.`id_cliente`)";
}

if ($con->connect_error) {  //error check
    die("Connection failed: " . $con->connect_error);
}
else
{

}

if($_GET['action'] == 'cliente'){
    if($_GET['metodo'] == 'byid'){
        $filename = "ConsultaCliente".$_GET['id_cliente']."del".$_GET['inicio']."al".$_GET['fin'];  //your_file_name
    }else if($_GET['metodo'] == 'all'){
        $filename = "ConsultaConsumoAllClientes";  //your_file_name
    }
}else if($_GET['action'] == 'producto'){
    if($_GET['metodo'] == 'byid'){
        $filename = "ConsultaProducto".$_GET['id_producto']."del".$_GET['inicio']."al".$_GET['fin'];  //your_file_name
    }else if($_GET['metodo'] == 'all'){
        $filename = "ConsultaConsumoAllProductos";  //your_file_name
    }
}else if($_GET['action'] == 'all'){
    $filename = "ConsultaDeTodosLosConsumos";  //your_file_name
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