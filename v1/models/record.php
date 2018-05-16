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
    
    public function __construct( PDO $pdo, $record_id = null ) {
      $this->pdo = $pdo;
      $this->record_id = $record_id;
    }
    
    public function getAllRecords() {
      $query = 'SELECT * FROM ' . $this->table;
      
      $stmt = $this->pdo->prepare( $query );
      try {
        $stmt->execute();
        
        $records = array();
        
        if ( $stmt->rowCount() ) {
          
          // Fetch assoc array
          while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
            
            $record = array(
              'record_id' => $row[ record_id ],
              'name' => html_entity_decode( $row[ name ], ENT_QUOTES ),
              'description' => htmlspecialchars_decode( $row[ description ], ENT_QUOTES ),
              'price' => $row[ price ],
              'rating' => $row[ rating ],
              'date_created' => $row[ date_created ],
              'date_modified' => $row[ date_modified ],
            );
            
            // Push onto product array
            $records[] = $record;
          }
          
        }
        
        return $records;
        
      } catch ( PDOException $exception ) {
        http_response_code( 500 );
        return array(
          'status' => 500,
          'message' => $exception->getMessage(),
        );
      }
      
    }
    
    public function getRecord() {
      $query = 'SELECT * FROM records WHERE record_id LIKE :record_id';
      
      $stmt = $this->pdo->prepare( $query );
      
      
      $this->record_id = filter_var( $this->record_id, FILTER_SANITIZE_NUMBER_INT );
      
      $stmt->bindParam( ':record_id', $this->record_id, PDO::PARAM_INT );
      
      try {
        $stmt->execute();
        
        if ( $stmt->rowCount() ) {
          
          // Fetch assoc array
          $record = $stmt->fetch( PDO::FETCH_ASSOC );
          
          $record = array(
            'record_id' => $record[ record_id ],
            'name' => html_entity_decode( $record[ name ], ENT_QUOTES ),
            'description' => htmlspecialchars_decode( $record[ description ], ENT_QUOTES ),
            'price' => $record[ price ],
            'rating' => $record[ rating ],
            'date_created' => $record[ date_created ],
            'date_modified' => $record[ date_modified ],
          );
          
          return $record;
          
        } else {
          http_response_code( 400 );
          return array(
            'status' => 400,
            'message' => 'Invalid record.',
          );
        }
        
      } catch ( PDOException $exception ) {
        http_response_code( 500 );
        return array(
          'status' => 500,
          'message' => $exception->getMessage(),
        );
      }
      
    }
    
    public function createRecord() {
      $query = 'INSERT INTO records (name, description, price, rating, date_created, date_modified) VALUES (:name, :description, :price, :rating, :date_created, :date_modified)';
      
      $stmt = $this->pdo->prepare( $query );
      
      $this->name = filter_var( $this->name, FILTER_SANITIZE_STRING );
      $this->description = filter_var( $this->description, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
      $this->price = filter_var( $this->price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
      $this->rating = filter_var( $this->rating, FILTER_SANITIZE_NUMBER_INT );
      $this->date_created = date( "Y-m-d H:i:s" );
      $this->date_modified = date( "Y-m-d H:i:s" );
      
      if ( empty( trim( $this->name ) ) ) {
        http_response_code( 400 );
        return array(
          'status' => 400,
          'message' => 'Name is required.',
        );
      }
      
      $stmt->bindParam( ':name', $this->name, PDO::PARAM_STR );
      $stmt->bindParam( ':description', $this->description, PDO::PARAM_STR );
      $stmt->bindParam( ':price', $this->price, PDO::PARAM_STR );
      $stmt->bindParam( ':rating', $this->rating, PDO::PARAM_INT );
      $stmt->bindParam( ':date_created', $this->date_created );
      $stmt->bindParam( ':date_modified', $this->date_modified );
      
      try {
        $stmt->execute();
        return array(
          'record_id' => $this->pdo->lastInsertId(),
          'name' => html_entity_decode( $this->name, ENT_QUOTES ),
          'description' => htmlspecialchars_decode( $this->description, ENT_QUOTES ),
          'price' => $this->price,
          'rating' => $this->rating,
          'date_created' => $this->date_created,
          'date_modified' => $this->date_modified,
        );
      } catch ( PDOException $exception ) {
        http_response_code( 500 );
        return array(
          'status' => 500,
          'message' => $exception->getMessage(),
        );
      }
      
    }
    
    public function updateRecord() {
      $query = 'UPDATE records SET name = :name, description = :description, price = :price, rating = :rating, date_modified = :date_modified WHERE record_id LIKE :record_id';
      
      $stmt = $this->pdo->prepare( $query );
      
      $this->record_id = filter_var( $this->record_id, FILTER_SANITIZE_NUMBER_INT );
      $this->name = filter_var( $this->name, FILTER_SANITIZE_STRING );
      $this->description = filter_var( $this->description, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
      $this->price = filter_var( $this->price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
      $this->rating = filter_var( $this->rating, FILTER_SANITIZE_NUMBER_INT );
      $this->date_modified = date( "Y-m-d H:i:s" );
      
      $record_to_update = $this->getRecord();
      if ( empty( $record_to_update[ record_id ] ) ) {
        http_response_code( 400 );
        return array(
          'status' => 400,
          'message' => 'Invalid record.',
        );
      }
      
      $stmt->bindParam( ':record_id', $record_to_update[ record_id ] );
      $stmt->bindParam( ':name', $this->name, PDO::PARAM_STR );
      $stmt->bindParam( ':description', $this->description, PDO::PARAM_STR );
      $stmt->bindParam( ':price', $this->price, PDO::PARAM_STR );
      $stmt->bindParam( ':rating', $this->rating, PDO::PARAM_INT );
      $stmt->bindParam( ':date_modified', $this->date_modified );
      
      if ( empty( trim( $this->record_id ) ) ) {
        http_response_code( 400 );
        return array(
          'status' => 400,
          'message' => 'Invalid record.',
        );
      } else if ( empty( trim( $this->name ) ) ) {
        http_response_code( 400 );
        return array(
          'status' => 400,
          'message' => 'Name is required.',
        );
      }
      
      try {
        $stmt->execute();
        return array(
          'record_id' => $record_to_update[ record_id ],
          'name' => html_entity_decode( $this->name, ENT_QUOTES ),
          'description' => htmlspecialchars_decode( $this->description, ENT_QUOTES ),
          'price' => $this->price,
          'rating' => $this->rating,
          'date_created' => $record_to_update[ date_created ],
          'date_modified' => $this->date_modified,
        );
      } catch ( PDOException $exception ) {
        http_response_code( 500 );
        return array(
          'status' => 500,
          'message' => $exception->getMessage(),
        );
      }
      
    }
    
  }