<?
//print $REQUEST_URI;

$url_array = explode( "/", $REQUEST_URI );

switch ( $url_array[2] )
{
    case "" :
        include( "eztrade/admin/categorylist.php" );
        break;
    case "testbench" :
        include( "eztrade/admin/testbench.php" );
        break;
    case "search" :
        print( "<h1>Product search</h1>" );        
        break;
    default :
        print( "<h1>Sorry, Your PRODUCT page could not be found. </h1>" );
        break;
}

?>
