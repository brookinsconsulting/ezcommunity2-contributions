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


    case "userlogin" :
    {
        $ForumID = $url_array[3];
        include( "ezforum/userlogin.php" );
    }    
    break;
    
    case "categorylist":
    {
        include( "ezforum/categorylist.php" );
    }
    break;

    case "newpost" :
    {
        $ForumID = $url_array[3];
        
        include( "ezforum/newmessage.php" );                
    }    
    break;
    
        
    case "category" :
        if ( $url_array[3] == "forum" )
        {
            if ( $url_array[4] == "message" )
            {
                if ( $PageCaching == "enabled" )
                {
                    print( "cached version<br>" );

                    $message_id = $url_array[5];
                    $forum_id = $url_array[6];
                    
                    $cachedFile = "ezforum/cache/message," . $message_id . ".cache";
                    
                    if ( file_exists( $cachedFile ) )
                    {
                        print( "pure static" );
                
                        include( $cachedFile );
                    }
                    else
                    {
                        print( "first time generated" );   
                        $GenerateStaticPage = "true";
                        
                        include( "ezforum/message.php" );                        
                    }            
                    
                }
                else
                {
                    print( "uncached  version<br>" );

                    $message_id = $url_array[5];
                    $forum_id = $url_array[6];
                    include( "ezforum/message.php" );
                }
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

                    $Limit = $ini->read_var( "eZForumMain", "MessageLimit" );
                    
                    $forum_id = $url_array[4];
                    $Action = $url_array[5];

                    if ( !isSet( $Offset ) )
                        $Offset = 0;
                    
                    $cachedFile = "ezforum/cache/forum," . $forum_id . "," .$Offset .",". $Limit . ".cache";
                    
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
