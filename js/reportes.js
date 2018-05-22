
$(document).ready(function(){
    load(1);
    init();
    localStorage.setItem('exportar', 0);
    localStorage.setItem('tipo_exportar', '');
    localStorage.setItem('metodo_exportar', 'all');
});

function init() {
    $( "#form_busq_cliente" ).hide( "slow" );
    $( "#form_busq_producto" ).hide( "slow" );
    $( "#form_busq_fechas" ).hide( "slow" );

}

//MUESTRO OCULTO OPCIONES
function showGetCliente() {
    $( "#form_busq_cliente" ).show( "slow" );
    $( "#form_busq_producto" ).hide( "slow" );
    $( "#form_busq_fechas" ).show( "slow" );
}

function showGetProducto() {
    $( "#form_busq_cliente" ).hide( "slow" );
    $( "#form_busq_producto" ).show( "slow" );
    $( "#form_busq_fechas" ).show( "slow" );
}

function showGetDetalle() {
    $( "#form_busq_cliente" ).show( "slow" );
    $( "#form_busq_producto" ).hide( "slow" );
    $( "#form_busq_fechas" ).show( "slow" );
}

function showGetCierre() {
    $( "#form_busq_cliente" ).hide( "slow" );
    $( "#form_busq_producto" ).hide( "slow" );
    $( "#form_busq_fechas" ).show( "slow" );
}

//SETEO VARIABLES SEGUN REPORTE
$( "#select_reporte" ).change(function() {
    $('#nombre_cliente').val(''); //BLANQUEO CAMPO NOMBRE CADA QUE ESCOJO REPORTE
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
        } else if (opcion == "cierre") {
            localStorage.setItem('tipo_exportar', 'cierre');
            showGetCierre();

        } else {
            //init();
            showGetDetalle();
            localStorage.setItem('tipo_exportar', 'detalle');
            getDetalle(0);
        }
    });    
});

//SETEO VARIABLES FECHAS Y EJECUTO QUERY PARA LLENAR TABLA DE DATOS
$('#desde').change(function(){
    var fecha = ''+this.value+'';
    $('#inicio').val(fecha);
});

$('#hasta').change(function(){
    var fecha = ''+this.value+'';
    $('#fin').val(fecha);
    var valida_repo = localStorage.getItem('tipo_exportar');
    if (valida_repo == 'cliente'){
        getClientes(1);
    } else if (valida_repo == 'producto'){
        getProductos(1);
    } else if (valida_repo == 'cierre'){
        getCierre(0);    
    } else {
        getDetalle(1);
    }
});

//EXPORTO TABLA QUE SE MUESTRA EN PANTALLA
$( "#exportar" ).click(function() {
    var valida_tipo = localStorage.getItem('tipo_exportar');

    $("#nombre_reporte").val(valida_tipo);
    if (valida_tipo == 'cliente'){
        var formulario = $("#Exportar_Clientes").eq(0).clone();
    } else if(valida_tipo == 'producto'){
        var formulario = $("#Exportar_Productos").eq(0).clone();
    } else {
        var formulario = ''; 
        exportDetalles();
    }
    
    if (formulario != ''){
        $("#datos_a_enviar").val( $("<div>").append(formulario).html());
        $("#FormularioExportacion").submit();    
    }
    
    limpiarPantalla();

});


//FUNCIO DE AUTOCOMPLETAR NOMBRE Y PRODUCTO SEGUN REPORTE
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
            
            //PARA CLIENTES Y DETALLE CLIENTES
            var valida_repo = localStorage.getItem('tipo_exportar');
            if (valida_repo == 'cliente'){
                getClientes(1);
            } else {
                getDetalle(1);
            }
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

//CARGO TABLA CON DATOS DE CLIENTE
function getClientes(tipo) {
    localStorage.setItem('tipo_exportar', 'cliente');
    var url = '';
    var id_cliente= $("#id_cliente").val();
    var inicio= $("#inicio").val();
    var fin= $("#fin").val();
    if(inicio == '') {
        //inicio = new Date().toJSON().slice(0,10);
        inicio = '2018-01-01';
    }
    if(fin == '') {
        //fin = new Date().toJSON().slice(0,10);
        fin = '3000-01-01';
    }

    url = './ajax/reporte_cliente.php?action=ajax&id_cliente='+id_cliente+'&inicio='+inicio+'&fin='+fin;
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

function getDetalle(tipo) {
    localStorage.setItem('tipo_exportar', 'detalle');
    var url = '';
    var id_cliente= $("#id_cliente").val();
    var inicio= $("#inicio").val();
    var fin= $("#fin").val();
    if(inicio == '') {
        //inicio = new Date().toJSON().slice(0,10);
        inicio = '2018-01-01';
    }
    if(fin == '') {
        //fin = new Date().toJSON().slice(0,10);
        fin = '3000-01-01';
    }

    url = './ajax/reporte_detalle.php?action=ajax&id_cliente='+id_cliente+'&inicio='+inicio+'&fin='+fin;
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
    var id_producto= $("#id_producto").val();
    var inicio= $("#inicio").val();
    var fin= $("#fin").val();
    if(inicio == '') {
        //inicio = new Date().toJSON().slice(0,10);
        inicio = '2018-01-01';
    }
    if(fin == '') {
        //fin = new Date().toJSON().slice(0,10);
        fin = '3000-01-01';
    }
     url = './ajax/reporte_producto.php?action=ajax&id_producto='+id_producto+'&inicio='+inicio+'&fin='+fin; 
    
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
    });
                    
     
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

function exportDetalles() {
    var id_cliente= $("#id_cliente").val();
    var inicio= $("#inicio").val();
    var fin= $("#fin").val();
    if(inicio == '') {
        inicio = '2000-01-01';
    }
    if(fin == '') {
        fin = '3000-01-01';
    }
    $("#loader").fadeIn('slow');
    window.location.href = './ajax/excel.php?action=detalle&id_cliente='+id_cliente+'&inicio='+inicio+'&fin='+fin;
}

function getCierre() {
    var inicio= $("#inicio").val();
    var fin= $("#fin").val();
    if(inicio == '') {
        inicio = '2000-01-01';
    }
    if(fin == '') {
        fin = '3000-01-01';
    }
    $("#loader").fadeIn('slow');
    window.open('./pdf/documentos/cierre_caja.php?fecha_ini=' + inicio + '&fecha_fin=' + fin, 'Factura', '', '1024', '768', 'true');
}

function limpiarPantalla(){
    $("#select_reporte").val('');  
    $("#id_cliente").val('');  
    $("#id_producto").val('');  
    init();
    $('#desde').val('');
    $('#hasta').val('');
    $('#nombre_cliente').val('');
    $(".outer_div").html('');
    $('#loader').html('');

}