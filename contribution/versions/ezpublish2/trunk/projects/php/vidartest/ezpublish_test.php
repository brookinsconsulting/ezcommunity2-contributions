<?

include_once( "ezxmlrpc/classes/ezxmlrpcclient.php" );
include_once( "ezxmlrpc/classes/ezxmlrpccall.php" );

include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcdouble.php" );

echo "hei";
/*
// eZ publish article publishing test

$client = new eZXMLRPCClient( "publish.fh.ez.no", "/xmlrpc/" );

$client->setDebug( true );

$call = new eZXMLRPCCall( );
$call->setMethodName( "Call" );
$call->addParameter( new eZXMLRPCStruct(
    array(
        "Version" => new eZXMLRPCDouble( "0.1" ), // client version
        "URL" => new eZXMLRPCStruct(
	array( "Module" => "ezarticle",
               "Type" => "category",
		"ID" => 1 ) ),
        "User" => new eZXMLRPCString( "admin" ),
	"Command" => new eZXMLRPCString( "data" ),
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
    $command = $value["Command"]->value();

    print( "Server version: <b>$version</b><br>" );
    print( "Command: <b>$command</b><br>" );
    print( "URL: <pre>" );print_r( $url ); echo "</pre>";
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
        "URL" => new eZXMLRPCString( "ezarticle:/category/1" ),
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

*/
?>
