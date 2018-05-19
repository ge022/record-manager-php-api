<?php
  if (getcwd() == dirname(__FILE__)) {
    die();
  }
  
  require( 'config.php' ); // Connection details
  
  class Database {
    
    private $db_host = DB_HOST;
    private $db_name = DB_NAME;
    private $db_user = DB_USER;
    private $db_pass = DB_PASSWORD;
    private $charset = 'utf8mb4';
    public $pdo;
    
    public function connect() {
      
      try {
        $dsn = 'mysql:host=' . $this->db_host . ';dbname=' . $this->db_name . ';charset=' . $this->charset;
        $pdo = new PDO( $dsn, $this->db_user, $this->db_pass );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
      } catch ( PDOException $exception ) {
        echo "Connection error: " . $exception->getMessage();
      }
      
    }
    
  }