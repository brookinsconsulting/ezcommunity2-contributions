<?

$PageCaching = $ini->read_var( "eZTradeMain", "PageCaching");


switch ( $url_array[2] )
{
    case "productlist" :
        if ( $PageCaching == "enabled" )
        {
            print( "cached version<br>" );

            $CategoryID = $url_array[3];

            $cachedFile = "eztrade/cache/productlist," . $CategoryID .".cache";
            if ( file_exists( $cachedFile ) )
            {
                print( "pure static" );
                
                include( $cachedFile );
            }
            else
            {
                print( "first time generated" );                
                $GenerateStaticPage = "true";
                include( "eztrade/user/productlist.php" );
            }            
        }
        else
        {
            print( "uncached version" );
            $CategoryID = $url_array[3];        
            include( "eztrade/user/productlist.php" );
        }
        
        break;
    case "productview" :
        if ( $PageCaching == "enabled" )
        {
            print( "cached version<br>" );
            $ProductID = $url_array[3];
            $CategoryID = $url_array[4];

            $cachedFile = "eztrade/cache/productview," .$ProductID . "," . $CategoryID .".cache";
            if ( file_exists( $cachedFile ) )
            {
                print( "pure static" );
                
                include( $cachedFile );
            }
            else
            {
                print( "first time generated" );                
                $GenerateStaticPage = "true";
                include( "eztrade/user/productview.php" );
            }            
        }
        else
        {
            print( "uncached version" );
            
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
            print( "wishlist<br>" );
            include( "eztrade/user/wishlist.php" );
        }
        else
        {
            print( "cart<br>" );            
            include( "eztrade/user/cart.php" );
        }
        
        break;
        
    case "wishlist" :
        $CartType = "WishList";        
    include( "eztrade/user/wishlist.php" );
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
        print( "<h1>Sorry, Your PRODUCT page could not be found. </h1>" );
        break;
}

?>
