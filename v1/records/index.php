<?php
  
  // Headers
  header( 'Content-Type: application/json; charset=UTF-8' );
  header( 'Access-Control-Allow-Methods: GET, POST, PUT, DELETE' );
  $request_method = $_SERVER[ 'REQUEST_METHOD' ];
  
  include_once( '../../config/database.php' );
  require( '../../config/auth.php' );
  include_once( '../models/record.php' );
  
  $db = new Database();
  $pdo = $db->connect();
  
  $record_id = isset( $_GET[ 'record_id' ] ) ? $_GET[ 'record_id' ] : null;
  $record = new Record( $pdo, $record_id );
  
  if ( $record_id ) {
    // Return, update, or delete a single record
    
    switch ( $request_method ) {
      
      case 'PUT':
        
        // Retrieve json data
        $data = json_decode( file_get_contents( 'php://input' ) );
        
        // Assign json data to record
        $record->record_id = $data->record_id;
        $record->name = $data->name;
        $record->description = $data->description;
        $record->price = $data->price;
        $record->rating = $data->rating;
        $record->image = $data->image;
  
        $record = $record->updateRecord();
        echo json_encode( $record );
        return;
      
      case 'DELETE':
        
        $record = $record->deleteRecord();
        echo json_encode( $record );
        return;
      
      default:
        
        $record = $record->getRecord();
        echo json_encode( $record );
        return;
      
    }
    
  }
  
  // Create a record or return all records
  switch ( $request_method ) {
    
    case 'POST':
      
      // Retrieve json data
      $data = json_decode( file_get_contents( 'php://input' ) );
      
      // Assign json data to record
      $record->name = $data->name;
      $record->description = $data->description;
      $record->price = $data->price;
      $record->rating = $data->rating;
      $record->image = $data->image;
      
      $record = $record->createRecord();
      echo json_encode( $record );
      return;
    
    default:
      
      $records = $record->getAllRecords();
      echo json_encode( $records );
    
  }