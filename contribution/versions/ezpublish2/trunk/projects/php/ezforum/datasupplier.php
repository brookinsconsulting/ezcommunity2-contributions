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
            if ( $url_array[4] == "message" )
            {
                $message_id = $url_array[6];
                $forum_id = $url_array[5];
                include( "ezforum/message.php" );
            }
            else if ( $url_array[4] == "newpost" )
            {
                $forum_id = $url_array[5];
                include( "ezforum/newmessage.php" );                
            }
            else if ( $url_array[4] == "replymessage" )
            {
                $forum_id = $url_array[5];
                $category_id = $url_array[5];
                $reply_id = $url_array[5];
                include( "ezforum/replymessage.php" );                
            }

            else                
            {
                $forum_id = $url_array[4];
                include( "ezforum/forum.php" );
            }

        }
        else
        {
            $category_id = $url_array[3];
            include( "ezforum/category.php" );
        }
        break;
}

?>
