<?
//print $REQUEST_URI;

$url_array = explode( "/", $REQUEST_URI );

switch ( $url_array[2] )
{
    case "categorylist" :
        if ( ( $url_array[3] == "parent") && ( $url_array[4] != "" ) )
        {
            $ParentID = $url_array[4];
            include( "eztrade/admin/categorylist.php" );
        }
        else
        {
            include( "eztrade/admin/categorylist.php" );
        }
        break;
        
    case "categoryedit" :
        if ( ( $url_array[3] == "insert") )
        {
            $Action = "Insert";
            include( "eztrade/admin/categoryedit.php" );
        }
        else if ( ( $url_array[3] == "edit") )
        {
            $Action = "Edit";
            $CategoryID = $url_array[4];            
            include( "eztrade/admin/categoryedit.php" );
        }
        else if ( ( $url_array[3] == "update") )
        {
            $Action = "Update";
            include( "eztrade/admin/categoryedit.php" );
        }        
        else if ( ( $url_array[3] == "delete") )
        {
            $Action = "Delete";
            $CategoryID = $url_array[4];
            include( "eztrade/admin/categoryedit.php" );
        }        
        else
        {
            include( "eztrade/admin/categoryedit.php" );
        }        
        break;
        
    case "productedit" :
        switch ( $url_array[3] )
        {
            case "optionlist" :
                $ProductID = $url_array[4];
                include( "eztrade/admin/optionlist.php" );
                break;

            case "optionedit" :
                $ProductID = $url_array[4];
                include( "eztrade/admin/optionedit.php" );
                break;
                
            case "insert" :
                $Action = "Insert";
                include( "eztrade/admin/productedit.php" );
                break;
            case "edit" :
                $Action = "Edit";
                $ProductID = $url_array[4];            
                include( "eztrade/admin/productedit.php" );
                break;
            case "update" :
                $Action = "Update";
                include( "eztrade/admin/productedit.php" );
                break;
            case "delete" :
                $Action = "Delete";
                $ProductID = $url_array[4];
                include( "eztrade/admin/productedit.php" );
                break;
            default:
                include( "eztrade/admin/productedit.php" );
                break;
        }
        break;        
    case "testbench" :
        include( "eztrade/admin/testbench.php" );
        break;
    case "search" :
        print( "<h1>Product search</h1>" );        
        break;
    default :
        print( "<h1>Sorry, Your PRODUCT page could not be found. </h1>" );
        break;
}

?>
