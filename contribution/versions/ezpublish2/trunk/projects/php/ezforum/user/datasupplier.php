<?php
include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();
$GlobalSectionID = $ini->read_var( "eZForumMain", "DefaultSection" );

function &errorPage( $PrimaryName, $PrimaryURL, $type )
{
    $ini =& $GLOBALS["GlobalSiteIni"];

    $t = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                         "ezforum/user/intl", $ini->read_var( "eZForumMain", "Language" ), "message.php" );

    $t->set_file( "page", "messageerror.tpl"  );
    $t->set_var( "primary_url", $PrimaryURL  );
    $t->set_var( "primary_url_name", $t->Ini->read_var( "strings", $PrimaryName  ) );
    if( $type == 404 )
    {
        $t->set_var( "error_1", $t->Ini->read_var( "strings", error_missing_page_1  ) );
        $t->set_var( "error_2", $t->Ini->read_var( "strings", error_missing_page_2  ) );
        $t->set_var( "error_3", $t->Ini->read_var( "strings", error_missing_page_3  ) );
    }
    else
    {
        $t->set_var( "error_1", $t->Ini->read_var( "strings", error_forbidden_page_1  ) );
        $t->set_var( "error_2", $t->Ini->read_var( "strings", error_forbidden_page_2  ) );
        $t->set_var( "error_3", $t->Ini->read_var( "strings", error_forbidden_page_3  ) );
    }
    $t->setAllStrings();

    $error = $t->parse( "error", "page" );
    $Info =& stripslashes( $error );
    $error =& urlencode( $Info );
    return $error;
}

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

        if ( $url_array[4] == "parent" )
            $Offset = $url_array[5];

        include( "ezforum/user/messagelist.php" );
    }
    break;

    case "messagelistflat":
    {
        $ForumID = $url_array[3];

        if ( $url_array[4] == "parent" )
            $Offset = $url_array[5];

        include( "ezforum/user/messagelistflat.php" );
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
        if ( $url_array[3] == "parent" )
        {
            $QueryString = urldecode( $url_array[4] );
            $Offset = $url_array[5];
            if  ( !is_numeric( $Offset ) )
                $Offset = 0;
        }

        include( "ezforum/user/search.php" );
    }
    break;

    default :
    {
        eZHTTPTool::header( "Location: /error/404?Info=" . errorPage( "forum_main", "/forum/categorylist/", 404 ) );
    }
    break;        
}

?>
