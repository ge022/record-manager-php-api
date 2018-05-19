<?php
  
  if ( !isset( $_SERVER[ 'PHP_AUTH_USER' ] ) ) {
    header( 'WWW-Authenticate: Basic realm=\"Please enter your username and password to proceed.\"' );
    header( 'HTTP/1.0 401 Unauthorized' );
    echo json_encode( array(
      'status' => 401,
      'message' => 'Unauthorized',
    ) );
    exit;
  } else if ( $_SERVER[ 'PHP_AUTH_USER' ] !== USER_ADMIN || $_SERVER[ 'PHP_AUTH_PW' ] !== USER_ADMIN_PASS ) {
    header( 'WWW-Authenticate: Basic realm=\"Please enter your username and password to proceed.\"' );
    header( 'HTTP/1.0 401 Unauthorized' );
    echo json_encode( array(
      'status' => 401,
      'message' => 'Unauthorized',
    ) );
    exit;
  }