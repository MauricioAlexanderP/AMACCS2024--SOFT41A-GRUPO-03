<?php
require_once 'config/cn.php';

class Docente extends cn {
  public function __construct()
  {
    parent::__construct();
  }

  public function get_docentes($dpi)
  {
    $sql = "SELECT * FROM docente";
    return $this->consulta($sql);
  }
}
?>