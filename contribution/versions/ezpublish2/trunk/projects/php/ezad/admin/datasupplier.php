<?

switch ( $url_array[2] )
{
    case "archive" :
    {
        $CategoryID = $url_array[3];

        include( "ezad/admin/adlist.php" );
    }
    break;

    case "statistics" :
    {
        $AdID = $url_array[3];
        
        include( "ezad/admin/adstatistics.php" );        
    }
    break;

    case "ad" :
    {
        if ( $url_array[3] == "new" )
        {
            $Action = "New";
        }

        if ( $url_array[3] == "insert" )
        {
            $Action = "Insert";
        }

        if ( $url_array[3] == "edit" )
        {
            $Action = "Edit";
            $AdID = $url_array[4];
        }

        if ( $url_array[3] == "Update" )
        {
            $Action = "Update";
            $AdID = $url_array[4];
        }

        if ( $url_array[3] == "delete" )
        {
            $Action = "Delete";
            $AdID = $url_array[4];
        }
        
        include( "ezad/admin/adedit.php" );
    }
    break;
    
    case "category" :
    {
        if ( $url_array[3] == "new" )
        {
            $Action = "New";
        }

        if ( $url_array[3] == "insert" )
        {
            $Action = "Insert";
        }

        if ( $url_array[3] == "edit" )
        {
            $Action = "Edit";
            $CategoryID = $url_array[4];
        }

        if ( $url_array[3] == "Update" )
        {
            $Action = "Update";
            $CategoryID = $url_array[4];
        }

        if ( $url_array[3] == "delete" )
        {
            $Action = "Delete";
            $CategoryID = $url_array[4];
        }
        
        include( "ezad/admin/categoryedit.php" );
    }
    break;

}

?>
