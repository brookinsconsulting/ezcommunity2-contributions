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
        if( $url_array[3] == "edit" )
        {
            $Action = "Edit";
            $BugID = $url_array[4];
        }

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
        switch( $url_array[3] )
        {
            case "new" :
            {
                $Action = "New";
                include( "ezbug/user/bugreport.php" );
            }
            break;

            case "edit" :
            {
                $BugID = $url_array[4];
                $Action = "Edit";
                include( "ezbug/user/bugreport.php" );
            }
            break;

            case "update" :
            {
                $BugID = $url_array[4];
                if( $BugID == "" )
                    $BugID = -1;
                $Action = "Update";
                include( "ezbug/user/bugreport.php" );
            }
            break;
            
            case "fileedit" :
            {
                if( $url_array[4] == "new")
                {
                    $Action = "New";
                    $BugID = $url_array[5];
                    include( "ezbug/user/fileedit.php" );
                }
                else if( $url_array[4] == "edit" )
                {
                    $Action = "Edit";
                    $BugID = $url_array[6];
                    $FileID = $url_array[5];
                    include( "ezbug/user/fileedit.php" );
                }
                else if( $url_array[4] == "delete" )
                {
                    $Action = "Delete";
                    $BugID = $url_array[6];
                    $FileID = $url_array[5];
                    include( "ezbug/user/fileedit.php" );
                }
                else
                {
                    include( "ezbug/user/fileedit.php" );
                }
            }
            break;
            case "imageedit" :
            {
                if( $url_array[4] == "new")
                {
                    $Action = "New";
                    $BugID = $url_array[5];
                    include( "ezbug/user/imageedit.php" );
                }
                else if( $url_array[4] == "edit" )
                {
                    $Action = "Edit";
                    $BugID = $url_array[6];
                    $ImageID = $url_array[5];
                    include( "ezbug/user/imageedit.php" );
                }
                else if( $url_array[4] == "delete" )
                {
                    $Action = "Delete";
                    $BugID = $url_array[6];
                    $ImageID = $url_array[5];
                    include( "ezbug/user/imageedit.php" );
                }
                else
                {
                    include( "ezbug/user/imageedit.php" );
                }
            }
            break;
            
            default :
            {
                print( "Error: Bug file not found" );
            }
            break;
        }
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
