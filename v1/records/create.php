<?php
  
  // Headers
  header( 'Content-Type: application/json; charset=UTF-8' );
  header( 'Access-Control-Allow-Methods: POST' );
  
  include_once( '../../config/database.php' );
  include_once( '../models/record.php' );
  
  $db = new Database();
  $pdo = $db->connect();
  
  $record = new Record( $pdo );
  
  // Retrieve json data
  $data = json_decode( file_get_contents( 'php://input' ) );
  
  // Assign json data to record
  $record->name = $data->name;
  $record->description = $data->description;
  $record->price = $data->price;
  $record->rating = $data->rating;
  
  $record = $record->create();
  
  echo json_encode($record);