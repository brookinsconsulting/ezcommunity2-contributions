<?
$PageCaching = $ini->read_var( "eZTradeMain", "PageCaching");

include_once( "eztrade/classes/ezpricegroup.php" );

$user = eZUser::currentUser();
$PriceGroup = 0;
if ( get_class( $user ) == "ezuser" )
{
    $PriceGroup = eZPriceGroup::correctPriceGroup( $user->groups( true ) );
}

switch ( $url_array[2] )
{
    case "productlist" :
    {
        $CategoryID = $url_array[3];
        $Offset = $url_array[4];
        if ( $PageCaching == "enabled" )
        {
            include_once( "classes/ezcachefile.php" );
            $cache = new eZCacheFile( "eztrade/cache/",
                                      array( "productlist", $CategoryID, $Offset, $PriceGroup ),
                                      "cache", "," );
//              $cachedFile = "eztrade/cache/productlist," . $CategoryID .".cache";
//              if ( file_exists( $cachedFile ) )
            if ( $cache->exists() )
            {
                include( $cache->filename( true ) );
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

            $cachedFile = "eztrade/cache/productview," .$ProductID . "," . $CategoryID . "," . $PriceGroup .".cache";
            if ( file_exists( $cachedFile ) )
            {
                include( $cachedFile );
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

            $cachedFile = "eztrade/cache/productprint," .$ProductID . "," . $CategoryID . "," . $PriceGroup .".cache";
            if ( file_exists( $cachedFile ) )
            {
                include( $cachedFile );
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
        include( "eztrade/user/productsearch.php" );
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
        include( "eztrade/xmlrpc/xmlrpcserverimport.php" );
    }
    break;

        
    default :
        print( "<h1>Sorry, Your Product page could not be found. </h1>" );
        break;
}

?>
