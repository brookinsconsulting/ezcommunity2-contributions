<?
include_once( "classes/INIFile.php" );
include_once( "classes/ezdb.php" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezproductattribute.php" );

include_once( "classes/ezfile.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );

include_once( "ezxmlrpc/classes/ezxmlrpcclient.php" );
include_once( "ezxmlrpc/classes/ezxmlrpccall.php" );

include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstruct.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcdouble.php" );

$ini = new INIFile( "site.ini" );
$GlobalSiteIni =& $ini;

set_time_limit( 0 );
print( "Passiv import\n" );

// $client = new eZXMLRPCClient( "www.mygold.com", "/trade/xmlrpcimport/" );
$client = new eZXMLRPCClient( "mygold.nox.ez.no", "/trade/xmlrpcimport/" );

$call = new eZXMLRPCCall( );
$call->setMethodName( "passiv" );

$fd = fopen ( "/mnt/export/passiv.ez", "r" );

$db = eZDB::globalDatabase();

$product = new eZProduct();
$optionValue = new eZOptionValue();

$db->array_query( $productList, "SELECT ID, RemoteID FROM eZTrade_Product" );
$db->array_query( $optionValueList, "SELECT ID, RemoteID FROM eZTrade_OptionValue" );

$i = 0;
while (!feof ($fd))
{
    // get a new line
    $buffer = fgets($fd, 4096);

    $buffer = ereg_replace( "\|", "", $buffer );
    $buffer = trim( $buffer );

    $bufferArray[] = $buffer;

    $i++;
}
fclose( $fd );

$paramenter = "";
$paramenter = new eZXMLRPCArray( $bufferArray );

$call->addParameter( $paramenter ); 

$response = $client->send( $call );
exit();
$result = $response->result();
if ( $response->isFault() )
{
    eZLog::writeError( "Error: " . $response->faultCode() . "): ". $response->faultString() );
    return false;
}
else
{
    return true;
}

print( "Products/Options: " . count ( $bufferArray ) . "\n" );

?>
