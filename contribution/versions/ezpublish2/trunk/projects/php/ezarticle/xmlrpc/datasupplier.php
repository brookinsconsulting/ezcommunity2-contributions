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
        
        case "storecategory" :
        case "category" :
        {
            $ID = $URL_ARRAY[2];
            $Action = $URL_ARRAY[1];
            include( "ezarticle/xmlrpc/category.php" );
        } break;

        case "storearticle" :
        case "article" :
        {
            $ID = $URL_ARRAY[2];
            $Action = $URL_ARRAY[1];
            include( "ezarticle/xmlrpc/article.php" );
        }
        break;
        
        default :
        {
            $Error = new eZXMLRPCResponse( );
            $Error->setError( 2, "Server function not found." );
        } break;
    }
}

?>
