<?

include_once( "classes/INIFile.php" );
include_once( "classes/ezdb.php" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezproductattribute.php" );
include_once( "classes/ezfile.php" );
include_once( "classes/ezlog.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );

include_once( "ezxmlrpc/classes/ezxmlrpcclient.php" );
include_once( "ezxmlrpc/classes/ezxmlrpccall.php" );

include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstruct.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcdouble.php" );
include_once( "classes/ezmail.php" );

include_once( "ezmygoldimport.php" );

$ini = new INIFile( "site.ini" );
$GlobalSiteIni =& $ini;
set_time_limit( 0 );

$all = $argv[1] == "all";

$onlyDelete = $argv[2] == "true";

$import = new eZMygoldImport( "mygoldtest.ez.no", "test", "mygold", $all, $onlyDelete, 5 );

// Do the magic
$existingProducts =& $import->existingProducts();
$importedProducts =& $import->importProducts();

$deleteProducts =& array_diff( $existingProducts, $importedProducts );

$import->unavailable( $deleteProducts );

$import->updateImages();

$import->assignToCategoies();

$import->clearcache();

?>

