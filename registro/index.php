<!DOCTYPE html>
  
<?php 
        // error_reporting(E_ALL);
        // ini_set('display_errors', '1');
        session_start();
        include_once ("clases/mysql_connection.php");
        include_once ("clases/mysqlCommand.php");
        include_once ("clases/mssqlCommand.php");
        include_once ("clases/mssql_connection.php");
        include_once ("db_params.php");
        include_once ("correo/correo.php");
        $congreso="9° Congreso de negocios UVP";
?>
<html lang="es">
<head>
    <title><?php echo $congreso; ?></title>
    <style type="text/css">
        body{background-image:url('../css/images/fondo.jpg');
             background-repeat: repeat; 
             background-size: 100%; }    
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    
</head>
<body class="onepage">  
<?php
        $n= $_POST['nombre'];
        $correoper= $_POST['correoper'];
        $_SESSION['correoper']=$_POST['correoper'];
        $i= $_POST['instituto'];
        $m= $_POST['matricula'];
        $no_poliza=$_POST['folio'];
        //SQLSERVER
        $msSql= new  mssqlCnx($pc_server_address,$pc_dbuser,$pc_dbpasswd,$pc_db);
        $conSQl=$msSql->Open();
        //MYSQL
        $con= new ConexionMysql($server_address,$dbuser,$dbpasswd,$db);

        //var_dump($_POST);

        $matric_var="";
        $precio=0;

        //validar generar gafete alumno
        if(strlen($m)==7)
        {
            $mat="TAX_ID";
        }
        if(strlen($m)==10)
        {
            $mat="PEOPLE_ID";
        }
               $sql = "SELECT DISTINCT c.CHARGECREDITNUMBER credito,
                c.RECEIPT_NUMBER,
                c.PEOPLE_ORG_ID id, TAX_ID matricula,
                c.ACADEMIC_YEAR anio,
                c.ACADEMIC_TERM periodo, 
                c.ACADEMIC_SESSION plantel,
                c.AMOUNT,
                c3.PAID_AMOUNT,
                c3.BALANCE_AMOUNT deuda,
                c2.ChargeAppliedTo,
                c2.ChargeCreditSource,
                c3.CHARGECREDITNUMBER,
                c3.CHARGE_CREDIT_CODE codigo
                FROM PEOPLE p LEFT JOIN
            CHARGECREDIT c ON p.PEOPLE_ID= c.PEOPLE_ORG_ID
                LEFT JOIN ChargeCreditApplication c2
                ON c.CHARGECREDITNUMBER = c2.ChargeCreditSource
                LEFT JOIN CHARGECREDIT c3
                ON c2.ChargeAppliedTo = c3.CHARGECREDITNUMBER
                WHERE c.RECEIPT_NUMBER = '$no_poliza'
                AND c3.VOID_FLAG = 'N'
                AND c3.CHARGE_CREDIT_CODE='OPEVCONGRE'
                AND $mat ='$m';";
        $cmd= new mssqlCommand($sql,$conSQl);
        $res=$cmd->ExecuteReader();
        $pago=number_format($res[0]["PAID_AMOUNT"]);
        if ($res[0]["PAID_AMOUNT"] =="850.000000" || $res[0]["PAID_AMOUNT"] == "950.000000")
        {
            $sql = "select * from registro where  correo = '$correoper'";
            $cmd = new ComandosMysql($sql, $con->open());
            $datos = $cmd->ExecuteReader(true);
            if ($datos[0]['nombre'] != "")
                $msg= "<div style='color: #71365f;'> <h1>$congreso</h1><h2><b>Usted ya se encuentra registrado</b><br><center>Bienvenido ".$datos[0]['nombre']."</h2></center><br><br><h3>Por favor imprima su gafete para agilizar su entrada el día del evento.</h3><br><br> (En caso de no ver su gafete, active la opción de abrir ventanas emergentes en su navegador y envie su registro de nuevo, o en su defecto revise su e-mail)<br></div>";
            else{
                $query="insert into registro values('$n', '$correoper','$i', '$m', '$congreso', $no_poliza)";
                //echo($q."<hr>");
                $cmd = new ComandosMysql($query, $con->open());
                    $datos = $cmd->ExecuteNonQuery(true);
                    //echo $cmd->error_message;
                $msg= "<div style='color: #71365f;'> <h1>$congreso</h1><h3>Su pago fue de $ $pago.00</h3><h2><b>Registro realizado con &eacute;xito</b><br><center>Bienvenido ".$n."</center><br></h2><br><h3>Por favor imprima su gafete para agilizar su entrada el día del evento.</h3><br><br> (En caso de no ver su gafete, active la opción de abrir ventanas emergentes en su navegador y envie su registro de nuevo, o en su defecto revise su e-mail)<br></div>";
                $correo =  new correo();
                    $mensaje=$correo->Enviar($correoper,$n,$i,$m);
            }
        }

        else
            {//caso contrario si no encuentra folio
            $msg="<div style='color: #DC2822;'><h1>$congreso</h1><h2>Usted no ha pagado el congreso o su número de recibo es incorrecto, verifique los datos nuevamente.<br><br> Por favor espere 10 segundos para volver a intentarlo.</h2></div>";
                ?>
                    <meta http-equiv="refresh" content="10;URL=https://congresonegocios.uvp.mx/" />
                <?php
            }

if ($res[0]["PAID_AMOUNT"] =="850.000000" || $res[0]["PAID_AMOUNT"] == "950.000000")
{
?>

<body style="background-attachment: fixed" onLoad="window.open('gafeteqr.php?a1=<?php echo $n;?>&a2=<?php echo $i;?>&a3=<?php echo $m;?>','','width=450,height=500 0')">

<?php }
else {?>
    <body  style="background-attachment: fixed">
<?php }
?>

<div id="body-wrapper" >
<center>
<table width="500" border="0" align="center" cellpadding="5" cellspacing="2">

  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="justify"><?=$msg?></td>
  </tr>
</table>
<br><br><br><br><br><br><br><br>    
        
</body>
</html>