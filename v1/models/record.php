<?php
  
  class Record {
    
    private $pdo;
    private $table = 'records';
    
    public $record_id;
    public $name;
    public $description;
    public $price;
    public $rating;
    public $date_created;
    public $date_modified;
    
    public function __construct( PDO $pdo ) {
      $this->pdo = $pdo;
    }
    
    public function getAllRecords() {
      $query = 'SELECT * FROM ' . $this->table;
      
      $stmt = $this->pdo->prepare( $query );
      $stmt->execute();
      
      return $stmt;
    }
    
  }