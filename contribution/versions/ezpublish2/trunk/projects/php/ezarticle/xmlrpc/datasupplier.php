<?
switch ( $RequestType )
{
    case "type" :
    {
        switch( $Command )
        {
            case "list":
            {
                include( "ezarticle/xmlrpc/typelist.php" );
                break;
            }
            case "data":
            case "storedata":
            case "delete":
            {
                include( "ezarticle/xmlrpc/type.php" );
                break;
            }
        }
    } break;
    case "topic" :
    {
        switch( $Command )
        {
            case "list":
            {
                include( "ezarticle/xmlrpc/topiclist.php" );
                break;
            }
            case "data":
            case "storedata":
            {
                include( "ezarticle/xmlrpc/topic.php" );
                break;
            }
        }
    } break;

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
            case "data":
            case "storedata":
            case "delete":
            {
                include( "ezarticle/xmlrpc/article.php" );
                break;
            }
            default:
                $Error = true;
        }
    } break;
        
    default :
    {
        $Error = true;
    } break;
}

?>
