<?
//print $REQUEST_URI;
$url_array = explode( "/", $REQUEST_URI );
switch ( $url_array[2] )
{
    case "categorytypelist" :
        include( "eztodo/admin/categorytypelist.php" );        
        break;
    case "categorytypeedit" :
    {
        if ( $url_array[3] == "new" )
        {
        include( "eztodo/admin/categorytypeedit.php" );
        }
        else if ( $url_array[3] == "insert" )
        {
            $CategoryID = $url_array[4];
            $Action = "insert";
            include( "eztodo/admin/categorytypeedit.php" );
        }

        else if ( $url_array[3] == "edit" )
        {
            $CategoryID = $url_array[4];
            $Action = "edit";
            include( "eztodo/admin/categorytypeedit.php" );
        }
        else if ( $url_array[3] == "update" )
        {
            $CategoryID = $url_array[4];
            $Action = "update";
            include( "eztodo/admin/categorytypeedit.php" );
        }
        else if ( $url_array[3] == "delete" )
        {
            $CategoryID = $url_array[4];
            $Action = "delete";
            include( "eztodo/admin/categorytypeedit.php" );
        }
    }
    break;
        
    case "prioritytypelist" :
        include( "eztodo/admin/prioritytypelist.php" );
        break;

    case "prioritytypeedit" :
    {
        if ( $url_array[3] == "new" )
        {
        include( "eztodo/admin/prioritytypeedit.php" );
        }
        else if ( $url_array[3] == "insert" )
        {
            $PriorityID = $url_array[4];
            $Action = "insert";
            include( "eztodo/admin/prioritytypeedit.php" );
        }

        else if ( $url_array[3] == "edit" )
        {
            $PriorityID = $url_array[4];
            $Action = "edit";
            include( "eztodo/admin/prioritytypeedit.php" );
        }
        else if ( $url_array[3] == "update" )
        {
            $PriorityID = $url_array[4];
            $Action = "update";
            include( "eztodo/admin/prioritytypeedit.php" );
        }
        else if ( $url_array[3] == "delete" )
        {
            $PriorityID = $url_array[4];
            $Action = "delete";
            include( "eztodo/admin/prioritytypeedit.php" );
        }
    }
    break;

    default:
        print( "<h1>Sorry, Your link page could not be found. </h1>" );
        break;
}

?>
