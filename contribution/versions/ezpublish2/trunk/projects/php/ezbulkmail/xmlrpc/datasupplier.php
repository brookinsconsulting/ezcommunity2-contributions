<?
if ( $ReturnCatalogues == true )
{
//    $Catalogues[] = new eZXMLRPCString( "categorylist" );
}
else
{
    switch ( $URL_ARRAY[1] )
    {
        case "categorylist" :
        {
            $Action = $URL_ARRAY[1];
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
