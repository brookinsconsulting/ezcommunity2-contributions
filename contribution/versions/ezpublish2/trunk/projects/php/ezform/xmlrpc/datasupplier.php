<?
switch ( $RequestType )
{
    case "form" :
    {
        switch( $Command )
        {
            case "list":
            {
                include( "ezform/xmlrpc/formlist.php" );
                break;
            }
//              case "data":
//              case "storedata":
//              case "delete":
//              {
//                  include( "ezarticle/xmlrpc/form.php" );
//                  break;
//              }
        }
    } break;
        
    default :
    {
        $Error = true;
    } break;
}

?>
