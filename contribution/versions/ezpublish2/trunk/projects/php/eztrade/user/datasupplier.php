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
                include( "eztrade/productlist.php" );                
            }            
        }
        else
        {
            print( "uncached version" );
            $CategoryID = $url_array[3];        
            include( "eztrade/productlist.php" );
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
                include( "eztrade/productview.php" );
            }            
        }
        else
        {
            print( "uncached version" );
            
            $ProductID = $url_array[3];
            $CategoryID = $url_array[4];
            include( "eztrade/productview.php" );
        }
        
        break;
        
    case "cart" :

        if ( $url_array[3] == "add" )
        {
            $Action = "AddToBasket";
            $ProductID = $url_array[4];
        }
        else
        {
        }

        if ( isset( $WishList ) )
        {
            print( "wishlist<br>" );
            include( "eztrade/wishlist.php" );            
        }
        else
        {
            print( "cart<br>" );            
            include( "eztrade/cart.php" );
        }
        
        break;
        
    case "wishlist" :
        $CartType = "WishList";        
         include( "eztrade/wishlist.php" );    
        break;

    case "customerlogin" :
        include( "eztrade/customerlogin.php" );        
        break;
        
    case "checkout" :
        include( "eztrade/checkout.php" );        
        break;

    case "ordersendt" :
        include( "eztrade/ordersendt.php" );        
        break;

        

    case "search" :
        
        include( "eztrade/productsearch.php" );
        break;
    default :
        print( "<h1>Sorry, Your PRODUCT page could not be found. </h1>" );
        break;
}

?>
