<?
include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstruct.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
if( $Action == "categorylist" ) // Return a list of all bulkmail categories..
{
    $categoryList = eZBulkMailCategory::getAll( );
    $categories = array();
    foreach( $categoryList as $category )
    {
        $categories[] = new eZXMLRPCStruct( array( "ID" => new eZXMLRPCInt( $category->id() ),
                                              "Name" => new eZXMLRPCString( $category->name( false ) )
                                              )
                                       );
    }
    $ReturnData = new eZXMLRPCArray( $categories );
}?>
