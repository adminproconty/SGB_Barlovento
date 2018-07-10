$(document).ready(function() {

    load(1);

});



function load(page) {

    var q = $("#q").val();

    $("#loader").fadeIn('slow');

    $.ajax({

        url: './ajax/buscar_productos_kardex.php?action=ajax&page=' + page + '&q=' + q,

        beforeSend: function(objeto) {

            $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');

        },

        success: function(data) {

            $(".outer_div").html(data).fadeIn('slow');

            $('#loader').html('');



        }

    })

}



$("#guardar_kardex").submit(function(event) {

    var cantidad_aumenta = $('#cantidad_aumenta').val() * 1;

    var cantidad_disminuye = $('#cantidad_disminuye').val() * 1;

    var motivo = $('#input_motivo').val();

    var id = $('#mod_id_producto').val();

    var cantidad_actual = $('#mod_cantidad').val() * 1;

    var url = '';

    var cantidad = 0;

    if (cantidad_disminuye == '' || motivo == '') {

        cantidad = cantidad_actual + cantidad_aumenta;

        url = 'id=' + id + '&cantidad=' + cantidad + '&cant=' + cantidad_aumenta;

    } else {

        cantidad = cantidad_actual - cantidad_disminuye;

        url = 'id=' + id + '&cantidad=' + cantidad + '&motivo=' + motivo + '&cant=' + cantidad_disminuye;

    }

    $.ajax({

        type: "GET",

        url: "./ajax/modificar_inventario.php?" + url,

        beforeSend: function(objeto) {

            $("#resultados_ajax").html("Mensaje: Cargando...");

        },

        success: function(datos) {

            $("#resultados_ajax").html(datos);

            load(1);

        }

    });

});


function bajar() {

    $('#disminuir').show();

    $('#aumentar').hide();

    $('#motivo').show();

}



function subir() {

    $('#disminuir').hide();

    $('#aumentar').show();

    $('#motivo').hide();

}