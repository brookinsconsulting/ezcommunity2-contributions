<?
$url_array = explode( "/", $REQUEST_URI );

switch ( $url_array[2] )
{
    case "" :
        include( "ezlink/admin/linklist.php" );
        break;
    case "linkedit" :
        
        if ( $url_array[3] == "" )
        {
            include( "ezlink/admin/linkedit.php" );
        }
        else
        {
            include( "ezlink/admin/linkedit.php?LGID=$url_array[3]" );
        }

        break;
    case "groupedit" :
        include( "ezlink/admin/groupedit.php" );
        break;
    case "testbench" :
        include( "eztrade/admin/testbench.php" );
        break;
    case "search" :
        include( "ezlink/admin/search.php" );        
        break;
    default :
        print( "<h1>Sorry, Your link page could not be found. </h1>" );
        break;
}

?>
