<?
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "ezbug/classes/ezbugmodule.php" );
include_once( "ezbug/classes/ezbug.php" );
include_once( "classes/ezhttptool.php" );

function hasPermission( $bugID )
{
    $user = eZUser::currentUser();
    $bug = new eZBug( $bugID );
    $module = $bug->module();
    if ( get_class( $module ) == "ezbugmodule" && eZObjectPermission::hasPermission( $module->id(), "bug_module", "w" ) )
    {
        return true;
    }
    else
    {
        return false;
    }
}

switch ( $url_array[2] )
{
    case "edit" :
    {
        if( $url_array[3] == "edit" && hasPermission( $url_array[4] ) )
        {
            $Action = "Edit";
            $BugID = $url_array[4];
            include( "ezbug/admin/bugedit.php" );
        }
        else if( $url_array[3] == "fileedit" && hasPermission( $BugID ) )
        {
            switch( $url_array[4] )
            {
                case  "new" :
                {
                    $Action = "New";
                    $BugID = $url_array[5];
                    include( "ezbug/admin/fileedit.php" );
                }
                break;
                case  "edit" :
                {
                    $Action = "Edit";
                    $BugID = $url_array[6];
                    $FileID = $url_array[5];
                    include( "ezbug/admin/fileedit.php" );
                }
                break;
                case "delete" :
                {
                    $Action = "Delete";
                    $BugID = $url_array[6];
                    $FileID = $url_array[5];
                    include( "ezbug/admin/fileedit.php" );
                }
                break;
                default :
                {
                    include( "ezbug/admin/fileedit.php" );
                }
                break;
            }
        }
        else if( $url_array[3] == "imageedit" && hasPermission( $BugID ) )
        {
            switch( $url_array[4] )
            {
                case "new":
                {
                    $Action = "New";
                    $BugID = $url_array[5];
                    include( "ezbug/admin/imageedit.php" );
                }
                break;
                case "edit" :
                {
                    $Action = "Edit";
                    $BugID = $url_array[6];
                    $ImageID = $url_array[5];
                    include( "ezbug/admin/imageedit.php" );
                }
                break;
                case "delete" :
                {
                    $Action = "Delete";
                    $BugID = $url_array[6];
                    $ImageID = $url_array[5];
                    include( "ezbug/admin/imageedit.php" );
                }
                break;
                default :
                {
                    include( "ezbug/admin/imageedit.php" );
                }
                break;
            }
        }
        else if( hasPermission( $BugID ) )
        {
            $Action = "Update";
            include( "ezbug/admin/bugedit.php" );
        }
        else // someone is trying to push the envelope
        {
            eZHTTPTool::header( "Location: /error/403");
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
        if ( $url_array[3] == "parent" )
        {
            $Offset = $url_array[5];
            $SearchText = urldecode( $url_array[4] );
        }

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
            case "create" :
            {
                $Action = "";
                include( "ezbug/user/bugreport.php" );
            }
            break;
            case "new" :
            {
                $Action = "New";
                $BugID = "";
                include( "ezbug/user/bugreport.php" );
            }
            break;

            case "edit" :
            {
                $BugID = $url_array[4];
                $Action = "Edit";
                if( $session->variable( "CurrentBugEdit" ) == $BugID && $BugID != 0 )
                {
                    $session->setVariable( "CurrentBugEdit", 0 );
                    include( "ezbug/user/bugreport.php" );
                }
                else
                {
                    eZHTTPTool::header( "Location: /error/403");
                    exit();
                }
            }
            break;

            case "update" :
            {
                $BugID = $url_array[4];
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

    case "unhandled" :
    {
        include( "ezbug/user/unhandledbugs.php" );
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
