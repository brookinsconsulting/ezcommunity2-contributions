<?
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezbug/classes/ezbugmodule.php" );
include_once( "ezbug/classes/ezbug.php" );
include_once( "classes/ezhttptool.php" );

switch ( $url_array[2] )
{
    case "edit" :
    {
        $Action = "Edit";
        $BugID = $url_array[3];

        $user = eZUser::currentUser();
        $bug = new eZBug( $BugID );
        $module = $bug->module();
        $ownerGroup = $module->ownerGroup();
        if ( eZPermission::checkPermission( $user, "eZBug", "BugEdit" ) && get_class( $ownerGroup ) == "ezusergroup" && $ownerGroup->isMember( $user ) )
        {
            include( "ezbug/admin/bugedit.php" );
        }
        else // someone is trying to push the envelope
        {
            eZHTTPTool::header( "Location: /bug/archive/");
            exit();
        }
    }
    break;
    
    case "archive" :        
    {
        $ModuleID = $url_array[3];
        
        include( "ezbug/user/buglist.php" );
    }
    break;

    case "search" :        
    {
        include( "ezbug/user/search.php" );
    }
    break;

    case "view" :        
    case "bugview" :        
    {
        $BugID = $url_array[3];
        
        include( "ezbug/user/bugview.php" );
    }
    break;
    
    
    case "report" :
    {
        include( "ezbug/user/bugreport.php" );
    }
    break;

    case "reportsuccess" :
    {
        include( "ezbug/user/reportsuccess.php" );
    }
    break;

    default :
    {
        print( "Error: Bug file not found" );
    }
    break;
    
}

?>
