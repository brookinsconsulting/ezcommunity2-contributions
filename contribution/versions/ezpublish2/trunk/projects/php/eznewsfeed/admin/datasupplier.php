
<?php

switch ( $url_array[2] )
{
    case "archive":
    {
        $CategoryID = $url_array[3];
        if  ( !isset( $CategoryID ) || ( $CategoryID == "" ) )
            $CategoryID = 0;

        include( "eznewsfeed/admin/newsarchive.php" );
    }
    break;

    case "news":
    {
        if ( $url_array[2]  == "new" )
        {
            $Action == "New";
        }
            
        include( "eznewsfeed/admin/newsedit.php" );
    }
    break;
    
}

?>
