<?php
switch ( $url_array[2] )
{
    case "userlogin":
    {
        $Action = $url_array[3];
        
        switch( $Action )
        {
            case "edit":
            case "delete":
            {
                $MessageID = $url_array[4];
                include( "ezforum/user/userlogin.php" );
            }
            break;
        }
        if ( $url_array[3] == "new" )
        {         
            $Action = $url_array[3];
            $ForumID = $url_array[4];
            $MessageID = $url_array[4];
            include( "ezforum/user/userlogin.php" );
        }

        if ( $url_array[3] == "reply" )
        {         
            $Action = $url_array[3];
            $ReplyToID = $url_array[4];
            include( "ezforum/user/userlogin.php" );
        }
        
        if ( $url_array[3] == "newsimple" )
        {
            $ForumID = $url_array[4];
            include( "ezforum/user/userlogin.php" );
        }

        if ( $url_array[3] == "replysimple" )
        {
            $ForumID = $url_array[4];
            $ReplyToID = $url_array[5];
            include( "ezforum/user/userlogin.php" );
        }
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

    case "messagesimpleedit":
    case "messagesimplereply":
    case "reply":
    case "messageedit":
    case "newpost":
    case "newsimple":
    {
        $Action = $url_array[3];
        $ID = $url_array[4];

        switch( $Action )
        {
            case "reply":
            {
                $ReplyToID = $ID;
                $ForumID = $url_array[5];
            }
            break;

            case "new":
            {
                $ForumID = $ID;
            }
            break;

            case "edit":
            case "completed":
            case "insert":
            case "update":
            case "delete":
            case "dodelete":
            {
                $MessageID = $ID;
            }
            break;
        }
        include( "ezforum/user/messageedit.php" );

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

    default :
    {
        print( "<h1>Error 404 - forum file not found </h1>" );
    }
    break;        
}

?>
