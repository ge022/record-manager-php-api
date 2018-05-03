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
    
    public function create() {
      $query = 'INSERT INTO records (name, description, price, rating, date_created, date_modified) VALUES (:name, :description, :price, :rating, :date_created, :date_modified)';
      
      $stmt = $this->pdo->prepare( $query );
      
      $this->name = filter_var( $this->name, FILTER_SANITIZE_STRING );
      $this->description = filter_var( $this->description, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
      $this->price = filter_var( $this->price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
      $this->rating = filter_var( $this->rating, FILTER_SANITIZE_NUMBER_INT );
      $this->date_created = date( "Y-m-d H:i:s" );
      $this->date_modified = date( "Y-m-d H:i:s" );
      
      $stmt->bindParam( ':name', $this->name, PDO::PARAM_STR );
      $stmt->bindParam( ':description', $this->description, PDO::PARAM_STR );
      $stmt->bindParam( ':price', $this->price, PDO::PARAM_STR );
      $stmt->bindParam( ':rating', $this->rating, PDO::PARAM_INT );
      $stmt->bindParam( ':date_created', $this->date_created );
      $stmt->bindParam( ':date_modified', $this->date_modified );
      
      try {
        $stmt->execute();
        return array(
          'name' => $this->name,
          'description' => $this->description,
          'price' => $this->price,
          'rating' => $this->rating,
          'date_created' => $this->date_created,
          'date_modified' => $this->date_modified,
        );
      } catch ( PDOException $exception ) {
        return array(
          'status' => 400,
          'message' => $exception->getMessage(),
        );
      }
      
    }
    
  }