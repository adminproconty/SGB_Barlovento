



<?php

include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado



/* Connect To Database*/



require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos



require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos



//Archivo de funciones PHP



include("../funciones.php");



$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';


if($action == 'ajax'){    

    if (isset($_GET['all'])) {
        $sql="SELECT li.`id_loginv`, li.`fecha_loginv`, li.`producto_loginv`, prod.`codigo_producto`, 
                prod.`nombre_producto`, li.`cantidad_loginv`, li.`tipo_loginv`, li.`motivo_loginv` 
                FROM `log_inventario` as li
                JOIN `products` as prod ON (li.`producto_loginv` = prod.`id_producto`)";
    } else {
        $fecha_desde = $_GET['desde'];
        $fecha_hasta = $_GET['hasta'];
        $sql="SELECT li.`id_loginv`, li.`fecha_loginv`, li.`producto_loginv`, prod.`codigo_producto`, 
                prod.`nombre_producto`, li.`cantidad_loginv`, li.`tipo_loginv`, li.`motivo_loginv` 
                FROM `log_inventario` as li
                JOIN `products` as prod ON (li.`producto_loginv` = prod.`id_producto`)
                WHERE li.`fecha_loginv` >= '".$fecha_desde."' AND li.`fecha_loginv` <= '".$fecha_hasta."'";
    }

    $query = mysqli_query($con, $sql);
    $numrows = mysqli_num_rows($query);


//loop through fetched data



if ($numrows>0){



    $simbolo_moneda=get_row('perfil','moneda', 'id_perfil', 1);



    ?>

<div class="table-responsive">

<table class="table">

   <tr  class="info">

      <th>Fecha</th>
      <th>Código</th>
      <th>Producto</th>
      <th class='text-right'>Cantidad</th>
      <th>Tipo Movimiento</th>
      <th>Motivo</th>

   </tr>

   <?php

      while ($row=mysqli_fetch_array($query)){

      

              $fecha=date("d/m/Y", strtotime($row['fecha_loginv']));    
              $codigo_producto=$row['codigo_producto'];
              $nombre_producto=$row['nombre_producto'];
              $cantidad=$row['cantidad_loginv'];
              $tipo=$row['tipo_loginv'];
              $motivo=$row['motivo_loginv'];     

    ?>

   <tr>

      <td><?php echo $fecha; ?></td>
      <td><?php echo $codigo_producto; ?></td>
      <td ><?php echo $nombre_producto; ?></td>
      <td><span class='pull-right'><?php echo $cantidad;?></span></td>
      <td ><?php echo $tipo; ?></td>
      <td ><?php echo $motivo; ?></td>

   </tr>

   <?php

      }

      

      ?>

</table>

</div>

<?php

}



}



?>



