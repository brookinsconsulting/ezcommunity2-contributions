<?
//print $REQUEST_URI;

$url_array = explode( "/", $REQUEST_URI );

switch ( $url_array[2] )
{
    case "" :
        include( "ezforum/main.php" );
        break;
    case "category" :
        if ( $url_array[3] == "forum" )
        {
            include( "ezforum/forum.php" );
        }
        else
        {
            include( "ezforum/category.php" );            
        }        
        break;
    case "gotolink" :
        include( "ezlink/gotolink.php" );        
        break;
    case "search" :
        include( "ezlink/search.php" );
        break;
    default :
        print( "<h1>Sorry, Your link page could not be found. </h1>" );
        break;
}

?>
