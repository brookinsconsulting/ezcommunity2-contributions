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
}

?>
