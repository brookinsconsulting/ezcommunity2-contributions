<?
include_once( "ezxmlrpc/classes/ezxmlrpcclient.php" );
include_once( "ezxmlrpc/classes/ezxmlrpccall.php" );

include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );

$client = new eZXMLRPCClient( "php.ez.no", "/xmlrpc/server.php" );

$call = new eZXMLRPCCall( );
$call->setMethodName( "myFunc2" );
$call->addParameter( new eZXMLRPCString( "bla" ) );
$call->addParameter( new eZXMLRPCString( "bla" ) );
$call->addParameter( new eZXMLRPCString( "bla" ) );
$call->addParameter( new eZXMLRPCString( "bla" ) );

$response = $client->send( $call );

$result = $response->result();

if ( $response->isFault() )
{
    print( "The server returned an error (" .  $response->faultCode() . "): ". 
           $response->faultString() .
           "<br>" );
}
else
{
    print( "The server returned: " . $result->value() . "<br>" );
}

//  $call = new eZXMLRPCCall( );
//  $call->setMethodName( "currentTime" );
//  $response = $client->send( $call );

//  $result = $response->result();
//  print( "The server returned: " . $result->value() . "<br>" );

// array test
//  $call = new eZXMLRPCCall( );
//  $call->setMethodName( "giveMeArray" );
//  $response = $client->send( $call );

//  $result = $response->result();
//  print( "The server returned: " . "<br>" );

//  foreach ( $result->value() as $item )
//  {
//      print( $item->value() . "<br>" );
    
//      if ( gettype( $item->value() )  == "array" )
//      {
//          foreach ( $item->value() as $subItem )
//          {
//              print( $subItem->value() . "<br>" );
//          }
        
//      }
//  }

//  // struct
//  print( "<hr>Struct:<br>" );
//  $call = new eZXMLRPCCall( );
//  $call->setMethodName( "giveMeStruct" );
//  $response = $client->send( $call );

//  $result = $response->result();

//  $struct = $result->value();

//  //  print_r( $struct );
    
//  print( $struct["errorCode"]->value() . "<br>" );
//  print( $struct["errorMessage"]->value() . "<br>" );


//  $call = new eZXMLRPCCall( );
//  $call->setMethodName( "add" );
//  $call->addParameter( new eZXMLRPCInt( 2 ) );
//  $call->addParameter( new eZXMLRPCInt( 3 ) );

//  $response = $client->send( $call );

//  $result = $response->result();
//  print( "The server returned: " . $result->value() . "<br>" );


// array as argument
$call = new eZXMLRPCCall( );
$call->setMethodName( "addArray" );

$call->addParameter( new eZXMLRPCArray( array( new eZXMLRPCInt( "1" ),
                                               new eZXMLRPCInt( "2" ),
                                               new eZXMLRPCInt( "3" ),
                                               new eZXMLRPCInt( "4" ) ) ) );

$response = $client->send( $call );
$result = $response->result();

print( "The server returned: " . $result->value() . "<br>" );




$call = new eZXMLRPCCall( );
$call->setMethodName( "foo" );
$call->addParameter( new eZXMLRPCInt( 10 ) );

$response = $client->send( $call );

$result = $response->result();
print( "The server returned: " . $result->value() . "<br>" );

$call = new eZXMLRPCCall( );
$call->setMethodName( "secret" );

$response = $client->send( $call );

$result = $response->result();
print( "The server returned: " . $result->value() . "<br>" );


$call = new eZXMLRPCCall( );
$call->setMethodName( "tellMe" );

$response = $client->send( $call );

$result = $response->result();
print( "The server returned: " . $result->value() . "<br>" );


// file test:

//  $call = new eZXMLRPCCall( );
//  $call->setMethodName( "myFile" );

//  $response = $client->send( $call );

//  $result = $response->result();
//  print( "The server returned: " . $result->value() . "<br>" );

//  $filePath = "/tmp/ezhttpbench2.gif";
//  $fp = fopen( $filePath, "w+" );
//  fwrite( $fp, $result->value() );
//  fclose( $fp );


// test on betty.userland.com
//  $client = new eZXMLRPCClient( "betty.userland.com", "/RPC2" );

//  $call = new eZXMLRPCCall( );
//  $call->setMethodName( "examples.getStateName" );
//  $call->addParameter( new eZXMLRPCInt( 41 ) );

//  $response = $client->send( $call );

//  $result = $response->result();
//  print( "The server returned: " . $result->value() . "<br>" );


// test on betty.userland.com
//  $client = new eZXMLRPCClient( "xmlrpc.heddley.com", "/server.php" );

//  $call = new eZXMLRPCCall( );
//  $call->setMethodName( "examples.getStateName" );
//  $call->addParameter( new eZXMLRPCInt( 41 ) );

//  $response = $client->send( $call );

//  $result = $response->result();
//  print( "The server returned: " . $result->value() . "<br>" );



?>

