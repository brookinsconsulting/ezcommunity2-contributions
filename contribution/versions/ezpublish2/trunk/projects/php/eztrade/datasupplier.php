<?
//print $REQUEST_URI;

$url_array = explode( "/", $REQUEST_URI );

switch ( $url_array[2] )
{
    case "productlist" :
        $CategoryID = $url_array[3];        
        include( "eztrade/productlist.php" );
        break;
    case "productview" :
        $ProductID = $url_array[3];
        $CategoryID = $url_array[4];
        include( "eztrade/productview.php" );
        break;
    case "product" :
        print( "<h1>Product</h1>" );
        break;
    case "search" :
        print( "<h1>Product search</h1>" );        
        break;
    default :
        print( "<h1>Sorry, Your PRODUCT page could not be found. </h1>" );
        break;
}

?>
