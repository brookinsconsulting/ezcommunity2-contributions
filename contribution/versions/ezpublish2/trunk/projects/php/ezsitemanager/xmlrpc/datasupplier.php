<?

if ( $ReturnCatalogues == true )
{
//    $Catalogues[] = new eZXMLRPCString( "users" );
//    $Catalogues[] = new eZXMLRPCString( "groups" );
}
else
{
    switch ( $RequestType )
    {
        case "section" :
        {
            include( "ezsitemanager/xmlrpc/section.php" );
        } break;
        
        default :
        {
            $Error = new eZXMLRPCResponse( );
            $Error->setError( 2, "Server function not found." );
        } break;
    }
}
?>
