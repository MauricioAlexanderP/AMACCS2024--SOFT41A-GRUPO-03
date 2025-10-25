<?php
class cn {

    private $con = null;

    public function __construct()
    {
        // Conexión con manejo de errores
        $this->con = new mysqli('localhost', 'root', '', 'sistemaglobal9');

        if ($this->con->connect_error) {
            die("Error de conexión: " . $this->con->connect_error);
        }

        // Establecer codificación UTF-8 para evitar problemas con tildes o eñes
        $this->con->set_charset("utf8mb4");
    }

    // Ejecutar una consulta directa
    public function consulta($sql) {
        $resultado = $this->con->query($sql);

        if (!$resultado) {
            die("Error en la consulta: " . $this->con->error);
        }

        return $resultado;
    }

    // Obtener el objeto mysqli (útil para consultas preparadas)
    public function getCon() {
        return $this->con;
    }

    // Cerrar conexión (opcional)
    public function cerrar() {
        if ($this->con) {
            $this->con->close();
        }
    }
}
?>
