<?php

switch ( $url_array[2] )
{
    case "latest":
    {
        $CategoryID = $url_array[3];
        if  ( !isset( $CategoryID ) || ( $CategoryID == "" ) )
            $CategoryID = 0;

        include( "eznewsfeed/user/newslist.php" );
    }
    break;
}

?>
