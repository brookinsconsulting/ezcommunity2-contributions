<?
include_once( "ezuser/classes/ezpermission.php" );
include_once( "classes/ezhttptool.php" );

$user =& eZUser::currentUser();
if( eZPermission::checkPermission( $user, "eZPoll", "ModuleEdit" ) == false )
{
    eZHTTPTool::header( "Location: /error/403" );
    exit();
}
//print $REQUEST_URI;

$url_array = explode( "/", $REQUEST_URI );

switch ( $url_array[2] )
{
    case "pollist" :
    {
        if( isset( $DeletePolls ) )
            $Action = "Delete";

        if ( isset( $AddPoll ) )
        {
            include( "ezpoll/admin/polledit.php" );
        }
        else
        {        
            include( "ezpoll/admin/pollist.php" );
        }
    }
    break;

    case "polledit" :
        if ( ( $url_array[3] == "new" ) )
        {
            include( "ezpoll/admin/polledit.php" );
        }
        else if ( ( $url_array[3] == "insert" ) )
        {
            $Action = "Insert";
            include( "ezpoll/admin/polledit.php" );
        }
        else if( ( $url_array[3] == "edit" ) )
        {
            $Action = "Edit";
            $PollID = $url_array[4];
            include( "ezpoll/admin/polledit.php" );
        }
        else if( ( $url_array[3] == "update" ) )
        {
            $Action = "Update";
            $PollID = $url_array[4];
            include( "ezpoll/admin/polledit.php" );
        }
        break;
}

?>
