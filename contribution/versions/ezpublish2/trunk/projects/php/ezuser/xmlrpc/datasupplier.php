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

        case "author" :
        {
            include( "ezuser/xmlrpc/author.php" );
        } break;
        
        case "group" :
        {
            include( "ezuser/xmlrpc/group.php" );
        } break;

        default :
        {
            $Error = true;
        } break;
    }
}
?>
