<?
if ( $ReturnCatalogues == true )
{
//    $Catalogues[] = new eZXMLRPCString( "users" );
//    $Catalogues[] = new eZXMLRPCString( "groups" );
}
else
{
    switch ( $URL_ARRAY[1] )
    {
        case "userlist" :
        {
            $Action = $URL_ARRAY[1];
            include( "ezuser/xmlrpc/user.php" );
        } break;
        
        case "grouplist" :
        {
            $Action = $URL_ARRAY[1];
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
