<?
include_once( "ezuser/classes/ezpermission.php" );
include_once( "classes/ezhttptool.php" );


$user =& eZUser::currentUser();

if ( get_class( $user ) == "ezuser"  and eZPermission::checkPermission( $user, "eZUser", "ModuleEdit" ) == true)
{
    // These should only be available if the user has permissions...
    switch ( $url_array[2] )
    {
        case "welcome" :
        {
            include( "ezuser/admin/welcome.php" );
        }
        break;

        case "sessioninfo" :
        {
            if ( $url_array[3] == "delete" )
            {
                $Action = "Delete";
                $SessionID = $url_array[4];
            }
            include( "ezuser/admin/sessioninfo.php" );
        }
        break;
    
        case "userlist" :
        {
            $Index = $url_array[3];
            $OrderBy = $url_array[4];
            if ( !isset( $GroupID ) )
                $GroupID = $url_array[5];
            include( "ezuser/admin/userlist.php" );
        }
        break;

        case "grouplist" :
        {
            include( "ezuser/admin/grouplist.php" );
        }
        break;

        case "useredit" :
        {
            if ( $url_array[3] == "new" )
            {
                $Action = "new";
                include( "ezuser/admin/useredit.php" );
            }
            else if ( $url_array[3] == "insert" )
            {
                $Action = "insert";
                include( "ezuser/admin/useredit.php" );
            }

            else if ( $url_array[3] == "edit" )
            {
                $Action = "edit";
                $UserID = $url_array[4];
                include( "ezuser/admin/useredit.php" );
            }
            else if ( $url_array[3] == "update" )
            {
                $Action = "update";
                $UserID = $url_array[4];
                include( "ezuser/admin/useredit.php" );
            }
            else if ( $url_array[3] == "delete" )
            {
                $Action = "delete";
                $UserID = $url_array[4];
                include( "ezuser/admin/useredit.php" );
            }
        }
        break;

        case "groupedit" :
        {
            if ( $url_array[3] == "new" )
            {
                include( "ezuser/admin/groupedit.php" );
            }
            else if ( $url_array[3] == "insert" )
            {
                $Action = "insert";
                include( "ezuser/admin/groupedit.php" );
            }

            else if ( $url_array[3] == "edit" )
            {
                $Action = "edit";
                $GroupID = $url_array[4];
                include( "ezuser/admin/groupedit.php" );
            }
            else if ( $url_array[3] == "update" )
            {
                $Action = "update";
                $GroupID = $url_array[4];
                include( "ezuser/admin/groupedit.php" );
            }
            else if ( $url_array[3] == "delete" )
            {
                $Action = "delete";
                $GroupID = $url_array[4];
                include( "ezuser/admin/groupedit.php" );
            }
        }
        break;

                case "login" :
        {
            $Action = $url_array[3];
            include( "ezuser/admin/login.php" );
        }
        break;

        case "success" :
        {
            $Action = $url_array[3];
            include( "ezuser/admin/success.php" );
        }
        break;

        case "logout" :
        {
            $Action = $url_array[3];
            include( "ezuser/admin/login.php" );
        }
        break;

        case "passwordchange" :
        {
            $Action = $url_array[3];
            include( "ezuser/admin/passwordchange.php" );
        }
        break;

        case "settings" :
        {
            $Action = $url_array[3];
            include( "ezuser/admin/settings.php" );
        }
        break;

    }
}
else
{
    // These should allways be available
    switch( $url_array[2] ) 
    {
        case "login" :
        {
            $Action = $url_array[3];
            include( "ezuser/admin/login.php" );
        }
        break;

        case "success" :
        {
            $Action = $url_array[3];
            include( "ezuser/admin/success.php" );
        }
        break;

        case "logout" :
        {
            $Action = $url_array[3];
            include( "ezuser/admin/login.php" );
        }
        break;

        case "passwordchange" :
        {
            $Action = $url_array[3];
            include( "ezuser/admin/passwordchange.php" );
        }
        break;

        case "settings" :
        {
            $Action = $url_array[3];
            include( "ezuser/admin/settings.php" );
        }
        break;

        default :
        {
            eZHTTPTool::header( "Location: /error/403" );
            exit();
        }
        break;

    }
}

?>
