<?
if ( $ReturnCatalogues == true )
{
    $Catalogues[] = new eZXMLRPCString( "categorylist" );
}
else
{
    switch ( $RequestType )
    {
        case "category" :
        {
            switch( $Command )
            {
                case "list":
                {
                    include( "ezarticle/xmlrpc/categorylist.php" );
                    break;
                }
                case "data":
                case "storedata":
                case "delete":
                {
                    include( "ezarticle/xmlrpc/category.php" );
                    break;
                }
                default:
                    $Error = true;
            }
        } break;

        case "article" :
        {
            switch( $Command )
            {
                case "retreive":
                case "store":
                {
                    $Action = "article";
                    include( "ezarticle/xmlrpc/article.php" );
                    break;
                }
                default:
                    $Error = true;
            }
        }
        break;
        
        default :
        {
            $Error = true;
        } break;
    }
}

?>
