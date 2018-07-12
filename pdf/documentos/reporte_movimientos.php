<?php

	session_start();

	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {

        header("location: ../../login.php");

		exit;

    }

	/* Connect To Database*/

	include("../../config/db.php");

	include("../../config/conexion.php");

	//Archivo de funciones PHP

    include("../../funciones.php");
    
    if (isset($_GET['all'])) {
        $sql_count=mysqli_query($con,"SELECT li.`id_loginv`, li.`fecha_loginv`, li.`producto_loginv`, 
                    prod.`codigo_producto`, prod.`nombre_producto`, li.`cantidad_loginv`, 
                    li.`tipo_loginv`, li.`motivo_loginv` 
                    FROM `log_inventario` as li 
                    JOIN `products` as prod ON (li.`producto_loginv` = prod.`id_producto`) ");
    } else {
        $fecha_desde = $_GET['desde'];
        $fecha_hasta = $_GET['hasta'];
        $sql_count=mysqli_query($con,"SELECT li.`id_loginv`, li.`fecha_loginv`, li.`producto_loginv`, prod.`codigo_producto`, 
                    prod.`nombre_producto`, li.`cantidad_loginv`, li.`tipo_loginv`, li.`motivo_loginv` 
                    FROM `log_inventario` as li
                    JOIN `products` as prod ON (li.`producto_loginv` = prod.`id_producto`)
                    WHERE li.`fecha_loginv` >= '".$fecha_desde."' AND li.`fecha_loginv` <= '".$fecha_hasta."'");
    }

	$count=mysqli_num_rows($sql_count);



	if ($count==0)



	{



	echo "<script>alert('No existen registros para exportar')</script>";



	echo "<script>window.close();</script>";



	exit;



	}



	

	require_once(dirname(__FILE__).'/../html2pdf.class.php');



    // get the HTML



     ob_start();



    include(dirname('__FILE__').'/res/ver_reporte_movimientos_html.php');



    $content = ob_get_clean();







    try



    {



        // init HTML2PDF



        $html2pdf = new HTML2PDF('P', 'LETTER', 'es', true, 'UTF-8', array(0, 0, 0, 0));



        // display the full page



        $html2pdf->pdf->SetDisplayMode('fullpage');



        // convert



        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));



        // send the PDF



        $html2pdf->Output('Movimientos.pdf');



    }



    catch(HTML2PDF_exception $e) {



        echo $e;



        exit;



    }

?>





