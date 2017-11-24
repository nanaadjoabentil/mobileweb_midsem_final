<?php
/*
* class to create and issue database connection
*/

class Database
{
  private static $dbb = NULL;
  private $conn = null;

  private function __construct() {
    //$this->conn = new
    $this->conn = new PDO("mysql:host=localhost;port=3306;dbname=mobileweb","root","");
    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }

  /**
  *method to get a database instance or create a new one if non-existent
  *@param void
  *@return database connection instance
  **/

  public static function getInstance(){
    if (NULL === self::$dbb) {
      self::$dbb = (new Database())->conn;
    }
    return self::$dbb;
  }
}

 ?>
