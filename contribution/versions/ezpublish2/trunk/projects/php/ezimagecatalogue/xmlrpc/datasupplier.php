<?
define( "EZIMAGECATALOGUE_NONEXISTING_IMAGE", 1 );
define( "EZIMAGECATALOGUE_CONVERT_ERROR", 2 );
define( "EZIMAGECATALOGUE_SIZE_MISSING", 3 );

switch ( $RequestType )
{
    case "category" :
    {
        switch( $Command )
        {
            case "list":
            {
                include( "ezimagecatalogue/xmlrpc/categorylist.php" );
                break;
            }
//                  case "data":
//                  case "storedata":
//                  case "delete":
//                  {
//                      include( "ezarticle/xmlrpc/category.php" );
//                      break;
//                  }
            default:
                $Error = true;
        }
    } break;

    case "image" :
    {
        switch( $Command )
        {
            case "data":
            case "storedata":
            case "delete":
            {
                include( "ezimagecatalogue/xmlrpc/image.php" );
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

?>
