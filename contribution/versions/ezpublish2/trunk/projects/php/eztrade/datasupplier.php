<?
//print $REQUEST_URI;

$url_array = explode( "/", $REQUEST_URI );

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
        if ( isSet( $WishList ) )
        {
            $CartType = "WishList";
            print( "wishlist" );
        }
        else
        {
            $CartType = "Cart";            
        }

        if ( $url_array[3] == "add" )
        {
            $Action = "AddToBasket";
            $ProductID = $url_array[4];
            include( "eztrade/cart.php" );
        }
        else
        {
            include( "eztrade/cart.php" );
        }
        break;
        
    case "wishlist" :
        $CartType = "WishList";        
         include( "eztrade/cart.php" );    
        break;

    case "checkout" :
        include( "eztrade/checkout.php" );        
        break;
        

    case "search" :
        print( "<h1>Product search</h1>" );        
        break;
    default :
        print( "<h1>Sorry, Your PRODUCT page could not be found. </h1>" );
        break;
}

?>
