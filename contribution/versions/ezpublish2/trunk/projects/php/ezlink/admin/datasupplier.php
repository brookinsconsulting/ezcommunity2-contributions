<?
$url_array = explode( "/", $REQUEST_URI );

switch ( $url_array[2] )
{
    case "" :
    {
        include( "ezlink/admin/linklist.php" );
    }
    break;
    case "link" :
    {
        $LID = $url_array[3];
        include( "ezlink/admin/linklist.php" );
    }
    break;

    case "group" :
    {
        $LGID = $url_array[3];
        include( "ezlink/admin/linklist.php" );
    }
    break;
    
    case "linkedit" :
    {
        if ( $url_array[3] == "new" )
        {
            include( "ezlink/admin/linkedit.php" );
        }
        else if ( $url_array[3] == "insert" )
        {
            $LID = $url_array[4];
            $Action = "insert";
            include( "ezlink/admin/linkedit.php" );
        }

        else if ( $url_array[3] == "edit" )
        {
            $LID = $url_array[4];
            $Action = "edit";
            include( "ezlink/admin/linkedit.php" );
        }
        else if ( $url_array[3] == "update" )
        {
            $LID = $url_array[4];
            $Action = "update";
            include( "ezlink/admin/linkedit.php" );
        }
        else if ( $url_array[3] == "delete" )
        {
            $LID = $url_array[4];
            $Action = "delete";
            include( "ezlink/admin/linkedit.php" );
        }
    }
    break;

    case "groupedit" :
    {
        if ( $url_array[3] == "new" )
        {
            include( "ezlink/admin/groupedit.php" );
        }
        else if ( $url_array[3] == "insert" )
        {
            $LGID = $url_array[4];
            $Action = "insert";
            include( "ezlink/admin/groupedit.php" );
        }

        else if ( $url_array[3] == "edit" )
        {
            $LGID = $url_array[4];
            $Action = "edit";
            include( "ezlink/admin/groupedit.php" );
        }
        else if ( $url_array[3] == "update" )
        {
            $LGID = $url_array[4];
            $Action = "update";
            include( "ezlink/admin/groupedit.php" );
        }
        else if ( $url_array[3] == "delete" )
        {
            $LGID = $url_array[4];
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
    default :
        print( "<h1>Sorry, Your link page could not be found. </h1>" );
        break;
}

?>
