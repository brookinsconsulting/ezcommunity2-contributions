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
            if( $Command == "list" )
            {
                include( "ezarticle/xmlrpc/categorylist.php" );
            }
            else if( $Command == "data" )
            {
                $Action = "data";
                include( "ezarticle/xmlrpc/category.php" );
            }
            else if( $Command == "store" )
            {
                $Action = "storecategory";
                include( "ezarticle/xmlrpc/category.php" );
            }
            else
            {
                // error
            }
        } break;
        
        case "article" :
        {
            if( $Command == "retreive" )
            {
                $Action = "article";
                include( "ezarticle/xmlrpc/article.php" );
            }
            else if( $Command == "store" )
            {
                $Action = "article";
                include( "ezarticle/xmlrpc/article.php" );
            }
            else
            {
                // error
            }
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
