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
    case "search" :
    {
        include( "eztrade/user/productsearch.php" );
        break;
    }
    case "customer":
    {
        $ModuleUserNew = "customer";
        $Action = $url_array[3];
        if ( $Action == "new" )
            $Action = "New";
        else if ( $Action == "insert" )
            $Action = "Insert";
        else if ( $Action == "edit" )
        {
            $Action = "Edit";
            $UserID = $url_array[4];
        }
        else if ( $Action == "update" )
        {
            $Action = "Update";
            $UserID = $url_array[4];
        }
        else
        {
            include_once( "classes/ezhttptool.php" );
            eZHTTPTool::header( "Location: /exchange/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
            break;
        }
        include( "ezexchange/user/customeredit.php" );
        break;
    }
    case "supplier":
    {
        $ModuleUserNew = "supplier";
        $Action = $url_array[3];
        if ( $Action == "new" )
            $Action = "New";
        else if ( $Action == "insert" )
            $Action = "Insert";
        else if ( $Action == "edit" )
        {
            $Action = "Edit";
            $UserID = $url_array[4];
        }
        else if ( $Action == "update" )
        {
            $Action = "Update";
            $UserID = $url_array[4];
        }
        else
        {
            include_once( "classes/ezhttptool.php" );
            eZHTTPTool::header( "Location: /exchange/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
            break;
        }
        include( "ezexchange/user/supplieredit.php" );
        break;
    }
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
            case "view":
            {
                $ProductID = $url_array[4];
                $CategoryID = $url_array[5];
                include( "ezexchange/user/productview.php" );
                break;
            }
            case "quote":
            case "offer":
            case "rfq":
            {
                $ProductID = $url_array[4];
                $CategoryID = $url_array[5];
                include( "ezexchange/user/quoteedit.php" );
                break;
            }
            case "request":
            {
                if ( !isset( $QuoteID ) )
                    $QuoteID = $url_array[6];
                $ProductID = $url_array[4];
                $CategoryID = $url_array[5];
                include( "ezexchange/user/quoteedit.php" );
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
