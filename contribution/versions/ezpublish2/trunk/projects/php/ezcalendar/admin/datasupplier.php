<?

switch ( $url_array[2] )
{
    case "typelist":
    {
        include( "ezcalendar/admin/typelist.php" );
    }
    break;

    case "typeedit" :
    {
        if ( $url_array[3] == "edit" )
        {
            $Action = "Edit";
            $TypeID = $url_array[4];
        }
        else if ( $url_array[3] == "delete" )
        {
            $Action = "Delete";
            $TypeID = $url_array[4];
        }
        else if ( $url_array[3] == "new" )
        {
            $Action = "New";
        }
        
        include( "ezcalendar/admin/typeedit.php" );
    }
    break;

    default :
    {
        // go to default module page or show an error message
        print( "Error: your page request was not found" );
    }
}

?>
