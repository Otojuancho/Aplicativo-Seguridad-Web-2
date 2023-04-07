<?php
    require_once('libs/php/funciones.php');
    require_once('libs/php/db_tools.php');
    LimpiarEntradas();
    IniciarSesionSegura();
    //session_start();
    try {
        if (isset($_SESSION["loguser"])) {
            $conn = ConexionDB();
            if ($conn != null) {
                $foto = Foto($conn, $_SESSION["loguser"]);
                $roles = Roles($conn, $_SESSION["loguser"]);
                foreach ($roles as $key => $value) {
                    if ($value['Id_rol'] != 1 ) {
                        header('Location:index.php');
                    }
                }
            }
            if (isset($_POST['anticsrf']) && $_POST['anticsrf'] == $_SESSION['anticsrf']) {
                if (isset($_POST['btnsalir']) && $_POST['btnsalir'] == 'salir') {
                    cerrarsesion();
                    exit();
                }
                if (isset($_POST['btnConsignar']) && $_POST['btnConsignar'] == 'Consignar') {
                    $resultadonumericocons = validarnumeros($_POST['valconsignacion']);
                    if (isset($resultadonumericocons) && $resultadonumericocons > 0) {
                        if ($conn != null) {
                            $registrado = Consignacion($conn, $_SESSION["loguser"],$_POST['valconsignacion']);
                            if ($registrado == TRUE) {
                                header('Location:movimientos.php');
                                exit();
                            }
                        }
                    }
                    else
                    {
                        echo '<script language="javascript">
                                alert("Escriba un valor numerico real")
                                window.location.href="movimientos.php";
                                </script>';
                        exit();
                    }
                    
                }
                if (isset($_POST['btnRetirar']) && $_POST['btnRetirar'] == 'Retirar') {
                    $resultadonumericoret = validarnumeros($_POST['valRetirar']);
                    if (isset($resultadonumericoret) && $resultadonumericoret > 0) {
                        if ($conn != null) {
                            $registrado = Retiro($conn, $_SESSION["loguser"],$_POST['valRetirar']);
                            if ($registrado == TRUE) {
                                header('Location:movimientos.php');
                                exit();
                            }
                            else {
                                echo '<script language="javascript">
                                alert("El valor digitado supera sus fondos u ocurrio una falla al hacer el retiro")
                                window.location.href="movimientos.php";
                                </script>';
                                exit();
                            }
                        }
                    }
                    else
                    {
                        echo '<script language="javascript">
                                alert("Escriba un valor numerico real")
                                window.location.href="movimientos.php";
                                </script>';
                        exit();
                    }
                }
                if (isset($_POST['btnTransferir']) && $_POST['btnTransferir'] == 'Transferir') {
                    $resultadonumericotrans = validarnumeros($_POST['valTransferir']);
                    $resultadonumericonmumcuenta = validarnumeros($_POST['numcuenta']);
                    if (isset($resultadonumericotrans) && $resultadonumericotrans > 0 && isset($resultadonumericonmumcuenta)) {
                        if ($conn != null) {
                            $registrado = Transferencia($conn, $_SESSION["loguser"],$_POST['valTransferir'],$_POST['numcuenta']);
                            if ($registrado == TRUE) {
                                header('Location:movimientos.php');
                                exit();
                            }
                            else {
                                echo '<script language="javascript">
                                alert("El valor digitado supera sus fondos u ocurrio una falla al hacer la transferencia")
                                window.location.href="movimientos.php";
                                </script>';
                                exit();
                            }
                        }
                    }
                    else
                    {
                        echo '<script language="javascript">
                                alert("Escriba un valor numerico real")
                                window.location.href="movimientos.php";
                                </script>';
                        exit();
                    }
                }
            }
        }
        else
        {
            header("Location:index.php");
            exit();
        }
    } catch (\Throwable $th) {
        //throw $th;
    }
    $anticsrf = rand(1000, 9999);
    $_SESSION['anticsrf'] = $anticsrf;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Movimientos</title>
  <link rel="stylesheet" href="libs/css/contenedor.css?v=<?php echo(rand()); ?>" />
  <script src="libs/js/jquery-3.6.0.min.js"></script>
  <script src="libs/js/main.js"></script>
  <script src="libs/js/petajax.js"></script>
</head>
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
  <div class="wrap">
		<ul class="tabs">
			<li><a href="#tab1"><span class="fa fa-home"></span><span class="tab-text">Transferencia</span></a></li>
			<li><a href="#tab2"><span class="fa fa-group"></span><span class="tab-text">Retiro</span></a></li>
			<li><a href="#tab3"><span class="fa fa-briefcase"></span><span class="tab-text">Consignación</span></a></li>
		</ul>
        <div id="refresh">

        </div>

		<div class="secciones">
			<article id="tab1">
                <form class="formp" action="" method="post" enctype="multipart/form-data">
                    <div class="form-element">
                        <label for="valTransferir">Cantidad a transferir:</label>
                        <input class="inputp" type="number" name="valTransferir" id="valTransferir" pattern="[0-9]" required>
                    </div>
                    <div class="form-element">
                        <label for="numcuenta">numero de cuenta a transferir:</label>
                        <input class="inputp" type="number" name="numcuenta" id="numcuenta" pattern="[0-9]" required>
                    </div>
                    <input type="hidden" name="anticsrf" value="<?php echo $anticsrf; ?>">
                    <input class="boton" type="submit" name="btnTransferir" value="Transferir">
                </form>
			</article>
			<article id="tab2">
                <form class="formp" action="" method="post" enctype="multipart/form-data">
                    <div class="form-element">
                        <label for="valRetirar">Cantidad a retirar:</label>
                        <input class="inputp" type="number" name="valRetirar" id="valRetirar" pattern="[0-9]" required>
                    </div>
                    <input type="hidden" name="anticsrf" value="<?php echo $anticsrf; ?>">
                    <input class="boton" type="submit" name="btnRetirar" value="Retirar">
                </form>
			</article>
			<article id="tab3">
                <form class="formp" action="" method="post" enctype="multipart/form-data">
                    <div class="form-element">
                        <label for="valconsignacion">Cantidad a consignar:</label>
                        <input class="inputp" type="number" name="valconsignacion" id="valconsignacion" pattern="[0-9]" required>
                    </div>
                    <input type="hidden" name="anticsrf" value="<?php echo $anticsrf; ?>">
                    <input class="boton" type="submit" name="btnConsignar" value="Consignar">
                </form>
			</article>
		</div>
	</div>
</body>
</html>