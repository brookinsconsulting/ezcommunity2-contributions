<?php

$PageCaching = $ini->read_var( "eZForumMain", "PageCaching");

unset( $GenerateStaticPage );

switch ( $url_array[2] )
{

    case "" :
        include( "ezforum/main.php" );
        break;

    case "categorylist":
    {
        include( "ezforum/user/categorylist.php" );
    }
    break;
        
    case "forumlist":
    {
        $CategoryID = $url_array[3];
        include( "ezforum/user/forumlist.php" );
    }
    break;
    
    case "messagelist":
    {
        $ForumID = $url_array[3];
        include( "ezforum/user/messagelist.php" );
    }
    break;

    case "messageedit";
    {
        if ( $url_array[3] == "new" )
        {
            $ForumID = $url_array[4];
            include( "ezforum/user/messageedit.php" );
        }

        if ( $url_array[3] == "insert" )
        {
            $Action = $url_array[3];
            $ForumID = $url_array[4];
            include( "ezforum/user/messageedit.php" );
        }
    }
    break;

    case "message":
    {
        $MessageID = $url_array[3];
        include( "ezforum/user/message.php" );
    }
    break;
        
    case "search" :
    {
        include( "ezforum/user/search.php" );
    }
        break;
        
    case "reply" :
    {
        if ( $url_array[3] == "reply" )
        {
            $ReplyID = $url_array[4];
            include( "ezforum/user/messagereply.php" );
        }
        if ( $url_array[3] == "insert" )
        {
            $Action = "insert";

            $ReplyID = $url_array[4];
            include( "ezforum/user/messagereply.php" );
        }
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
