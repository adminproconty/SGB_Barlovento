<?php



	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado

	/* Connect To Database*/

	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos

	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos

	

	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

	if($action == 'ajax'){

		// escaping, additionally removing everything that could be (html/javascript-) code

        $q = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));

        $sql = "SELECT inv.*, prod.* FROM 
                        `inventario` as inv
                        JOIN `products` as prod on (inv.`producto_inventario` = prod.`id_producto`)
                        WHERE prod.`nombre_producto` LIKE '%".$_GET['q']."%'
                        OR prod.`codigo_producto` LIKE '%".$_GET['q']."%'";

        $query = mysqli_query($con, $sql);

        $numrows = mysqli_num_rows($query);

		include 'pagination.php'; //include pagination file

		//pagination variables

		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;

		$per_page = 5; //how much records you want to show

		$adjacents  = 4; //gap between pages after number of adjacents

		$offset = ($page - 1) * $per_page;

		$total_pages = ceil($numrows/$per_page);

		$reload = './index.php';

		//loop through fetched data

		if ($numrows>0){

			

			?>

			<div class="table-responsive">

			  <table class="table">

				<tr  class="warning">

					<th>Código</th>

					<th>Producto</th>

					<th><span class="pull-right">Cant.</span></th>

					<th><span class="pull-right">Precio</span></th>

					<th class='text-center' style="width: 36px;">Agregar</th>

				</tr>

				<?php

				while ($row=mysqli_fetch_array($query)){

					$id_producto=$row['id_producto'];

					$codigo_producto=$row['codigo_producto'];

					$nombre_producto=$row['nombre_producto'];

                    $precio_venta=$row["precio_producto"];
                    
                    $cantidad_inventario = $row['cantidad_inventario'];

                    $id_inventario = $row['id_inventario'];

					$precio_venta=number_format($precio_venta,2,'.','');

					?>

					<tr>

						<td><?php echo $codigo_producto; ?></td>

						<td><?php echo $nombre_producto; ?></td>

						<td class='col-xs-1'>

						<div class="pull-right">

						    <input type="number" class="form-control" style="text-align:right" 
                                id="cantidad_<?php echo $id_producto; ?>"  value="1" min="1" max="<?php echo $cantidad_inventario; ?>">
                            <input type="hidden" id="cantidad_inventario_<?php echo $id_producto; ?>" value="<?php echo $cantidad_inventario; ?>">
                            <input type="hidden" id="id_inventario_<?php echo $id_producto; ?>" value="<?php echo $id_inventario; ?>">

						</div></td>

						<td class='col-xs-2'><div class="pull-right">

						<input readonly type="text" class="form-control" style="text-align:right" id="precio_venta_<?php echo $id_producto; ?>"  value="<?php echo $precio_venta;?>" >

						</div></td>

						<td class='text-center'><a class='btn btn-info'href="#" onclick="agregar('<?php echo $id_producto ?>')"><i class="glyphicon glyphicon-plus"></i></a></td>

					</tr>

					<?php

				}

				?>

				<tr>

					<td colspan=5><span class="pull-right">

					<?php

					 echo paginate($reload, $page, $total_pages, $adjacents);

					?></span></td>

				</tr>

			  </table>

			</div>

			<?php

		}

	}



?>