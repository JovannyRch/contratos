<?php



class Db
{
    function __construct()
    {

        $server = 'localhost';
        $username = 'root';
        $database = 'contratos_db';
        $password = '';

        try {
            $this->db = new mysqli($server, $username, $password, $database);
        } catch (Exception $e) {
            die('Connection Failed: ' . $e->getMessage());
        }
    }


    function row($sql, $debug = false)
    {
        if ($debug) {
            echo "Row: " . $sql;
        }
        $arr = $this->array($sql);

        if (sizeof($arr) == 0) {
            return null;
        }
        return $arr[0];
    }

    function array($sql)
    {
        try {
            $query = mysqli_query($this->db, $sql);

            $res = array();
            while ($row = mysqli_fetch_array($query)) {
                $res[] = $row;
            }
            return $res;
        } catch (\Throwable $th) {
            return array();
        }
    }

    function query($sql)
    {
        try {
            return $this->db->query($sql);
        } catch (\Throwable $th) {
            echo $th;
            return array();
        }
    }

    function insert($sql)
    {
        try {
            if ($this->query($sql) === TRUE) {
                return $this->db->insert_id;
            }
            return null;
        } catch (\Throwable $th) {
            return null;
        }
    }

    function login($correo, $password)
    {
        $usuario = $this->row("SELECT u.*, p.nombre as puesto from usuarios as u inner join puestos p on p.id_puesto = u.id_puesto where u.correo = '$correo' and u.password = '$password' ");
        return $usuario;
    }

    function registrar($nombre, $paterno, $materno, $id_puesto, $correo, $password)
    {
        $response = $this->query("INSERT INTO 
         usuarios(nombre, paterno, materno, id_puesto, correo, password)
         VALUES ('$nombre','$paterno','$materno','$id_puesto','$correo','$password')");
        if (is_null($response)) {
            return false;
        }
        return $response;
    }

    function getPuestos()
    {
        return $this->array("SELECT * from puestos");
    }
}
