
$(document).ready(function(){
    load(1);
    init();
});

function init() {
    $( "#form_busq_cliente" ).hide( "slow" );
    $( "#form_busq_producto" ).hide( "slow" );
}

function showGetCliente() {
    $( "#form_busq_cliente" ).show( "slow" );
    $( "#form_busq_producto" ).hide( "slow" );
}

function showGetProducto() {
    $( "#form_busq_cliente" ).hide( "slow" );
    $( "#form_busq_producto" ).show( "slow" );
}

$( "#select_reporte" ).change(function() {
    var opcion = "";
    $( ".outer_div" ).hide( "slow" );
    $( "select option:selected" ).each(function() {
        opcion = $( this ).val();
        if (opcion == 'cliente'){
            showGetCliente();
        } else if (opcion == 'producto') {
            showGetProducto();
        } else {
            init();
        }
    });    
});

$('#desde_producto').change(function(){
    var fecha = ''+this.value+'';
    $('#inicio_producto').val(fecha);
});

$('#hasta_producto').change(function(){
    var fecha = ''+this.value+'';
    $('#fin_producto').val(fecha);
    getProductos();
});

$('#desde_cliente').change(function(){
    var fecha = ''+this.value+'';
    $('#inicio_cliente').val(fecha);
});

$('#hasta_cliente').change(function(){
    var fecha = ''+this.value+'';
    $('#fin_cliente').val(fecha);
    getClientes();
});

$(function() {
	$("#nombre_cliente").autocomplete({
        source: "./ajax/autocomplete/clientes.php",
        minLength: 2,
        select: function(event, ui) {
            event.preventDefault();
            $('#id_cliente').val(ui.item.id_cliente);
            $('#nombre_cliente').val(ui.item.nombre_cliente);
            $('#tel1').val(ui.item.telefono_cliente);
            $('#mail').val(ui.item.email_cliente);
            $('#saldo_cliente').val(ui.item.saldo_cliente);
            getClientes();
        }
    });
    
    $("#nombre_producto").autocomplete({
        source: "./ajax/autocomplete/productos.php",
        minLength: 2,
        select: function(event, ui) {
            event.preventDefault();
            $('#id_producto').val(ui.item.id_producto);
            $('#codigo_producto').val(ui.item.codigo_producto);
            $('#nombre_producto').val(ui.item.nombre_producto);
            getProductos();
        }
    });	
						
});	

function getClientes() {
    var id_cliente= $("#id_cliente").val();
    var inicio= $("#inicio_cliente").val();
    var fin= $("#fin_cliente").val();
    if(inicio == '') {
        inicio = '2000-01-01';
    }
    if(fin == '') {
        fin = '3000-01-01';
    }
	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/reporte_cliente.php?action=ajax&id_cliente='+id_cliente+'&inicio='+inicio+'&fin='+fin,
		beforeSend: function(objeto){
			$('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
		},
		success:function(data){
			$(".outer_div").html(data).fadeIn('slow');
            $('#loader').html('');
        }
	})
}

function getProductos() {
    var id_producto= $("#id_producto").val();
    var inicio= $("#inicio_producto").val();
    var fin= $("#fin_producto").val();
    if(inicio == '') {
        inicio = '2000-01-01';
    }
    if(fin == '') {
        fin = '3000-01-01';
    }
	$("#loader").fadeIn('slow');
	$.ajax({
		url:'./ajax/reporte_producto.php?action=ajax&id_producto='+id_producto+'&inicio='+inicio+'&fin='+fin,
		beforeSend: function(objeto){
			$('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
		},
		success:function(data){
			$(".outer_div").html(data).fadeIn('slow');
            $('#loader').html('');
        }
	})
}

function load(page){
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
