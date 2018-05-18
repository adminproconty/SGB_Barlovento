<?php

	/* Connect To Database*/

	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos

	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos

	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado

	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

	if($action == 'ajax'){

		$aColumns = array('date', 'cod', 'name', 'cant', 'total');//Columnas de busqueda

		include 'pagination.php'; //include pagination file

		//pagination variables

		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;

		$per_page = 10; //how much records you want to show

		$adjacents  = 4; //gap between pages after number of adjacents

		$offset = ($page - 1) * $per_page;

		//Count the total number of row in your table*/


			if($_GET['id_producto'] == '') {

				$count_query   = mysqli_query($con, "
            		SELECT count(df.`numero_factura`) AS numrows, df.`id_producto`, df.`cantidad`, df.`precio_venta`, 
            		fac.`fecha_factura`,fac.`total_venta`, fac.`estado_factura`, prod.`codigo_producto`, 
					prod.`nombre_producto` , (select user_name from users us where us.user_id = fac.id_vendedor) as vendedor, fac.numero_factura,
					(df.`cantidad` * df.`precio_venta`) as subtotal_venta
            		FROM `detalle_factura` as df 
            		JOIN `facturas` as fac ON (df.`numero_factura` = fac.`numero_factura`) 
            		JOIN `products` as prod ON (df.`id_producto` = prod.`id_producto`)
            		WHERE fac.`fecha_factura` >= '".$_GET['inicio']." 00:00:00' 
            		AND fac.`fecha_factura` <= '".$_GET['fin']." 23:59:59'
        		");
			
				$sql="SELECT df.`numero_factura`, df.`id_producto`, df.`cantidad`, df.`precio_venta`, 
                	fac.`fecha_factura`,fac.`total_venta`, fac.`estado_factura`, prod.`codigo_producto`, 
                	prod.`nombre_producto` , (select user_name from users us where us.user_id = fac.id_vendedor) as vendedor, fac.numero_factura,
					(df.`cantidad` * df.`precio_venta`) as subtotal_venta
                	FROM `detalle_factura` as df 
                	JOIN `facturas` as fac ON (df.`numero_factura` = fac.`numero_factura`) 
                	JOIN `products` as prod ON (df.`id_producto` = prod.`id_producto`)
                	WHERE fac.`fecha_factura` >= '".$_GET['inicio']." 00:00:00' 
                	AND fac.`fecha_factura` <= '".$_GET['fin']." 23:59:59'";

			} else {

				$count_query   = mysqli_query($con, "
            		SELECT count(df.`numero_factura`) AS numrows, df.`id_producto`, df.`cantidad`, df.`precio_venta`, 
            		fac.`fecha_factura`,fac.`total_venta`, fac.`estado_factura`, prod.`codigo_producto`, 
					prod.`nombre_producto` , (select user_name from users us where us.user_id = fac.id_vendedor) as vendedor, fac.numero_factura,
					(df.`cantidad` * df.`precio_venta`) as subtotal_venta
            		FROM `detalle_factura` as df 
            		JOIN `facturas` as fac ON (df.`numero_factura` = fac.`numero_factura`) 
            		JOIN `products` as prod ON (df.`id_producto` = prod.`id_producto`)
            		WHERE df.`id_producto` = ".$_GET['id_producto']." 
            		AND fac.`fecha_factura` >= '".$_GET['inicio']." 00:00:00' 
            		AND fac.`fecha_factura` <= '".$_GET['fin']." 23:59:59'
        		");
			
				$sql="SELECT df.`numero_factura`, df.`id_producto`, df.`cantidad`, df.`precio_venta`, 
                	fac.`fecha_factura`,fac.`total_venta`, fac.`estado_factura`, prod.`codigo_producto`, 
                	prod.`nombre_producto` , (select user_name from users us where us.user_id = fac.id_vendedor) as vendedor, fac.numero_factura,
					(df.`cantidad` * df.`precio_venta`) as subtotal_venta
                	FROM `detalle_factura` as df 
                	JOIN `facturas` as fac ON (df.`numero_factura` = fac.`numero_factura`) 
                	JOIN `products` as prod ON (df.`id_producto` = prod.`id_producto`)
                	WHERE df.`id_producto` = ".$_GET['id_producto']." 
                	AND fac.`fecha_factura` >= '".$_GET['inicio']." 00:00:00' 
                	AND fac.`fecha_factura` <= '".$_GET['fin']." 23:59:59'";

			}

			
		

		$row= mysqli_fetch_array($count_query);

		$numrows = $row['numrows'];

		$total_pages = ceil($numrows/$per_page);

		$reload = './reportes.php';

		$query = mysqli_query($con, $sql);

		//loop through fetched data

?>
<input id="cantidad" type='hidden' value="<?php echo $numrows?>">	
<script>
	var cantidad= $("#cantidad").val();
	if(cantidad > 0){
		localStorage.setItem('exportar', 1);
	}else{
		localStorage.setItem('exportar', 0);
	}
</script>
<?php

		if ($numrows>0){

			

			?>

			<div class="table-responsive">

			  <table class="table" id="Exportar_Productos">

				<tr  class="info">

					<th>Id</th>

					<th>Fecha</th>

					<th>Vendedor</th>

					<th>Código</th>

					<th>Nombre</th>

					<th class="text-center">Cantidad</th>

					<th class="text-center">Total Venta</th>

					

				</tr>

				<?php

				while ($row=mysqli_fetch_array($query)){
					    $numero= $row['numero_factura'];  

                        $date= date('d/m/Y', strtotime($row['fecha_factura']));    

						$vendedor=$row['vendedor'];


						$cod=$row['codigo_producto'];

						$nombre=$row['nombre_producto'];

						$cantidad=$row['cantidad'];

						$total=number_format($row['total_venta'],2,'.','');

						$subtotal=number_format($row['subtotal_venta'],2,'.','');

						$vededor=$row['vendedor'];
						

					?>
				

					<tr>

						<td><?php echo $numero; ?></td>

						<td><?php echo $date; ?></td>

						<td><?php echo $vendedor; ?></td>

						<td><?php echo $cod; ?></td>

						<td><?php echo $nombre; ?></td>

						<td class="text-center"><?php echo str_replace(".",",",$cantidad); ?></td>

						<td class="text-center">$<?php echo str_replace(".",",",$subtotal);?></td>						

					</tr>

					<?php

				}

				?>

				<tr>

					<td colspan=9><span class="pull-right">

					<?php

					 //echo paginate($reload, $page, $total_pages, $adjacents);

					?></span></td>

				</tr>

			  </table>

			<form action="ficheroExcel.php" method="post" target="_blank" id="FormularioExportacion">
				<input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
				<input type="hidden" id="nombre_reporte" name="nombre_reporte" />
			</form>

			</div>

			<?php

		}

	}

?>