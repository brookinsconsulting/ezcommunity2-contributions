<?
//print $REQUEST_URI;

//  $url_array = explode( "/", $REQUEST_URI );

$PageCaching = $ini->read_var( "eZForumMain", "PageCaching");

switch ( $url_array[2] )
{
    case "" :
        include( "ezforum/main.php" );
        break;

    case "search" :
        include( "ezforum/search.php" );
        break;
        
    case "reply" :
    {
        $Action = "Reply";
        $ReplyID = $url_array[3];
        include( "ezforum/replymessage.php" );
    }    
    break;

    case "replymessage" :
    {
        $ReplyID = $url_array[3];
        include( "ezforum/replymessage.php" );                
    }
    break;
    
        
    case "category" :
        if ( $url_array[3] == "forum" )
        {
            if ( $url_array[4] == "message" )
            {
                $message_id = $url_array[5];
                $forum_id = $url_array[6];
                include( "ezforum/message.php" );
            }
            else if ( $url_array[4] == "newpost" )
            {
                $forum_id = $url_array[5];
                include( "ezforum/newmessage.php" );                
            }
            else if ( $url_array[4] == "post" )
            {
                $Action = "post";
                $forum_id = $url_array[5];
                include( "ezforum/forum.php" );                
            }
            else                
            {
                if ( $PageCaching == "enabled" )
                {
                    print( "cached version<br>" );
                
                    $forum_id = $url_array[4];
                    $Action = $url_array[5];

                    $cachedFile = "ezforum/cache/forum," . $forum_id . ".cache";
                    
                    if ( file_exists( $cachedFile ) )
                    {
                        print( "pure static" );
                
                        include( $cachedFile );
                    }
                    else
                    {
                        print( "first time generated" );                
                        $GenerateStaticPage = "true";
                        
                        include( "ezforum/forum.php" );                        
                    }            
                    
                }
                else
                {
                    print( "uncached  version<br>" );
                
                    $forum_id = $url_array[4];
                    $Action = $url_array[5];
                    include( "ezforum/forum.php" );
                }
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
