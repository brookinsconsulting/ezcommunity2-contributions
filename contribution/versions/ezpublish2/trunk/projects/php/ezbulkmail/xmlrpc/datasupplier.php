<?
if ( $ReturnCatalogues == true )
{
//    $Catalogues[] = new eZXMLRPCString( "categorylist" );
}
else
{
    switch ( $RequestType )
    {
        case "category" :
        {
            include( "ezbulkmail/xmlrpc/category.php" );
        } break;
        
        default :
        {
            $Error = new eZXMLRPCResponse( );
            $Error->setError( 2, "Server function not found." );
        } break;
    }
}

?>
