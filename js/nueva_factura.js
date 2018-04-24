
		$(document).ready(function(){
			load(1);
			init(1);
		});

		function load(page){
			init(page);
			var q= $("#q").val();
			$("#loader").fadeIn('slow');
			$.ajax({
				url:'./ajax/productos_factura.php?action=ajax&page='+page+'&q='+q,
				 beforeSend: function(objeto){
				 $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
			  },
				success:function(data){
					$(".outer_div").html(data).fadeIn('slow');
					$('#loader').html('');
				}
			})			
		}

		function init(page) {
			var q= $("#search").val();
			$("#loader").fadeIn('slow');
			$.ajax({
				url:'./ajax/consultar_productos.php?action=ajax&page='+page+'&q='+q,
				 beforeSend: function(objeto){
				 $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
			  },
				success:function(data){
					$(".productos").html(data).fadeIn('slow');
					$('#loader').html('');
				}
			})		
		}

	function agregar (id)
		{
			var precio_venta=document.getElementById('precio_venta_'+id).value;
			var cantidad=document.getElementById('cantidad_'+id).value;
			//Inicia validacion
			if (isNaN(cantidad))
			{
			alert('Esto no es un numero');
			document.getElementById('cantidad_'+id).focus();
			return false;
			}
			if (isNaN(precio_venta))
			{
			alert('Esto no es un numero');
			document.getElementById('precio_venta_'+id).focus();
			return false;
			}
			//Fin validacion
			
			$.ajax({
						type: "POST",
						url: "./ajax/agregar_facturacion.php",
						data: "id="+id+"&precio_venta="+precio_venta+"&cantidad="+cantidad,
						 beforeSend: function(objeto){
							$("#resultados").html("Mensaje: Cargando...");
						  },
						success: function(datos){
							$("#resultados").html(datos);
							var total_factura = localStorage.getItem('total_factura');
							var saldo = $("#saldo_cliente").val() * 1;
							if (total_factura > saldo) {
								alert('Saldo insuficiente para la compra');
								document.getElementById("btn-comprar").disabled = true;
							} else {
								document.getElementById("btn-comprar").disabled = false;
							}				
						}
					});					
			
		}
		
			function eliminar (id)
		{
			
			$.ajax({
        type: "GET",
        url: "./ajax/agregar_facturacion.php",
        data: "id="+id,
		 beforeSend: function(objeto){
			$("#resultados").html("Mensaje: Cargando...");
		  },
        success: function(datos){
					$("#resultados").html(datos);
					var total_factura = localStorage.getItem('total_factura');
					var saldo = $("#saldo_cliente").val() * 1;
					if (total_factura > saldo) {
						alert('Saldo insuficiente para la compra');
						document.getElementById("btn-comprar").disabled = true;
					}	else {
						document.getElementById("btn-comprar").disabled = false;
					}
				}
			});

		}
		
		$("#datos_factura").submit(function(event){
		  		
			var cantidad_productos = localStorage.getItem('cantidad_productos');
			if (cantidad_productos < 1 )	{
				alert('Debe seleccionar productos para proceder la compra');
				event.preventDefault();
			}
		  
	 	});
		
		$( "#guardar_cliente" ).submit(function( event ) {
		  $('#guardar_datos').attr("disabled", true);
		  
		 var parametros = $(this).serialize();
			 $.ajax({
					type: "POST",
					url: "ajax/nuevo_cliente.php",
					data: parametros,
					 beforeSend: function(objeto){
						$("#resultados_ajax").html("Mensaje: Cargando...");
					  },
					success: function(datos){
					$("#resultados_ajax").html(datos);
					$('#guardar_datos').attr("disabled", false);
					load(1);
				  }
			});
		  event.preventDefault();
		})
		
		$( "#guardar_producto" ).submit(function( event ) {
		  $('#guardar_datos').attr("disabled", true);
		  
		 var parametros = $(this).serialize();
			 $.ajax({
					type: "POST",
					url: "ajax/nuevo_producto.php",
					data: parametros,
					 beforeSend: function(objeto){
						$("#resultados_ajax_productos").html("Mensaje: Cargando...");
					  },
					success: function(datos){
					$("#resultados_ajax_productos").html(datos);
					$('#guardar_datos').attr("disabled", false);
					load(1);
				  }
			});
		  event.preventDefault();
		})

		$('#getproductos').click(function() {
			load(1);
		});

		$('#addproducto').click(function() {
			load(1);
		});
