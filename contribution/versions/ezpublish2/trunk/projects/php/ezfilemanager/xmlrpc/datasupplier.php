<?
define( "EZFILEMANAGER_NONEXISTING_FILE", 1 );
define( "EZFILEMANAGER_BAD_FILE", 2 );

switch ( $RequestType )
{
    case "file" :
    {
        switch( $Command )
        {
//              case "list":
//              {
//                  include( "ezarticle/xmlrpc/typelist.php" );
//                  break;
//              }
            case "data":
            case "storedata":
            case "delete":
            {
                include( "ezfilemanager/xmlrpc/file.php" );
                break;
            }
        }
    } break;
        
    default :
    {
        $Error = true;
    } break;
}

?>
