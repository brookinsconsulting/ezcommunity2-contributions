<?
//print $REQUEST_URI;
$url_array = explode( "/", $REQUEST_URI );
switch ( $url_array[2] )
{
    case "" :
        include( "eztodo/todolist.php" );        
        break;
    case "todolist" :
        include( "eztodo/todolist.php" );        
        break;
    case "todoedit" :
        include( "eztodo/todoedit.php" );
        break;

    case "todoinfo" :
        include( "eztodo/todoinfo.php" );
        break;

    default:
        print( "<h1>Sorry, Your link page could not be found. </h1>" );
        break;
}

?>
