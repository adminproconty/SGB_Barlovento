<style type="text/css">



<!--



table { vertical-align: top; }



tr    { vertical-align: top; }



td    { vertical-align: top; }



.midnight-blue{



	background:#2c3e50;



	padding: 4px 4px 4px;



	color:white;



	font-weight:bold;



	font-size:12px;



}



.silver{



	background:white;



	padding: 3px 60px 3px;



}



.gold{



background:white;



padding: 3px 4px 3px;



}



.clouds{



	background:#ecf0f1;



	padding: 3px 4px 3px;



}



.border-top{



	border-top: solid 1px #bdc3c7;



	



}



.border-left{



	border-left: solid 1px #bdc3c7;



}



.border-right{



	border-right: solid 1px #bdc3c7;



}



.border-bottom{



	border-bottom: solid 1px #bdc3c7;



}



table.page_footer {width: 100%; border: none; background-color: white; padding: 2mm;border-collapse:collapse; border: none;}



}



-->



</style>



<page backtop="15mm" backbottom="15mm" backleft="15mm" backright="15mm" style="font-size: 12pt; font-family: arial" >



        <page_footer>



        <table class="page_footer">



            <tr>







                <td style="width: 50%; text-align: left">



                    P&aacute;gina [[page_cu]]/[[page_nb]]



                </td>



                <td style="width: 50%; text-align: right">



                    &copy; <?php echo "www.proconty.com | "; echo  $anio=date('Y'); ?>



                </td>



            </tr>



        </table>



    </page_footer>



	<?php include("encabezado_factura.php");?>



    <br>



    

    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 10pt;">

        <tr>

            <th style="width: 100%;text-align:center" class='midnight-blue'>REPORTE MOVIMIENTOS</th>

        </tr>

    </table>    



    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 8pt;">

        <?php

        $vendedor = '';

        if (isset($_GET['all'])) {
        $sql=mysqli_query($con,"SELECT li.`id_loginv`, li.`fecha_loginv`, li.`producto_loginv`, 
                    prod.`codigo_producto`, prod.`nombre_producto`, li.`cantidad_loginv`, 
                    li.`tipo_loginv`, li.`motivo_loginv` 
                    FROM `log_inventario` as li 
                    JOIN `products` as prod ON (li.`producto_loginv` = prod.`id_producto`) ");
        } else {
        $fecha_desde = $_GET['desde'];
        $fecha_hasta = $_GET['hasta'];
        $sql=mysqli_query($con,"SELECT li.`id_loginv`, li.`fecha_loginv`, li.`producto_loginv`, prod.`codigo_producto`, 
                    prod.`nombre_producto`, li.`cantidad_loginv`, li.`tipo_loginv`, li.`motivo_loginv` 
                    FROM `log_inventario` as li
                    JOIN `products` as prod ON (li.`producto_loginv` = prod.`id_producto`)
                    WHERE li.`fecha_loginv` >= '".$fecha_desde."' AND li.`fecha_loginv` <= '".$fecha_hasta."'");
        }

    

    ?>



        <tr>

            <th class='clouds' style="text-align: center">Fecha</th>
            <th class='clouds' style="text-align: center">Cod Producto</th>
            <th class='clouds' style=" text-align: center">Nombre Producto</th>
            <th class='clouds' style="text-align: center">Cantidad</th>
            <th class='clouds' style="text-align: center">Tipo Movimiento</th>
            <th class='clouds' style="text-align: center">Motivo</th>

        </tr>



    <?php

    

    

    

    while ($row=mysqli_fetch_array($sql))



	{



    ?>



        <tr>

            

            <td class='gold' style="width: 15%; text-align: center"><?php echo date("d/m/Y", strtotime($row['fecha_loginv']))?></td>
            <td class='gold' style="width: 15%; text-align: center"><?php echo $row['codigo_producto'];?></td>
            <td class='gold' style="width: 35%; text-align: center"><?php echo $row['nombre_producto'];?></td>
            <td class='gold' style="width: 5%; text-align: center"><?php echo $row['cantidad_loginv'];?></td>
            <td class='gold' style="width: 15%; text-align: center"><?php echo $row['tipo_loginv'];?></td>
            <td class='gold' style="width: 15%; text-align: center"><?php echo $row['motivo_loginv'];?></td>

            

        </tr>



    <?php

	}



    ?>

    </table>





	<br>

    <br>

    <br>

    <br>



    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 10pt;">

        <tr>

            <th class='silver' style="width: 50%; text-align: left; font-size:11pt">Jefe de Contrato</th>

            <th class='silver' style="width: 40%; text-align: right; font-size:11pt">Recibe</th>

        </tr>

    </table>





</page>