<?
ob_start();

// include the server
include_once( "ezxmlrpc/classes/ezxmlrpcserver.php" );

// include the datatype(s) we need
include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );

$server = new eZXMLRPCServer( );

$server->registerFunction( "myFunc" );

// process the server requests
$server->processRequest();

function myFunc( )
{
    return new eZXMLRPCString( "This command was run by xml rpc" );
}

ob_end_flush();
?>
