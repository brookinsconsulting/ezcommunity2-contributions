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
        }

        if ( $url_array[3] == "update" )
        {
            $Action = "Update";
        }

        if ( $url_array[3] == "delete" )
        {
            $Action = "Delete";
        }
        
        if( empty( $AdID ) )
        {
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
        }

        if ( $url_array[3] == "update" )
        {
            $Action = "Update";
            $CategoryID = $url_array[4];
        }

        if ( $url_array[3] == "delete" )
        {
            $Action = "Delete";
        }
        if( empty( $CategoryID ) )
        {
            $CategoryID = $url_array[4];
        }
        include( "ezad/admin/categoryedit.php" );
    }
    break;

}

?>
