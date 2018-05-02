<?php
  
  // Response headers
  header( "Content-Type: application/json; charset=UTF-8" );
  
  include_once( '../../config/database.php' );
  include_once( '../models/record.php' );
  
  $db = new Database();
  $pdo = $db->connect();
  
  $record = new Record( $pdo );
  $stmt = $record->getAllRecords();
  
  // Json structure
  $records = array();
  
  if ( $stmt->rowCount() ) {
    
    // Fetch assoc array
    while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ) ) {
      extract( $row );
      
      $record = array(
        'record_id' => $record_id,
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'rating' => $rating,
        'date_created' => $date_created,
        'date_modified' => $date_modified,
      );
      
      // Push onto product array
      $records[] = $record;
    }
    
  }
  
  echo json_encode( $records );