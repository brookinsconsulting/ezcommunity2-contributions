<?
ob_start();
 // hei
 // hei
 // asdf
 // asdf



/*

// Uncomment this to have PHP Errors returned as XMLRPC Faults (error number 666)
// see ezxmlrpc/classes/ezxmlrpcerrorhandler.php

*/
error_reporting( 0 );
include_once( "ezxmlrpc/classes/ezxmlrpcresponse.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcerrorhandler.php" );
set_error_handler( "ezxmlrpcErrorHandler" );


include_once( "ezxmlrpc/classes/ezxmlrpcserver.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcbool.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcdouble.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcbase64.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcarray.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstruct.php" );

$server = new eZXMLRPCServer( );

$server->registerFunction( "myFunc" );
$server->registerFunction( "myFunc2", array( new eZXMLRPCString(), new eZXMLRPCString() )  );
$server->registerFunction( "myFile" );
$server->registerFunction( "tellMe" );
$server->registerFunction( "secret" );
$server->registerFunction( "add", array( new eZXMLRPCInt(), new eZXMLRPCInt() ) );
$server->registerFunction( "foo", array( new eZXMLRPCInt() ) );
$server->registerFunction( "myPi" );
$server->registerFunction( "currentTime" );

$server->registerFunction( "returnFirstArg", array( new eZXMLRPCArray() ) );



$server->registerFunction( "addArray", array( new eZXMLRPCArray() ) );



$server->registerFunction( "giveMeArray" );
$server->registerFunction( "giveMeStruct" );

$server->processRequest();

function myFunc( )
{
    $tmp = new eZXMLRPCString( "This comman< &&&&&d > & was>> run by xml rpc" );
    return $tmp;
}

function myFunc2( $args )
{
    $tmp = new eZXMLRPCString( "You sendt me: " . $args[0]->value() );
    return $tmp;
}

function secret( )
{
    $tmp = new eZXMLRPCString( "42, don't tell!" );
    return $tmp;
}

function tellMe( )
{
    $tmp = new eZXMLRPCBool( true );
    return $tmp;
}


function giveMeArray( )
{
    $tmp = new eZXMLRPCArray( array( new eZXMLRPCDouble( 3.14 ),
                                     new eZXMLRPCString( "second" ),
                                     new eZXMLRPCString( "second" ),
                                     new eZXMLRPCString( "second" ),
                                     new eZXMLRPCArray( array( new eZXMLRPCDouble( 3.14 ),
                                                               new eZXMLRPCString( "second" ),
                                                               new eZXMLRPCString( "second" ),
                                                               new eZXMLRPCString( "second" ) ) )
                                     )
                              );
    return $tmp;
}


function giveMeStruct( )
{
    $tmp = new eZXMLRPCStruct( array( "errorCode" => new eZXMLRPCInt( 42 ),
                                      "errorMessage" => new eZXMLRPCString( "Secret" ),
                                      "doubleTest" => new eZXMLRPCDouble( 3.1415 ),
                                      "errorMessage2" => new eZXMLRPCString( "Secret, not!" ),
                                      "ArrayInside" => new eZXMLRPCArray(
                                          array( new eZXMLRPCString( "first" ),
                                                 new eZXMLRPCString( "level1_1" ) )
                                          )
                                      )
                              );
    return $tmp;
}


function returnFirstArg( $args )
{
    return $args[0];
}



function add( $args )
{
    $res = $args[0]->value() + $args[1]->value();

    $tmp = new eZXMLRPCDouble( $res );
    return $tmp;
}

function foo( $args )
{
    $ret = "";
    for ( $i=0; $i<$args[0]->value(); $i++ )
    {
        $ret .= "blaa $v";
    }
    return new eZXMLRPCString( $ret );
}

function myPi( )
{
    return new eZXMLRPCDouble( 3.1415 );
}

function currentTime( )
{
    return new eZXMLRPCDateTime( );
}


function myFile( )
{
    $filePath = "/home/bf/ezhttpbench.gif";
    $fp = fopen( $filePath, "r" );
    $fileSize = filesize( $filePath );
    $content =& fread( $fp, $fileSize );
    
    return new eZXMLRPCBase64( $content );
}


function addArray( $args )
{
    $ret = "";
    // fetch the first parameter
    $args = $args[0];
    
    foreach ( $args->value() as $arg )
    {
        print( $arg );
        $ret += $arg->value();
    }
    
    return new eZXMLRPCInt( $ret );
}

ob_end_flush();
?>
// bla
// bla
// bla
// bla
// bla
// bla
// bla
// bla
// bla
// bla
// bla
// bla
// blaasdf

