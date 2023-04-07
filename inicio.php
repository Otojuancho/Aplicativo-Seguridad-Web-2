<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Home</title>
  <link rel="stylesheet" href="libs/css/contenedor.css?v=<?php echo(rand()); ?>" />
</head>
<?php

    require_once('libs/php/funciones.php');
    require_once('libs/php/db_tools.php');
    require_once('htdocs/email/index.php');
    LimpiarEntradas();
    IniciarSesionSegura();
    //session_start();
    try {
      if (isset($_SESSION["loguser"])) {
        $conn = ConexionDB();
        if ($conn != null) {
            $foto = Foto($conn, $_SESSION["loguser"]);
            $roles = Roles($conn, $_SESSION["loguser"]);
            
        }
      }
      else
      {
          header("Location:index.php");
          exit();
      }
      if (isset($_POST['anticsrf']) && $_POST['anticsrf'] == $_SESSION['anticsrf']) {
        if (isset($_POST['btnsalir']) && $_POST['btnsalir'] == 'salir') {
            cerrarsesion();
            exit();
        }
        if (isset($_POST["btnbuscar"]) && $_POST["btnbuscar"] == 'Buscar') {
          
          if (isset($_POST["txtfiltro"])) {
            $conn = ConexionDB();
            $personas = BusacarPersonasDB($conn,$_POST["txtfiltro"]);
          }
        }
        if (isset($_POST["btnextracto"]) && $_POST["btnextracto"] == 'Generar extracto  mensual') {
          
          $conn = ConexionDB();
          $correo = correo($conn,$_SESSION["loguser"]);
          $listextracto = ListaextractoDB($conn,$_SESSION["loguser"]);
          $listahtml = ' ';
          echo 'aqui';
          foreach ($listextracto as $key => $value) {
            $listahtml .= '<tr>
            <td align="center">'.$value["Fecha_transaccion"]->format('Y-m-d H:i:s') . "\n".'</td>
            <td align="center">'.$value["Tipo_movimiento"].'</td>
            <td align="center">'.$value["cant"].'</td>
            </tr>
            ';
          }
          
          $resultadoc = enviarmailextracto($correo, $listahtml);
          if ($resultadoc = true) {
            echo '<script language="javascript">
                  alert("extracto enviado por correo")
                  window.location.href="inicio.php";
                  </script>';
            exit();
          }
          else{
            echo '<script language="javascript">
                  alert("fallo al enviar el extracto, intentelo mas tarde")
                  window.location.href="inicio.php";
                  </script>';
            exit();
          }
        }
        if (isset($_POST["btnreporte"]) && $_POST["btnreporte"] == 'Generar Reporte mensual') {
          
          $conn = ConexionDB();
          $correo = correo($conn,$_SESSION["loguser"]);
          $listreporte = ListaReporteDB($conn,$_SESSION["loguser"]);
          $listahtmlr = ' ';
          echo 'aqui';
          foreach ($listreporte as $key => $value) {
            $listahtmlr .= '<tr>
            <td align="center">'.$value["Fecha_transaccion"]->format('Y-m-d H:i:s') . "\n".'</td>
            <td align="center">'.$value["Tipo_movimiento"].'</td>
            <td align="center">'.$value["cant"].'</td>
            </tr>
            ';
          }
          
          $resultador = enviarmailreporte($correo, $listahtmlr);
          if ($resultador == true) {
            echo '<script language="javascript">
                  alert("Reporte enviado por correo")
                  window.location.href="inicio.php";
                  </script>';
            exit();
          }
          else{
            echo '<script language="javascript">
                  alert("fallo al enviar el reporte, intentelo mas tarde")
                  window.location.href="inicio.php";
                  </script>';
            exit();
          }

        }
      }
    } catch (\Throwable $th) {
      //throw $th;
    }
    $anticsrf = rand(1000, 9999);
    $_SESSION['anticsrf'] = $anticsrf;
?>
<body>

  <header>
    <div class="menu">
      <nav>
          <ul>
            <li><a href="inicio.php">Inicio</a></li>
            <!--<li><img height="50" src="uploaded_files/images/<?php   ?>"></li>-->
            <li style="color:rgb(255, 255, 255);">Bienvenido, <strong> <?php echo $foto['nombre'];?></strong></li>
              <?php 
                try {
                  foreach ($roles as $key => $value) {
                    if ($value['Id_rol'] == 1 ) {
                      $Saldo = Saldo($conn, $_SESSION["loguser"]);
                      echo'<li style="color:rgb(255, 255, 255);">Saldo: $<strong>'.$Saldo['Saldo_disponible'].'</strong></li>';
                    }
                  }
                } catch (\Throwable $th) {
                  //throw $th;
                }
              ?>
            <li style="color:rgb(255, 255, 255);">
                <form method="POST">
                    <input type="hidden" name="anticsrf" value="<?php echo $anticsrf; ?>">
                    <input class="boton" type="submit" name="btnsalir" value="salir">
                </form>
            </li>
          </ul>
      </nav>
    </div>
  </header>
<br><br>
<div >
  <?php
    try {
      foreach ($roles as $key => $value) {
        if ($value['Id_rol'] == 2 ) {
          echo'
          <a class="boton" href="crearcuentahabitante.php" >Crear Cuentahabiente</a>
          <br>
          <br>
          <br>
          <form action="" method="post">
            <input type="hidden" name="anticsrf" id="anticsrf" value="'.$anticsrf.'">
            <input class="boton" name="btnreporte" type="submit"  id="btnreporte " value="Generar Reporte mensual" />
          </form>
          ';
        }
        if ($value['Id_rol'] == 1 ) {
          echo'
          <a class="boton" href="movimientos.php" >Hacer un movimiento</a>
          <a class="boton" href="perfil.php" >Datos de la cuenta</a>
          <a class="boton" href="sucursales.php" >Sucursales</a>
          <br>
          <br>
          <br>
          <form action="" method="post">
            <input type="hidden" name="anticsrf" id="anticsrf" value="'.$anticsrf.'">
            <input class="boton" name="btnextracto" type="submit"  id="btnextracto " value="Generar extracto  mensual" />
          </form>
          ';
        }
      }
    } catch (\Throwable $th) {
      //throw $th;
    }
  ?>
</div>
<br><br>

<div>
  <?php
    try {
      foreach ($roles as $key => $value) {
        if ($value['Id_rol'] == 2 ) {
          echo'
          <h1>Buscar Cuenta habiente</h1>
          <form class="formp" action="" method="post">
            <div class="form-element">
                <label for="txtfiltro">Nombre:</label>
                <input class="inputp" type="text" name="txtfiltro" id="txtfiltro" pattern="[A-Za-z ]{1,}">
            </div>
            <input type="hidden" name="anticsrf" id="anticsrf" value="'.$anticsrf.'">
            <input class="boton" name="btnbuscar" type="submit"  id="btnbuscar" value="Buscar" />
            
          </form>
          ';
        }
      }
    } catch (\Throwable $th) {
      //throw $th;
    }
  ?>
</div>
  <div id="personsearch">
    <?php
    
      if (isset($personas)) {
        foreach ($personas as $key => $value) {
          
            echo'
                <form class="formp" action="perfiles.php" method="post">
                <input type="hidden" value="'.$value["Id_persona"].'" name="txtidpersona" id="txtidpersona">
                <table style="text-align:center;">
                    <tr>
                      <td>'.$value["Nombre_comp"].'</td>
                    </tr>
                </table>
                <input type="hidden" name="anticsrf" value="'.$anticsrf.'">
                <input class="boton" name="btnver" type="submit"  id="btnver" value="VER" />';
            echo '
                </form>
            ';
        }
      }
    ?>
  </div>
  
</body>
</html>