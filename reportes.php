<?php
	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: login.php");
		exit;
        }

	/* Connect To Database*/
	require_once ("config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("config/conexion.php");//Contiene funcion que conecta a la base de datos
	$active_facturas="";
	$active_productos="";
	$active_clientes="";
	$active_usuarios="";
	$active_reportes="active";
	$title="Reportes | SGB";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php include("head.php");?>
  </head>
  <body>
 	<?php
	include("navbar.php");
	?> 
    <div class="container">
		<div class="panel panel-info">
		<div class="panel-heading">
			<div class="btn-group pull-right">
				<button type='button' class="btn btn-info" id="exportar"
				data-toggle="tooltip" data-placement="top" title="Exportar excel">
					<span class="glyphicon glyphicon-cloud-download" ></span>
				</button>
			</div>
			<h4><i class='glyphicon glyphicon-paste'></i> Consulta de Reportes</h4>
		</div>			
			<div class="panel-body">
			<?php
			/*include("modal/registro_usuarios.php");
			include("modal/editar_usuarios.php");
			include("modal/cambiar_password.php");*/
			?>
			<form class="form-horizontal" role="form" style="margin-bottom: 25px;">
				
				<div class="form-group row">
					<label for="q" class="col-md-3 control-label">Seleccione el Tipo de Reporte:</label>
					<div class="col-md-4">
                        <select class="form-control" id="select_reporte">
					        <option value="">-- Selecciona tipo de reporte --</option>
							<option value="cierre">Cierre de Caja</option>
					        <option value="cliente">Ventas Agrupadas por Cliente</option>
							<option value="detalle">Ventas Detalladas por Cliente</option>
					        <option value="producto">Productos Vendidos</option> 
				        </select>
					</div>
							
							
							
				<div class="col-md-3">
					<button type="button" class="btn btn-default" onclick='load(1);' style="display: none;">
					    <span class="glyphicon glyphicon-search" ></span> Buscar</button>
						<span id="loader"></span>
					</div>
				</div>

                <div class="for-group row" id="form_busq_producto">
                    <div class="col-md-4">
                        <label for="q" class="control-label" style="display: inline-block; width: 20%;">Producto:</label>
                        <input type="text" class="form-control input-sm" style="display: inline-block; width: 75%;" id="nombre_producto" placeholder="Selecciona un producto">
                        <input id="id_producto" type='hidden' name="id producto">                        
                    </div>
                </div>

                <div class="for-group row" id="form_busq_cliente">
                    <div class="col-md-4">
                        <label for="q" class="control-label" style="display: inline-block; width: 20%;">Cliente:</label>
                        <input type="text" class="form-control input-sm" style="display: inline-block; width: 75%;" id="nombre_cliente" placeholder="Selecciona un cliente">
					    <input id="id_cliente" type='hidden' name="id_cliente">
                    </div>
                </div>

				<div class="for-group row" id="form_busq_fechas">
                    <div class="col-md-4">
                        <label for="q" class="control-label" style="display: inline-block; width: 20%;">Desde:</label>
                        <input type="date" class="form-control input-sm" style="display: inline-block; width: 75%;" id="desde" placeholder="Desde">
						<input type="hidden" id="inicio">
                    </div>

                    <div class="col-md-4">
                    	<label for="q" class="control-label" style="display: inline-block; width: 20%;">Hasta:</label>
                        <input type="date" class="form-control input-sm" style="display: inline-block; width: 75%;" id="hasta" placeholder="Hasta">
						<input type="hidden" id="fin">
                    </div>
                </div>


			</form>
				<div id="resultados"></div><!-- Carga los datos ajax -->
				<div class='outer_div'></div><!-- Carga los datos ajax -->
						
			</div>
		</div>

	</div>
	<hr>
	<?php
	include("footer.php");
	?>
	<script type="text/javascript" src="js/reportes.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  </body>
</html>