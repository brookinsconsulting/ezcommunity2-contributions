<?
include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstruct.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
if( $Command == "list" ) // Return a list of all bulkmail categories..
{
    $categoryList = eZBulkMailCategory::getAll( );
    $categories = array();
    foreach( $categoryList as $category )
    {
        $categories[] = new eZXMLRPCStruct( array( "URL" => createURLStruct( "ezbulkmail", "category",  $category->id() ),
                                              "Name" => new eZXMLRPCString( $category->name( false ) )
                                              )
                                       );
    }
    $ReturnData = new eZXMLRPCStruct( array( "Catalogues" => new eZXMLRPCArray(),
                                             "Elements" => $categories,
                                             "Path" => new eZXMLRPCArray() ) ); // array starting with top level catalogue, ending with parent.

}

?>
