<?

if ( $ReturnCatalogues == true )
{
    $Catalogues[] = new eZXMLRPCString( "categorylist" );
}
else
{
    switch ( $URL_ARRAY[1] )
    {
        case "categorylist" :
        {
            $ID = $URL_ARRAY[2];
            include( "ezarticle/xmlrpc/categorylist.php" );
        } break;
        
        case "category" :
        {
            $ID = $URL_ARRAY[2];
            include( "ezarticle/xmlrpc/category.php" );
        } break;
        
        default :
        {
            $Error = new eZXMLRPCResponse( );
            $Error->setError( 2, "Server function not found." );
        } break;
    }
}

?>
