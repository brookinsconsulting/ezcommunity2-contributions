<?php

switch ( $url_array[2] )
{

    case "userlogin" :
    {
        $Action = $url_array[3];
        $ForumID = $url_array[4];
        $MessageID = $url_array[4];
        include( "ezforum/user/userlogin.php" );
    }    
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
            $Action = "new";
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

    default :
    {
        print( "<h1>Error 404 - forum file not found </h1>" );
    }
    break;        
}

?>
