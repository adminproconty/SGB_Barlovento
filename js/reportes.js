
$(document).ready(function(){
    load(1);
    init();
    localStorage.setItem('exportar', 0);
    localStorage.setItem('tipo_exportar', 'nada');
    localStorage.setItem('metodo_exportar', 'all');
});

function init() {
    $( "#form_busq_cliente" ).hide( "slow" );
    $( "#form_busq_producto" ).hide( "slow" );
    getAll();
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
    localStorage.setItem('exportar', 0);
    $( "select option:selected" ).each(function() {
        opcion = $( this ).val();
        if (opcion == 'cliente'){
            showGetCliente();
            localStorage.setItem('tipo_exportar', 'cliente');
            getClientes(0);
        } else if (opcion == 'producto') {
            showGetProducto();
            localStorage.setItem('tipo_exportar', 'producto');
            getProductos(0);
        } else {
            init();
            localStorage.setItem('tipo_exportar', 'nada');
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
    getProductos(1);
});

$('#desde_cliente').change(function(){
    var fecha = ''+this.value+'';
    $('#inicio_cliente').val(fecha);
});

$('#hasta_cliente').change(function(){
    var fecha = ''+this.value+'';
    $('#fin_cliente').val(fecha);
    getClientes(1);
});

$( "#exportar" ).click(function() {
    var cantidad = localStorage.getItem('exportar') * 1;
    if(cantidad > 0) {
        var tipo_exportar = localStorage.getItem('tipo_exportar');
        if(tipo_exportar == 'cliente') {
            exportClientes();
        }else if(tipo_exportar == 'producto') {
            exportarProductos();
        }else{
            exportarAll();
        }
    }else{
        alert('No hay datos para exportar');        
    }
});

function exportClientes() {
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
    var metodo = localStorage.getItem('metodo_exportar');
    if(metodo == 'all') {
        window.location.href = './ajax/excel.php?action=cliente&metodo=all';
    }else if(metodo == 'byid'){
        window.location.href = './ajax/excel.php?action=cliente&metodo=byid&id_cliente='+id_cliente+'&inicio='+inicio+'&fin='+fin;
    }
}

function exportarProductos() {
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
    var metodo = localStorage.getItem('metodo_exportar');
    if(metodo == 'all') {
        window.location.href = './ajax/excel.php?action=producto&metodo=all';
    }else if(metodo == 'byid'){
        window.location.href = './ajax/excel.php?action=producto&metodo=byid&id_producto='+id_producto+'&inicio='+inicio+'&fin='+fin;
    }
}

function exportarAll() {
    window.location.href = './ajax/excel.php?action=all';
}

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
            getClientes(1);
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
            getProductos(1);
        }
    });	
						
});	

function getClientes(tipo) {
    var url = '';
    if(tipo == 0){
        url = './ajax/reporte_cliente.php?action=ajax&tipo=all';
        localStorage.setItem('metodo_exportar', 'all');
    }else{
        var id_cliente= $("#id_cliente").val();
        var inicio= $("#inicio_cliente").val();
        var fin= $("#fin_cliente").val();
        if(inicio == '') {
            inicio = '2000-01-01';
        }
        if(fin == '') {
            fin = '3000-01-01';
        }
        url = './ajax/reporte_cliente.php?action=ajax&tipo=byid&id_cliente='+id_cliente+'&inicio='+inicio+'&fin='+fin;
        localStorage.setItem('metodo_exportar', 'byid');
    }    
	$("#loader").fadeIn('slow');
	$.ajax({
		url: url,
		beforeSend: function(objeto){
			$('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
		},
		success:function(data){
			$(".outer_div").html(data).fadeIn('slow');
            $('#loader').html('');
        }
	})
}

function getProductos(tipo) {
    var url = '';
    if(tipo == 0){
        url = './ajax/reporte_producto.php?action=ajax&tipo=all';
        localStorage.setItem('metodo_exportar', 'all');
    }else{
        var id_producto= $("#id_producto").val();
        var inicio= $("#inicio_producto").val();
        var fin= $("#fin_producto").val();
        if(inicio == '') {
            inicio = '2000-01-01';
        }
        if(fin == '') {
            fin = '3000-01-01';
        }
        url = './ajax/reporte_producto.php?action=ajax&tipo=byid&id_producto='+id_producto+'&inicio='+inicio+'&fin='+fin;
        localStorage.setItem('metodo_exportar', 'byid');
    }    
	$("#loader").fadeIn('slow');
	$.ajax({
		url: url,
		beforeSend: function(objeto){
			$('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
		},
		success:function(data){
			$(".outer_div").html(data).fadeIn('slow');
            $('#loader').html('');
        }
	})
}

function getAll() {
    var url = './ajax/report_all.php?action=ajax';
	$("#loader").fadeIn('slow');
	$.ajax({
		url: url,
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
