<?
//print $REQUEST_URI;
 
$url_array = explode( "/", $REQUEST_URI );

include("eznews/classes/eznewsitem.php");


switch ( $url_array[2] )
{
    case "product" :
        print( "<h1>Product</h1>" );
        break;
    case "search" :
        print( "<h1>Product search</h1>" );
        break;
    default :
        print( "<h1>Sorry, Your news page could not be found. </h1>" );
        break;
}
 
?> 
