
<?
$url_array = explode( "/", $REQUEST_URI );

include_once( "ezuser/classes/ezpermission.php" );
include_once( "classes/ezhttptool.php" );
$user = eZUser::currentUser();
if( eZPermission::checkPermission( $user, "eZForum", "ModuleEdit" ) == false )
{
    eZHTTPTool::header( "Location: /error/403" );
    exit();
}
switch ( $url_array[2] )
{
    case "forumlist":
    {
        $CategoryID = $url_array[3];
        include( "ezforum/admin/forumlist.php" );
    }
    break;

    case "unapprovedlist":
    {
        if ( $url_array[3] == "parent" )
            $Offset = $url_array[4];
        else
            $Offset = 0;
        include( "ezforum/admin/unapprovedlist.php" );
    }
    break;
    case "unapprovededit":
    {
        include( "ezforum/admin/unapprovededit.php" );
    }
    break;

    
    case "messagelist":
    {
        $ForumID = $url_array[3];

        if ( $url_array[4] == "parent" )
            $Offset = $url_array[5];
        else
            $Offset = 0;

        include( "ezforum/admin/messagelist.php" );
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
        include( "ezforum/admin/search.php" );
    }
    break;


    case "message":
    {
        $MessageID = $url_array[3];
        include( "ezforum/admin/message.php" );
    }
    break;

    case "messageedit":
    {
        if ( $url_array[3] == "edit" )
        {
            $Action = "edit";
            $MessageID = $url_array[4];
            include( "ezforum/admin/messageedit.php" );
        }
        if ( $url_array[3] == "update" )
        {
            $Action = "update";
            $MessageID = $url_array[4];
            include( "ezforum/admin/messageedit.php" );
        }
        if ( $url_array[3] == "delete" )
        {
            $Action = "delete";
            $MessageID = $url_array[4];
            include( "ezforum/admin/messageedit.php" );
        }
    }
    break;
    case "forumedit":
    {
        if ( $url_array[3] == "new" )
        {
            $Action = "new";
            include( "ezforum/admin/forumedit.php" );
        }

        if ( $url_array[3] == "insert" )
        {
            $Action = "insert";
            include( "ezforum/admin/forumedit.php" );
        }

        if ( $url_array[3] == "edit" )
        {
            $Action = "edit";
            $ForumID = $url_array[4];
            include( "ezforum/admin/forumedit.php" );
        }
        if ( $url_array[3] == "update" )
        {
            
            $Action = "update";
            $ForumID = $url_array[4];
            include( "ezforum/admin/forumedit.php" );
        }
        if ( $url_array[3] == "delete" )
        {
            $Action = "delete";
            $ForumID = $url_array[4];
            include( "ezforum/admin/forumedit.php" );
        }
    }
    break;

    case "categoryedit":
    {
        if ( $url_array[3] == "new" )
        {
            $Action = "new";
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
    case "norights":
    {
        include( "ezforum/admin/norights.php" );
    }
    break;

    default :
        print( "<h1>Sorry, Your link page could not be found. </h1>" );
        break;
}

?>
