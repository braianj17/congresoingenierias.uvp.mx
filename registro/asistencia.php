<!DOCTYPE html>
  
<?php 
       // error_reporting(E_ALL);
       // ini_set('display_errors', '1');
       
        include_once ("clases/mysql_connection.php");
        include_once ("clases/mysqlCommand.php");
        include_once ("db_params.php");
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

<div id="body-wrapper" >
<center>
  <h1><?php echo $congreso; ?></h1><br><br>
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="2">

  <tr>
    <td align="center">
      <form action="" method="POST" accept-charset="utf-8">
          <input type="text" autofocus name="matricula" id="matricula">
      </form>  
    </td>
  </tr>
  <tr>
    <td align="center">
      <?
          if(isset($_POST['matricula'])){
              $m= $_POST['matricula'];
              //MYSQL
              $con= new ConexionMysql($server_address,$dbuser,$dbpasswd,$db);
              $sql = "select * from registro where  matricula = '$m'";
              $cmd = new ComandosMysql($sql, $con->open());
              $datos = $cmd->ExecuteReader(true);
              if ($datos[0]['nombre'] != ""){
                $nombre=$datos[0]['nombre'];
                $msg=strtoupper($m." ".$nombre);
                $fecha = date('Y-m-d h:i:s');
                $query="insert into asistencia values('$m','$fecha','$nombre')";
                $cmd = new ComandosMysql($query, $con->open());
                $response = $cmd->ExecuteNonQuery(true);
              }
              else{
                  $msg="Matricula, No existe en el registro.";
              }

              print "<h1><b>$msg</b></h1>";
       }
     
      ?>
        
    </td>
  </tr>
</table>
<br><br><br><br><br><br><br><br>    
</center>
</div>        
</body>
</html>