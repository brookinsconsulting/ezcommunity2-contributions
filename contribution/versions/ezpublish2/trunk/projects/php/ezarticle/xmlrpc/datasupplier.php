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
            include( "ezarticle/xmlrpc/categorylist.php" );
        } break;
        
        
        default :
        {
            $Error = new eZXMLRPCResponse( );
            $Error->setError( 2, "Server function not found." );
        } break;
    }
}

?>
