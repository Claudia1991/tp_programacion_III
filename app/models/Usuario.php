<?php

class Usuario
{
    public $id;
    public $nombre;
    public $apellido;
    public $clave;
    public $tipo_usuario;
    public $id_sector;
    public $baja_logica;

    public function crearUsuario()
    {
        //Cuando creo un usuario, por defecto, activo_temporada = 1 y baja_logica = 0
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO Usuarios (nombre, apellido, clave, tipo_usuario,id_sector, baja_logica) VALUES (:nombre, :apellido, :clave, :tipo_usuario, :id_sector, :baja_logica)");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':tipo_usuario', $this->tipo_usuario, PDO::PARAM_INT);
        $consulta->bindValue(':id_sector', $this->id_sector, PDO::PARAM_INT);
        $consulta->bindValue(':baja_logica', 0, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Usuarios where baja_logica = 0");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUsuario($id, $activoTemporada = false)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Usuarios WHERE id = :id and baja_logica = :baja_logica");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->bindValue(':baja_logica', $activoTemporada, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public static function modificarUsuario(Usuario $usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Usuarios SET nombre = :nombre, apellido = :apellido, clave = :clave WHERE id = :id");
        $claveHash = password_hash($usuario->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':nombre', $usuario->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $usuario->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash, PDO::PARAM_STR);
        $consulta->bindValue(':id', $usuario->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarUsuario($id)
    {
        //Se realiza borrado logico
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Usuarios SET baja_logica = 1 WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function revertirBajaUsuario($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Usuarios SET baja_logica = 0 WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    /** Reportes */

    public static function ingresosSistema($fecha_inicio, $fecha_fin)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT l.id_usuario, u.nombre, sr.descripcion, l.fecha_hora FROM `Logs` l
        inner JOIN Usuarios u on u.id = l.id_usuario
        INNER join Sectores_Restaurant sr on sr.codigo = u.id_sector
        WHERE l.entidad = 'SISTEMA' AND operacion = 'Ingreso Sistema' and  cast(fecha_hora AS date) BETWEEN :fecha_inicio AND :fecha_fin");
        $consulta->bindValue(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
        $consulta->execute();
        $reportes = $consulta->fetchAll();
        $reportes_string = '';
        foreach ($reportes as $reporte) {
            $reporte_line  = 'Id usuario: ' . $reporte['id_usuario'] . ' - Nombre: '. $reporte['nombre'] . ' - Sector: ' 
            . $reporte['descripcion'] . ' - Fecha hora: ' . $reporte['fecha_hora'] ;
            $reportes_string = $reportes_string.PHP_EOL . $reporte_line;
        }
        return $reportes_string;
    }

    public static function operacionesPorSector($fecha_inicio, $fecha_fin)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT l.id_sector, sr.descripcion, count(*) as cantidad_operaciones FROM `Logs` l
        inner JOIN Sectores_Restaurant sr on sr.codigo = l.id_sector
        where cast(fecha_hora AS date) BETWEEN :fecha_inicio AND :fecha_fin
        GROUP by l.id_sector, sr.descripcion
        ORDER by l.id_sector ASC");
        $consulta->bindValue(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
        $consulta->execute();

        $reportes = $consulta->fetchAll();
        $reportes_string = '';
        foreach ($reportes as $reporte) {
            $reporte_line  = 'Id sector: ' . $reporte['id_sector'] . ' - Descripcion: '. $reporte['descripcion'] . ' - Operaciones: ' 
            . $reporte['cantidad_operaciones'] ;
            $reportes_string = $reportes_string.PHP_EOL . $reporte_line;
        }
        return $reportes_string;
    }

    public static function operacionesPorSectorYPorUsuario($fecha_inicio, $fecha_fin)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT l.id_usuario,u.nombre, sr.descripcion ,
        COUNT(l.id_usuario) as cantidad_operaciones FROM `Logs` l
        INNER JOIN Usuarios u on u.id = l.id_usuario
        INNER join Sectores_Restaurant sr on sr.codigo = u.id_sector
        where cast(fecha_hora AS date) BETWEEN :fecha_inicio AND :fecha_fin
        GROUP BY  l.id_usuario,u.nombre, sr.descripcion
        ORDER by l.id_usuario");
        $consulta->bindValue(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
        $consulta->execute();

        $reportes = $consulta->fetchAll();
        $reportes_string = '';
        foreach ($reportes as $reporte) {
            $reporte_line  = 'Id usuario: ' . $reporte['id_usuario'] . ' - Nombre: '. $reporte['nombre'] . ' - Sector: ' 
            . $reporte['descripcion'] . ' - Operaciones: ' . $reporte['cantidad_operaciones'] ;
            $reportes_string = $reportes_string.PHP_EOL . $reporte_line;
        }
        return $reportes_string;
    }

    public static function operacionesPorUsuario($fecha_inicio, $fecha_fin)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT l.id_usuario,u.nombre, COUNT(l.id_usuario) as cantidad_operaciones FROM `Logs` l
        INNER JOIN Usuarios u on u.id = l.id_usuario
        where cast(fecha_hora AS date) BETWEEN :fecha_inicio AND :fecha_fin
        GROUP BY  l.id_usuario,u.nombre
        ORDER by l.id_usuario");
        $consulta->bindValue(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
        $consulta->execute();

        $reportes = $consulta->fetchAll();
        $reportes_string = '';
        foreach ($reportes as $reporte) {
            $reporte_line  = 'Id usuario: ' . $reporte['id_usuario'] . ' - Nombre: '. $reporte['nombre'] . ' - Operaciones: ' 
            . $reporte['cantidad_operaciones'] ;
            $reportes_string = $reportes_string.PHP_EOL . $reporte_line;
        }
        return $reportes_string;
    }
    
}