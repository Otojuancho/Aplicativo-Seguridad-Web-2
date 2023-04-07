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
            }
            foreach ($roles as $key => $value) {
                if ($value['Id_rol'] != 1 ) {
                    header('Location:index.php');
                }
            }
            if (isset($_POST['anticsrf']) && $_POST['anticsrf'] == $_SESSION['anticsrf']) {
                if (isset($_POST['btnsalir']) && $_POST['btnsalir'] == 'salir') {
                    cerrarsesion();
                    exit();
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
  <title>Sucursales</title>
  <link rel="stylesheet" href="libs/css/contenedor.css?v=<?php echo(rand()); ?>" />
</head>
<body>
  <header>
    <div class="menu">
      <nav>
          <ul>
            <li><a href="inicio.php">Inicio</a></li>
            <!--li><img height="50" src="uploaded_files/images/<?php //echo $foto["Foto"];?>"></li>-->
            <li style="color:rgb(255, 255, 255);">Bienvenido, <strong> <?php echo $foto['nombre'];?></strong></li> 
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
  <h1>sucursales</h1>
    <?php
        try {
            $conn = ConexionDB();
            if ($conn != null) {
                $listdat = ListarsucursalesDB($conn);
                foreach ($listdat as $key => $value) {
                    echo '
                    <table  class = "table" >
                        <tr>
                            <td> <label>Sucursal: </label> </td> <td> '.$value["Nombre_sucursal"].'</td>
                        </tr>
                        <tr>
                            <td><label>Gerente: </label></td> <td> '.$value["Gerente"].'</td>
                        </tr>
                        <tr>
                            <td><label>Telefono: </label></td> <td> '.$value["Telefono"].'</td>
                        </tr>
                        <tr>
                            <td><label>Dirreccion sucursal: </label></td> <td> '.$value["Direccion_comp"].'</td>
                        </tr>
                    </table>
                    '; 
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    ?>
</body>
</html>
