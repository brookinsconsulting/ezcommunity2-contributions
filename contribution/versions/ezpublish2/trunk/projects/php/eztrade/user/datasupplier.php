<?
$PageCaching = $ini->read_var( "eZTradeMain", "PageCaching");

include_once( "ezuser/classes/ezuser.php" );
include_once( "eztrade/classes/ezpricegroup.php" );
include_once( "classes/ezhttptool.php" );
$user = eZUser::currentUser();

$RequireUser = $ini->read_var( "eZTradeMain", "RequireUserLogin" ) == "enabled" ? true : false;
$ShowPrice = $RequireUser ? get_class( $user ) == "ezuser" : true;

$PriceGroup = 0;
if ( get_class( $user ) == "ezuser" )
{
    $PriceGroup = eZPriceGroup::correctPriceGroup( $user->groups( true ) );
}
if ( !$ShowPrice )
    $PriceGroup = -1;

switch ( $url_array[2] )
{
    case "productlist" :
    {
        $CategoryID = $url_array[3];
        $Offset = $url_array[4];
        if ( !is_numeric( $Offset ) )
            $Offset = 0;
        if ( $PageCaching == "enabled" )
        {
            include_once( "classes/ezcachefile.php" );
            $CacheFile = new eZCacheFile( "eztrade/cache/",
                                          array( "productlist", $CategoryID, $Offset, $PriceGroup ),
                                          "cache", "," );
            if ( $CacheFile->exists() )
            {
                include( $CacheFile->filename( true ) );
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

    case "productview" :
        if ( $PageCaching == "enabled" )
        {
            $ProductID = $url_array[3];
            $CategoryID = $url_array[4];

            include_once( "classes/ezcachefile.php" );
            $CacheFile = new eZCacheFile( "eztrade/cache/",
                                          array( "productview", $ProductID, $CategoryID, $PriceGroup ),
                                          "cache", "," );
            if ( $CacheFile->exists() )
            {
                include( $CacheFile->filename( true ) );
            }
            else
            {
                $GenerateStaticPage = "true";
                include( "eztrade/user/productview.php" );
            }
        }
        else
        {
            $ProductID = $url_array[3];
            $CategoryID = $url_array[4];
            include( "eztrade/user/productview.php" );
        }

        break;
        
    case "print" :
    case "productprint" :
        if ( $PageCaching == "enabled" )
        {
            $ProductID = $url_array[3];
            $CategoryID = $url_array[4];

            include_once( "classes/ezcachefile.php" );
            $CacheFile = new eZCacheFile( "eztrade/cache/",
                                          array( "productprint", $ProductID, $CategoryID, $PriceGroup ),
                                          "cache", "," );
            if ( $CacheFile->exists() )
            {
                include( $CacheFile->filename( true ) );
            }
            else
            {
                $GenerateStaticPage = "true";
                include( "eztrade/user/productview.php" );
            }
        }
        else
        {
            $ProductID = $url_array[3];
            $CategoryID = $url_array[4];
            include( "eztrade/user/productview.php" );
        }

        break;

    case "cart" :

        if ( $url_array[3] == "add" )
        {
            $Action = "AddToBasket";
            $ProductID = $url_array[4];
        }

        if ( $url_array[3] == "remove" )
        {
            $Action = "RemoveFromBasket";
            $CartItemID = $url_array[4];
        }

        if ( isset( $WishList ) )
        {
            include( "eztrade/user/wishlist.php" );

//              Header( "Location: /trade/wishlist/add/$ProductID" );
//              exit();
        }
        else
        {
            include( "eztrade/user/cart.php" );
        }

        break;

    case "wishlist" :
    {
        if ( $url_array[3] == "add" )
        {
            $Action = "AddToBasket";
            $ProductID = $url_array[4];
        }
        
        if ( $url_array[3] == "movetocart" )
        {
            $Action = "MoveToCart";
            $WishListItemID = $url_array[4];
        }

        if ( $url_array[3] == "remove" )
        {
            $Action = "RemoveFromWishlist";
            $WishListItemID = $url_array[4];
        }

        include( "eztrade/user/wishlist.php" );
    }
    break;

    case "viewwishlist" :
    {
        if ( $url_array[3] == "movetocart" )
        {
            $Action = "MoveToCart";
            $WishListItemID = $url_array[4];
        }
        
        include( "eztrade/user/viewwishlist.php" );
    }
    break;
    
    case "sendwishlist" :
    {
        include( "eztrade/user/sendwishlist.php" );
    }
    break;

    case "findwishlist" :
    {
        include( "eztrade/user/findwishlist.php" );
    }
    break;

    case "customerlogin" :
        include( "eztrade/user/customerlogin.php" );
        break;

    case "checkout" :
    {
        include( "eztrade/user/checkout.php" );
    }
        break;

    case "payment" :
    {
        include( "eztrade/user/payment.php" );
    }
        break;
        
    case "ordersendt" :
        $OrderID = $url_array[3];
        include( "eztrade/user/ordersendt.php" );
        break;

    case "search" :
    {
        if ( $url_array[3] == "move" )
        {
            $Query = urldecode( $url_array[4] );
            $Offset = urldecode ( $url_array[5] );
        }
        include( "eztrade/user/productsearch.php" );
    }
    break;

    case "extendedsearch" :
    {
        $Limit = 10;
        if ( $url_array[3] == "move" )
        {
            $Text = urldecode( $url_array[4] );
            $PriceRange = urldecode( $url_array[5] );
            $CategoryArray = urldecode ( $url_array[6] );
            $Offset = urldecode ( $url_array[7] );

            $Action = "SearchButton";
            $Next = true;
        }
                
        include( "eztrade/user/extendedsearch.php" );
    }
    break;
    
    // XML rpc interface
    case "xmlrpc" :
    {
        include( "eztrade/xmlrpc/xmlrpcserver.php" );
    }
    break;

    // XML rpc interface
    case "xmlrpcimport" :
    {
        ob_end_clean();
        ob_start();
        include( "eztrade/xmlrpc/xmlrpcserverimport.php" );
        ob_end_flush();
        exit();
    }
    break;

        
    default :
        print( "<h1>Sorry, Your Product page could not be found. </h1>" );
        break;
}

?>
