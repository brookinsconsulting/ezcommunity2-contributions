<?
// $url_array = explode( "/", $REQUEST_URI );

$user = eZUser::currentUser();
include_once( "ezuser/classes/ezpermission.php" );
include_once( "classes/ezhttptool.php" );

if( eZPermission::checkPermission( $user, "eZLink", "ModuleEdit" ) == false )
{
    eZHTTPTool::header( "Location: /error/403" );
    exit();
}

switch ( $url_array[2] )
{
    case "" :
    {
        include( "ezlink/admin/linkgrouplist.php" );
    }
    break;
    case "link" :
    {
        $LID = $url_array[3];
        include( "ezlink/admin/linkgrouplist.php" );
    }
    break;

    case "group" :
    {
        if ( $url_array[4] == "parent" )
            $Offset = $url_array[5];
        $LinkGroupID = $url_array[3];
        include( "ezlink/admin/linkgrouplist.php" );
    }
    break;

    case "unacceptedlist":
    {
        if ( $url_array[3] )
            $Offset = $url_array[3];
        include( "ezlink/admin/unacceptedlist.php" );
    }
    break;
    case "unacceptededit":
    {
        include( "ezlink/admin/unacceptededit.php" );
    }
    break;
    
    case "linkedit" :
    {
        if ( $url_array[3] == "new" )
        {
            $Action = "new";
            include( "ezlink/admin/linkedit.php" );
        }
        else if ( $url_array[3] == "insert" )
        {
            $LinkID = $url_array[4];
            $Action = "insert";
            include( "ezlink/admin/linkedit.php" );
        }

        else if ( $url_array[3] == "edit" )
        {
            $LinkID = $url_array[4];
            $Action = "edit";
            include( "ezlink/admin/linkedit.php" );
        }
        else if ( $url_array[3] == "update" )
        {
            $LinkID = $url_array[4];
            $Action = "update";
            include( "ezlink/admin/linkedit.php" );
        }
        else if ( $url_array[3] == "delete" )
        {
            $LinkID = $url_array[4];
            $Action = "delete";
            include( "ezlink/admin/linkedit.php" );
        }
    }
    break;

    case "groupedit" :
    {
        if ( $url_array[3] == "new" )
        {
            $Action = "new";
            include( "ezlink/admin/groupedit.php" );
        }
        else if ( $url_array[3] == "insert" )
        {
            $LinkGroupID = $url_array[4];
            $Action = "insert";
            include( "ezlink/admin/groupedit.php" );
        }

        else if ( $url_array[3] == "edit" )
        {
            $LinkGroupID = $url_array[4];
            $Action = "edit";
            include( "ezlink/admin/groupedit.php" );
        }
        else if ( $url_array[3] == "update" )
        {
            $LinkGroupID = $url_array[4];
            $Action = "update";
            include( "ezlink/admin/groupedit.php" );
        }
        else if ( $url_array[3] == "delete" )
        {
            $LinkGroupID = $url_array[4];
            $Action = "delete";
            include( "ezlink/admin/groupedit.php" );
        }
    }
    break;
    case "testbench" :
        include( "eztrade/admin/testbench.php" );
        break;
    case "search" :
    {
        if ( $url_array[3] == "parent" )
        {
            $QueryString = urlencode( $url_array[4] );
            $Offset = $url_array[5];
        }
        include( "ezlink/admin/search.php" );
    }
        break;
    case "norights" :
        include( "ezlink/admin/norights.php" );        
        break;
    case "gotolink" :
    {
        $Action = $url_array[3];
        $LinkID = $url_array[4];
        include( "ezlink/admin/gotolink.php" );
    }
    break;


    default :
        print( "<h1>Sorry, Your link page could not be found. </h1>" );
        break;
}

?>
