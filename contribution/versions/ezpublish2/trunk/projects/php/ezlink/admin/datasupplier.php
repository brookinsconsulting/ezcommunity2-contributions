<?
// $url_array = explode( "/", $REQUEST_URI );

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
        $LinkGroupID = $url_array[3];
        include( "ezlink/admin/linkgrouplist.php" );
    }
    break;

    case "unacceptedlist":
    {
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
        include( "ezlink/admin/search.php" );        
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
