<?
include_once( "ezxmlrpc/classes/ezxmlrpcclient.php" );
include_once( "ezxmlrpc/classes/ezxmlrpccall.php" );

include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );

// test with another server:

/*
$client = new eZXMLRPCClient( "betty.userland.com", "/rpc2" );

$call = new eZXMLRPCCall( );
$call->setMethodName( "examples.getStateName" );
$call->addParameter( new eZXMLRPCInt( 21 ) );

$response = $client->send( $call );

print( "<pre>" );
//print_r($response );
print( "</pre>" );

if ( $response->isFault() )
{
    print( "The server returned an error (" .  $response->faultCode() . "): ". 
           $response->faultString() .
           "<br>" );
}
else
{
    $result = $response->result();

    print( "The server returned: " . $result->value() . "<br>" );
}

*/

// Local test

$client = new eZXMLRPCClient( "php.ez.no", "/xmlrpc/server.php" );
//$client->setDebug( true );

// error test, to many parameters
print( "error test:<br>" );
$call = new eZXMLRPCCall( );
$call->setMethodName( "myFunc2" );
$call->addParameter( new eZXMLRPCString( "bla" ) );
$call->addParameter( new eZXMLRPCString( "bla" ) );
$call->addParameter( new eZXMLRPCDouble( "bla" ) );

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

$call = new eZXMLRPCCall( );
$call->setMethodName( "currentTime" );
$response = $client->send( $call );

$result = $response->result();

print( "The server returned: " . $result->value() . "<br>" );

// array test
$call = new eZXMLRPCCall( );
$call->setMethodName( "giveMeArray" );
$response = $client->send( $call );

$result = $response->result();
print( "The server returned: " . "<br>" );

print( "<pre>" );
print_r( $result->value() );
print( "</pre>" );
    

foreach ( $result->value() as $item )
{
    print( $item->value() . "<br>" );
    
    if ( gettype( $item->value() )  == "array" )
    {
        foreach ( $item->value() as $subItem )
        {
            print( $subItem->value() . "<br>" );
        }
        
    }
}

// struct
print( "<hr>Struct:<br>" );
$call = new eZXMLRPCCall( );
$call->setMethodName( "giveMeStruct" );
$response = $client->send( $call );

$result = $response->result();

$struct = $result->value();

print( "<pre>" );
print_r( $struct );
print( "</pre>" );
    
print( $struct["errorCode"]->value() . "<br>" );
print( $struct["errorMessage"]->value() . "<br>" );



$call = new eZXMLRPCCall( );
$call->setMethodName( "add" );
$call->addParameter( new eZXMLRPCInt( 2 ) );
$call->addParameter( new eZXMLRPCInt( 3 ) );

$response = $client->send( $call );

$result = $response->result();
print( "The server returned: " . $result->value() . "<br>" );


// array as argument
$call = new eZXMLRPCCall( );
$call->setMethodName( "addArray" );

$call->addParameter( new eZXMLRPCArray( array( new eZXMLRPCDouble( "1" ),
                                               new eZXMLRPCInt( "2" ),
                                               new eZXMLRPCInt( "3" ),
                                               new eZXMLRPCInt( "4" ) ) ) );

$response = $client->send( $call );
$result = $response->result();

print( "The server returned: " . $result->value() . "<br>" );



// send return test, with array
print( "<hr>" );
print( "<br>Send and return test:<br>" );
$call = new eZXMLRPCCall( );
$call->setMethodName( "returnFirstArg" );

// create an advanced datatype combination:
$call->addParameter( new eZXMLRPCArray( array( new eZXMLRPCDouble( 4.32 ),
                                               new eZXMLRPCInt( "2" ),
                                               new eZXMLRPCString( "Foo bar" ),
                                               new eZXMLRPCInt( "3" ),
                                               new eZXMLRPCStruct( array( "ADoubleValue" => new eZXMLRPCDouble( 42.2223 ),
                                                                          "AnInt" => new eZXMLRPCInt( 2 ),
                                                                          "AString" => new eZXMLRPCString( "3" ),                                                                          
                                                                          "BoolItIS" => new eZXMLRPCBool( true ),
                                                                          "ASubArray" => new eZXMLRPCArray( array( new eZXMLRPCDouble( 3.1415 ),
                                                                                                                  new eZXMLRPCInt( "2" ),
                                                                                                                  new eZXMLRPCInt( "3" ),
                                                                                                                   new eZXMLRPCInt( "4" ) ) ) )
                                                                   ),
                                               
                                               new eZXMLRPCArray( array( new eZXMLRPCDouble( 3.1415 ),
                                                                         new eZXMLRPCInt( "2" ),
                                                                         new eZXMLRPCInt( "3" ),
                                                                         new eZXMLRPCStruct( array( "ADoubleValue" => new eZXMLRPCDouble( 42.2223 ),
                                                                                                    "AnInt" => new eZXMLRPCInt( 2 ),
                                                                                                    "AString" => new eZXMLRPCString( "3" ),                                                                          
                                                                                                    "BoolItIS" => new eZXMLRPCBool( true ),
                                                                                                    "ASubArray" => new eZXMLRPCArray( array( new eZXMLRPCDouble( 3.1415 ),
                                                                                                                                             new eZXMLRPCInt( "2" ),
                                                                                                                                             new eZXMLRPCInt( "3" ),
                                                                                                                                             new eZXMLRPCArray( array( new eZXMLRPCDouble( 3.1415 ),
                                                                                                                                                                       new eZXMLRPCInt( "2" ),
                                                                                                                                                                       new eZXMLRPCInt( "3" ),
                                                                                                                                                                       new eZXMLRPCStruct( array( "ADoubleValue" => new eZXMLRPCDouble( 42.2223 ),
                                                                                                                                                                                                  "AnInt" => new eZXMLRPCInt( 2 ),
                                                                                                                                                                                                  "AString" => new eZXMLRPCString( "3" ),                                                                          
                                                                                                                                                                                                  "BoolItIS" => new eZXMLRPCBool( true ),
                                                                                                                                                                                                  "ASubArray" => new eZXMLRPCArray( array( new eZXMLRPCDouble( 3.1415 ),
                                                                                                                                                                                                                                           new eZXMLRPCInt( "2" ),
                                                                                                                                                                                                                                           new eZXMLRPCInt( "3" ),
                                                                                                                                                                                                                                           new eZXMLRPCInt( "4" ) ) ) )
                                                                                                                                                                                           ),
                                                                                                                                                                       new eZXMLRPCInt( "4" ) ) ),
                                                                                                                                             
                                                                                                                                             new eZXMLRPCInt( "4" ) ) ) )
                                                                                             ),                                                                         
                                                                         new eZXMLRPCInt( "4" ) ) )
                                               )
                                        ) );
                     
$response = $client->send( $call );

if ( $response->isFault() )
{
    print( "The server returned an error (" .  $response->faultCode() . "): ". 
           $response->faultString() .
           "<br>" );
}
else
{
    $result = $response->result();

    print( "<pre>" );
    print_r( $result->value() );
    print( "</pre>" );
    
    print( "The server returned: " . $result->value() . "<br>" );
    
    
    print( "The server returned: " . $result->value() . "<br>" );
}

print( "<hr>" );

/// misc tests

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


//file test:
//  $call = new eZXMLRPCCall( );
//  $call->setMethodName( "myFile" );

//  $response = $client->send( $call );

//  $result = $response->result();
//  print( "The server returned: " . $result->value() . "<br>" );

//  $filePath = "/tmp/ezhttpbench2.gif";
//  $fp = fopen( $filePath, "w+" );
//  fwrite( $fp, $result->value() );
//  fclose( $fp );



?>

