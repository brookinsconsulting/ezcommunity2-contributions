<?

include_once( "eztrade/classes/ezproducttype.php" );

$PageCaching = $ini->read_var( "eZTradeMain", "PageCaching");

switch ( $url_array[2] )
{
    case "productlist" :
        if ( $PageCaching == "enabled" )
        {
            $CategoryID = $url_array[3];

            $cachedFile = "eztrade/cache/productlist," . $CategoryID .".cache";
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
            $CategoryID = $url_array[3];
            include( "eztrade/user/productlist.php" );
        }

        break;
        
    case "productview" :
        if ( $PageCaching == "enabled" )
        {
            $ProductID = $url_array[3];
            $CategoryID = $url_array[4];

            $cachedFile = "eztrade/cache/productview," .$ProductID . "," . $CategoryID .".cache";
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

            $cachedFile = "eztrade/cache/productprint," .$ProductID . "," . $CategoryID .".cache";
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
        include( "eztrade/user/checkout.php" );
        break;

    case "ordersendt" :
        $OrderID = $url_array[3];
        include( "eztrade/user/ordersendt.php" );
        break;

    case "search" :

        include( "eztrade/user/productsearch.php" );
        break;

    default :
        print( "<h1>Sorry, Your Product page could not be found. </h1>" );
        break;
}

?>
