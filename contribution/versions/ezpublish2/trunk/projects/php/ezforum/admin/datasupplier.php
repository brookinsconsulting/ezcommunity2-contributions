<?
$url_array = explode( "/", $REQUEST_URI );

switch ( $url_array[2] )
{
    case "forumlist":
    {
        $CategoryID = $url_array[3];
        include( "ezforum/admin/forumlist.php" );
    }
    break;

    case "messagelist":
    {
        $CategoryID = $url_array[3];
        $ForumID = $url_array[4];
        include( "ezforum/admin/messagelist.php" );
    }
    break;

//      case "message":
//      {
//          $CategoryID = $url_array[3];
//          $ForumID = $url_array[4];
//          $MessageID = $url_array[5];
//          include( "ezforum/admin/message.php" );
//      }
//      break;

    case "messageedit":
    {
        if ( $url_array[3] == "edit" )
        {
            $Action = "edit";
            $CategoryID = $url_array[4];
            $ForumID = $url_array[5];
            $MessageID = $url_array[6];
            include( "ezforum/admin/messageedit.php" );
        }
        if ( $url_array[3] == "update" )
        {
            $Action = "update";
            $CategoryID = $url_array[4];
            $ForumID = $url_array[5];
            $MessageID = $url_array[6];
            include( "ezforum/admin/messageedit.php" );
        }
        if ( $url_array[3] == "delete" )
        {
            $Action = "delete";
            $CategoryID = $url_array[4];
            $ForumID = $url_array[5];
            $MessageID = $url_array[6];
            include( "ezforum/admin/messageedit.php" );
        }
    }
    break;
    case "forumedit":
    {
        if ( $url_array[3] == "new" )
        {
            include( "ezforum/admin/forumedit.php" );
        }

        if ( $url_array[3] == "insert" )
        {
            $Action = "insert";
            $CategoryID = $url_array[4];
            $ForumID = $url_array[5];
            include( "ezforum/admin/forumedit.php" );
        }

        if ( $url_array[3] == "edit" )
        {
            $Action = "edit";
            $CategoryID = $url_array[4];
            $ForumID = $url_array[5];
            include( "ezforum/admin/forumedit.php" );
        }
        if ( $url_array[3] == "update" )
        {
            
            $Action = "update";
            $CategoryID = $url_array[4];
            $ForumID = $url_array[5];
            include( "ezforum/admin/forumedit.php" );
        }
        if ( $url_array[3] == "delete" )
        {
            $Action = "delete";
            $CategoryID = $url_array[4];
            $ForumID = $url_array[5];
            include( "ezforum/admin/forumedit.php" );
        }
    }
    break;

    case "categoryedit":
    {
        if ( $url_array[3] == "new" )
        {
            include( "ezforum/admin/categoryedit.php" );
        }
        if ( $url_array[3] == "insert" )
        {
            $Action = "insert";
            include( "ezforum/admin/categoryedit.php" );
        }
        if ( $url_array[3] == "edit" )
        {
            $Action = "edit";
            $CategoryID = $url_array[4];
            include( "ezforum/admin/categoryedit.php" );
        }
        if ( $url_array[3] == "update" )
        {
            $Action = "update";
            $CategoryID = $url_array[4];
            include( "ezforum/admin/categoryedit.php" );
        }
        if ( $url_array[3] == "delete" )
        {
            $Action = "delete";
            $CategoryID = $url_array[4];
            include( "ezforum/admin/categoryedit.php" );
        }
    }
    break;

    case "categorylist" :
    {
        include( "ezforum/admin/categorylist.php" );
    }
    break;

    default :
        print( "<h1>Sorry, Your link page could not be found. </h1>" );
        break;
}

?>
