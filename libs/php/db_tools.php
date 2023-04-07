<?php
    function ConexionDB()
    {
        /*$servername = "SQL8003.site4now.net";
        $database = "db_a86d58_bancochia";
        $username = "db_a86d58_bancochia_admin";
        $password = "BancoChia123";*/
        $servername = "DESKTOP-B4PV0I4";
        $database = "Banco_Chia";
        $username = "sa";
        $password = "123"; 
        $connectionInfo = array( "UID"=>$username ,"PWD"=>$password ,"Database"=>$database );   
        $my_Db_Connection = sqlsrv_connect( $servername, $connectionInfo);
        return $my_Db_Connection;
    }
    function RegistrarUsuarioDB($my_Db_Connection, $Nombres, $Apellidos, $Tipdoc, $Documento, $Genero, $Usuario, $Contrasena, $Fecha_nacimiento, $Correo, $Telefono, $Direccion, $Tipo_cuenta, $loguser)
    {
        try {
            $my_Select_Statement = "EXECUTE SP_BC_VALIDAR_USUARIO_EXISTENTE ?";
            $params = array(&$Usuario);
            $stmt = sqlsrv_prepare($my_Db_Connection, $my_Select_Statement , $params);
            sqlsrv_execute($stmt);
            if( sqlsrv_fetch( $stmt) === false) {
                die( print_r( sqlsrv_errors(), true));
            }
            $user = sqlsrv_get_field( $stmt, 0);
            if ($user) {
                return FALSE;
            }
            $my_Insert_Statement = "EXECUTE SP_BC_USUARIOS_I ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
            $params1 = array(&$Nombres,&$Apellidos,&$Tipdoc,&$Documento,&$Genero,&$Usuario,&$Contrasena,&$Fecha_nacimiento,&$Correo,&$Telefono);
            $dat = sqlsrv_prepare($my_Db_Connection, $my_Insert_Statement , $params1);
            if (sqlsrv_execute($dat)) 
            {
                $my_Insert_Statement = "EXECUTE SP_BC_CUENTA_I ?, ?";
                $params2 = array(&$Tipo_cuenta,&$Usuario);
                $stmt = sqlsrv_prepare($my_Db_Connection, $my_Insert_Statement , $params2);
                sqlsrv_execute($stmt);

                $my_Insert_Statement = "EXECUTE SP_BC_DIRECCION_I ?, ?, ?";
                $params3 = array(&$Direccion,&$Usuario,&$loguser);
                $stmt = sqlsrv_prepare($my_Db_Connection, $my_Insert_Statement , $params3);
                sqlsrv_execute($stmt);

                $my_Insert_Statement = "EXECUTE SP_BC_PERSONASXROLES_USUARIOS_I ?";
                $params4 = array(&$Usuario);
                $stmt = sqlsrv_prepare($my_Db_Connection, $my_Insert_Statement , $params4);
                sqlsrv_execute($stmt);

                return TRUE;
            }
            else
            {
                return FALSE;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    function ValidarLoginDB($my_Db_Connection, $Usuario, $Clave)
    {
        
            $token = '';
            $my_Select_Statement = "EXECUTE SP_BC_VALIDAR_LOGIN ?, ?";
            $params = array(&$Usuario, &$Clave);
            $stmt = sqlsrv_prepare($my_Db_Connection, $my_Select_Statement , $params);
            sqlsrv_execute($stmt);
            if( sqlsrv_fetch( $stmt) === false) {
                die( print_r( sqlsrv_errors(), true));
            }
            $user = sqlsrv_get_field( $stmt, 0);
            if ($user) 
            {
                $token = md5(time() . $Usuario);
            }
            return $token;
    }
    function SolicitudDB($my_Db_Connection, $Usuario, $Correo)
    {
        $sol = null;
        try {
            $my_Select_Statement = "EXECUTE SP_BC_SOLICITUD ?, ?";
            $params = array(&$Usuario, &$Correo);
            $stmt = sqlsrv_prepare($my_Db_Connection, $my_Select_Statement , $params);
            sqlsrv_execute($stmt);
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $sol = $row;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $sol;
    }
    function cambiocontraDB($my_Db_Connection, $Usuario, $Clave, $token)
    {
        try {
            $my_Select_Statement = "EXECUTE SP_BC_CONTRASENA_U ?, ?, ?";
            $params = array(&$Usuario, &$Clave, &$token);
            $stmt = sqlsrv_prepare($my_Db_Connection, $my_Select_Statement , $params);
            if (sqlsrv_execute($stmt)) {
                return true;
            }
            else
            {
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }
    function Foto($my_Db_Connection, $Usuario)
    {
        $foto = null;
        try {
            $my_Select_Statement = "EXECUTE SP_BC_FOTO  ?";
            $params = array(&$Usuario);
            $dat = sqlsrv_prepare($my_Db_Connection, $my_Select_Statement , $params);
            sqlsrv_execute($dat);
            while( $row = sqlsrv_fetch_array( $dat, SQLSRV_FETCH_ASSOC) ) {
                $foto = $row;
            }
            
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $foto;
    }
    function correo($my_Db_Connection, $Usuario)
    {
        $correo = null;
        try {
            $my_Select_Statement = "EXECUTE SP_BC_CORREO ?";
            $params = array(&$Usuario);
            $dat = sqlsrv_prepare($my_Db_Connection, $my_Select_Statement , $params);
            sqlsrv_execute($dat);
            while( $row = sqlsrv_fetch_array( $dat, SQLSRV_FETCH_ASSOC) ) {
                $correo = $row;
            }
            
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $correo;
    }
    function Roles($my_Db_Connection, $Usuario)
    {
        $Rol = array();
        try {
            $my_Select_Statement = "EXECUTE SP_BC_ROLES ?";
            $params = array(&$Usuario);
            $dat = sqlsrv_prepare($my_Db_Connection, $my_Select_Statement , $params);
            sqlsrv_execute($dat);
            while( $row = sqlsrv_fetch_array( $dat, SQLSRV_FETCH_ASSOC) ) {
                $Rol[] = $row;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $Rol;
    }
    function Saldo($my_Db_Connection, $Usuario)
    {
        $Saldo = null;
        try {
            $my_Select_Statement = "EXECUTE SP_BC_SALDO ?";
            $params = array(&$Usuario);
            $dat = sqlsrv_prepare($my_Db_Connection, $my_Select_Statement , $params);
            sqlsrv_execute($dat);
            while( $row = sqlsrv_fetch_array( $dat, SQLSRV_FETCH_ASSOC) ) {
                $Saldo = $row;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $Saldo;
    }
    function ListarDatosDB($my_Db_Connection, $Usuario)
    {
        $listadatos = array();
        try {
            $my_Select_Statement = "EXECUTE SP_BC_USUARIOS_S ?";
            $params = array(&$Usuario);
            $dat = sqlsrv_prepare($my_Db_Connection, $my_Select_Statement , $params);
            sqlsrv_execute($dat);
            while( $row = sqlsrv_fetch_array( $dat, SQLSRV_FETCH_ASSOC) ) {
                $listadatos[] = $row;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $listadatos;
    }
    function ListarDatosCuentaHDB($my_Db_Connection, $Usuario)
    {
        $listadatos = array();
        try {
            $my_Select_Statement = "EXECUTE SP_BC_USUARIOSH_S ?";
            $params = array(&$Usuario);
            $dat = sqlsrv_prepare($my_Db_Connection, $my_Select_Statement , $params);
            sqlsrv_execute($dat);
            while( $row = sqlsrv_fetch_array( $dat, SQLSRV_FETCH_ASSOC) ) {
                $listadatos[] = $row;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $listadatos;
    }
    function ListarsucursalesDB($my_Db_Connection)
    {
        $listadatos = array();
        try {
            $my_Select_Statement = "EXECUTE SP_BC_SUCURSALES_S";
            $dat = sqlsrv_prepare($my_Db_Connection, $my_Select_Statement);
            sqlsrv_execute($dat);
            while( $row = sqlsrv_fetch_array( $dat, SQLSRV_FETCH_ASSOC) ) {
                $listadatos[] = $row;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $listadatos;
    }
    function ListaTipdocsDB($my_Db_Connection)
    {
        $listadatostipdoc = array();
        try {
            $my_Select_Statement = "EXECUTE SP_BA_TIP_DOC_S";
            $dat = sqlsrv_prepare($my_Db_Connection, $my_Select_Statement);
            sqlsrv_execute($dat);
            while( $row = sqlsrv_fetch_array( $dat, SQLSRV_FETCH_ASSOC) ) {
                $listadatostipdoc[] = $row;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $listadatostipdoc;
    }
    function ListatiposcuentaDB($my_Db_Connection)
    {
        $listadatostipocuenta = array();
        try {
            $my_Select_Statement = "EXECUTE SP_BA_TIPOS_CUENTA_S";
            $dat = sqlsrv_prepare($my_Db_Connection, $my_Select_Statement);
            sqlsrv_execute($dat);
            while( $row = sqlsrv_fetch_array( $dat, SQLSRV_FETCH_ASSOC) ) {
                $listadatostipocuenta[] = $row;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $listadatostipocuenta;
    }
    function ListagenerosDB($my_Db_Connection)
    {
        $listadatosgenero = array();
        try {
            $my_Select_Statement = "EXECUTE SP_BA_GENEROS_S";
            $dat = sqlsrv_prepare($my_Db_Connection, $my_Select_Statement);
            sqlsrv_execute($dat);
            while( $row = sqlsrv_fetch_array( $dat, SQLSRV_FETCH_ASSOC) ) {
                $listadatosgenero[] = $row;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $listadatosgenero;
    }
    function Consignacion($my_Db_Connection, $Usuario, $valorconsignacion)
    {
        try {
            //code...
            $my_Insert_Statement = "EXECUTE SP_BC_CONSIGNACION_I ?, ?";
            $params = array(&$Usuario,&$valorconsignacion);
            $dat = sqlsrv_prepare($my_Db_Connection, $my_Insert_Statement , $params);
            if (sqlsrv_execute($dat)) 
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    function Retiro($my_Db_Connection, $Usuario, $valorretiro)
    {
        try {
            $saldo_cuenta = Validar_cuenta_sin_fondos($my_Db_Connection,$Usuario);
            if ($saldo_cuenta["Saldo_disponible"] <  $valorretiro) {
                return FALSE;
            }
            $my_Insert_Statement = "EXECUTE SP_BC_RETIRO_I ?, ?";
            $params = array(&$Usuario,&$valorretiro);
            $dat = sqlsrv_prepare($my_Db_Connection, $my_Insert_Statement , $params);
            if (sqlsrv_execute($dat))
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    function Transferencia($my_Db_Connection, $Usuario, $valortransferencia, $Cuenta_destino)
    {
        try {
            $saldo_cuenta = Validar_cuenta_sin_fondos($my_Db_Connection,$Usuario);
            if ($saldo_cuenta["Saldo_disponible"] <  $valortransferencia) {
                return FALSE;
            }
            $valcuenta = Validar_cuenta($my_Db_Connection,$Usuario);
            if ($valcuenta["Id_cuenta"] == $Cuenta_destino) {
                return FALSE;
            }
            $my_Insert_Statement = "EXECUTE SP_BC_transferencia_I ?, ?, ?";
            $params = array(&$Usuario,&$valortransferencia,&$Cuenta_destino);
            $dat = sqlsrv_prepare($my_Db_Connection, $my_Insert_Statement , $params);
            if (sqlsrv_execute($dat)) 
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    function Validar_cuenta_sin_fondos($my_Db_Connection, $Usuario)
    {
        $saldo = null;
        try {
            //code...
            $my_Insert_Statement = "EXECUTE SP_BC_VALIDAR_CUENTA_SIN_FONDOS ?";
            $params = array(&$Usuario);
            $dat = sqlsrv_prepare($my_Db_Connection, $my_Insert_Statement , $params);
            sqlsrv_execute($dat);
            while( $row = sqlsrv_fetch_array( $dat, SQLSRV_FETCH_ASSOC) ) {
                $saldo = $row;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $saldo;
    }
    function Validar_cuenta($my_Db_Connection, $Usuario)
    {
        $cuenta = NULL;
        try {
            //code...
            $my_Insert_Statement = "EXECUTE SP_BC_VALIDAR_CUENTA ?";
            $params = array(&$Usuario);
            $dat = sqlsrv_prepare($my_Db_Connection, $my_Insert_Statement , $params);
            sqlsrv_execute($dat);
            while( $row = sqlsrv_fetch_array( $dat, SQLSRV_FETCH_ASSOC) ) {
                $cuenta = $row;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $cuenta;
    }
    function ActualizarClaveDB($my_Db_Connection, $Usuario, $Clavenueva, $Claveantigua)
    {
        try {
            $my_Select_Statement =
                $my_Db_Connection->prepare("EXECUTE SP_EPD1_USUARIOS_CONTRASEÑA_U :Usuario, :Clave, :Claveantigua");
            $my_Select_Statement->execute([':Usuario' => $Usuario, ':Clave' => $Clavenueva, ':Claveantigua' => $Claveantigua]);
            if ($my_Select_Statement) 
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

    }
    function BusacarPersonasDB($my_Db_Connection,$Caracter)
    {
        $list = null;
        try {
            $my_Select_Statement = "EXECUTE SP_BC_BUSQUEDA_PERSONAS ?";
            $params = array(&$Caracter);
            $list = sqlsrv_prepare($my_Db_Connection, $my_Select_Statement , $params);
            sqlsrv_execute($list);
            $lists = array();
            while( $row = sqlsrv_fetch_array( $list, SQLSRV_FETCH_ASSOC) ) {
                $lists[] = $row;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $lists;
    }
    function ListaextractoDB($my_Db_Connection,$Usuario)
    {
        $lists = array();
        try {
            $my_Select_Statement = "EXECUTE SP_BA_DATOSEXTRACTO ?";
            $params = array(&$Usuario);
            $list = sqlsrv_prepare($my_Db_Connection, $my_Select_Statement , $params);
            sqlsrv_execute($list);
            while( $row = sqlsrv_fetch_array( $list, SQLSRV_FETCH_ASSOC) ) {
                $lists[] = $row;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $lists;
    }

    
?>