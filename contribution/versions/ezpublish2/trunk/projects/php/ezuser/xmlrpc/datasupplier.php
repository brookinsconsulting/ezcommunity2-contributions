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
        case "user" :
        {
            include( "ezuser/xmlrpc/user.php" );
        } break;
        
        case "group" :
        {
            include( "ezuser/xmlrpc/group.php" );
        } break;

        default :
        {
            $Error = new eZXMLRPCResponse( );
            $Error->setError( 2, "Server function not found." );
        } break;
    }
}
?>
