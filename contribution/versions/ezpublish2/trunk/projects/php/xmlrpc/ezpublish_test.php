<?
include_once( "ezxmlrpc/classes/ezxmlrpcclient.php" );
include_once( "ezxmlrpc/classes/ezxmlrpccall.php" );

include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcdouble.php" );

// eZ publish article publishing test

$client = new eZXMLRPCClient( "publish.php.ez.no", "/xmlrpc/" );

$client->setDebug( true );

$call = new eZXMLRPCCall( );
$call->setMethodName( "Call" );
$call->addParameter( new eZXMLRPCStruct(
    array(
        "Version" => new eZXMLRPCDouble( "0.1" ), // client version
        "URL" => new eZXMLRPCString( "ezpublish:/modules" ),
        "User" => new eZXMLRPCString( "admin" ),
        "Password" => new eZXMLRPCString( "publish" ),
        "Data" => new eZXMLRPCArray( ) )
    ) );

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
    $value = $result->value();
    $version = $value["Version"]->value();
    $url = $value["URL"]->value();
    $data = $value["Data"]->value();

    print( "Server version: <b>$version</b><br>" );
    print( "URL: <b>$url</b><br>" );
    print( "Data: <pre>" );
    print_r( $data );
    print( "</pre>" );
        
}

//
print( "<br><br>" );
$call = new eZXMLRPCCall( );
$call->setMethodName( "Call" );
$call->addParameter( new eZXMLRPCStruct(
    array(
        "Version" => new eZXMLRPCDouble( "0.1" ), // client version
        "URL" => new eZXMLRPCString( "ezarticle:/categorylist/0" ),
        "User" => new eZXMLRPCString( "admin" ),
        "Password" => new eZXMLRPCString( "publish" ),
        "Data" => new eZXMLRPCArray( ) )
    ) );

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
    $value = $result->value();
    $version = $value["Version"]->value();
    $url = $value["URL"]->value();
    $data = $value["Data"]->value();

    print( "Server version: <b>$version</b><br>" );
    print( "URL: <b>$url</b><br>" );
    print( "Data: <pre>" );
    print_r( $data );
    print( "</pre>" );
}


?>
