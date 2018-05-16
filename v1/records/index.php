<?php
  
  if ( $_SERVER[ 'REQUEST_METHOD' ] != "GET" ) {
    http_response_code( 404 );
    return;
  }
  
  // Headers
  header( 'Content-Type: application/json; charset=UTF-8' );
  
  include_once( '../../config/database.php' );
  include_once( '../models/record.php' );
  
  $db = new Database();
  $pdo = $db->connect();
  
  $record_id = isset( $_GET[ 'record_id' ] ) ? $_GET[ 'record_id' ] : null;
  $record = new Record( $pdo, $record_id );
  
  if ( $record_id ) {
    // Return single record
    
    $record = $record->getRecord();
    
    echo json_encode( $record );
    
    return;
    
  }
  
  // Return all records
  
  $records = $record->getAllRecords();
  
  echo json_encode( $records );