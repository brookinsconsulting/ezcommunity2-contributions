<?

include_once( "classes/ezuritool.php" );

if ( !isset( $url_array ) )
    $url_array = eZURITool::split( $REQUEST_URI );
$ListType = $url_array[2];

// Supply these to trade listings/views etc..
$ModuleName = "exchange";
$ModuleList = "product/list";
$ModuleView = "product/view";
$ModulePrint = "product/print";

switch ( $ListType )
{
    case "product":
    {
        $Action = $url_array[3];
        switch ( $Action )
        {
            // intentional fall through
            case "new":
            case "edit":
            case "update":
            case "delete":
            case "insert":
            {
                include( "ezexchange/user/productedit.php" );
                break;
            }
            case "print":
                $ProductID = $url_array[4];
                $CategoryID = $url_array[5];
                $cachedFile = "ezexchange/cache/productprint," .$ProductID . "," . $CategoryID .".cache";
            case "quote":
            case "offer":
            case "rfq":
            {
                $ProductID = $url_array[4];
                include( "ezexchange/user/quoteedit.php" );
                break;
            }
            case "view":
            {
                $ProductID = $url_array[4];
                $CategoryID = $url_array[5];
                include( "ezexchange/user/productview.php" );
                break;
            }
            case "list":
            {
                $CategoryID = $url_array[4];
                if ( $PageCaching == "enabled" )
                {
                    $cachedFile = "ezexchange/cache/productlist," . $CategoryID .".cache";
                    if ( file_exists( $cachedFile ) )
                    {
                        include( $cachedFile );
                    }
                    else
                    {
                        $GenerateStaticPage = "true";
                        include( "eztrade/user/productlist.php" );
                    }
                }
                else
                {
                    include( "eztrade/user/productlist.php" );
                }
                break;
            }

            default:
            {
                include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /exchange/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
                break;
            }
        }
        break;
    }

    case "error":
    {
        include( "ezexchange/user/error.php" );
        break;
    }

    default :
        include_once( "classes/ezhttptool.php" );
        eZHTTPTool::header( "Location: /exchange/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
        break;
}

?>
