<?
//print $REQUEST_URI;
$url_array = explode( "/", $REQUEST_URI );
switch ( $url_array[2] )
{
    case "categorytypelist":
    {
        include( "eztodo/admin/categorytypelist.php" );        
    }
    break;
    
    case "categorytypeedit" :
    {
        switch( $url_array[3] )
        {
            case "new":
            {
                $Action = "new";
                include( "eztodo/admin/categorytypeedit.php" );
            }
            break;
            
            case "insert":
            {
                $CategoryID = $url_array[4];
                $Action = "insert";
                include( "eztodo/admin/categorytypeedit.php" );
            }
            break;
            
            case "edit":
            {
                $CategoryID = $url_array[4];
                $Action = "edit";
                include( "eztodo/admin/categorytypeedit.php" );
            }
            break;
            
            case "update":
            {
                $CategoryID = $url_array[4];
                $Action = "update";
                include( "eztodo/admin/categorytypeedit.php" );
            }
            break;
            
            case "delete":
            {
                $CategoryID = $url_array[4];
                $Action = "delete";
                include( "eztodo/admin/categorytypeedit.php" );
            }
            break;
        }
    }
    break;
        
    case "prioritytypelist":
    {
        include( "eztodo/admin/prioritytypelist.php" );
    }
    break;

    case "prioritytypeedit" :
    {
        switch( $url_array[3] )
        {
            case "new":
            {
                $Action = "new";
                include( "eztodo/admin/prioritytypeedit.php" );
            }
            break;

            case "insert":
            {
                $PriorityID = $url_array[4];
                $Action = "insert";
                include( "eztodo/admin/prioritytypeedit.php" );
            }
            break;

            case "edit":
            {
                $PriorityID = $url_array[4];
                $Action = "edit";
                include( "eztodo/admin/prioritytypeedit.php" );
            }
            break;

            case "update":
            {
                $PriorityID = $url_array[4];
                $Action = "update";
                include( "eztodo/admin/prioritytypeedit.php" );
            }
            break;

            case "delete":
            {
                $PriorityID = $url_array[4];
                $Action = "delete";
                include( "eztodo/admin/prioritytypeedit.php" );
            }
            break;
        }
    }
    break;

    case "statustypelist":
    {
        include( "eztodo/admin/statustypelist.php" );        
    }
    break;
    
    case "statustypeedit" :
    {
        switch( $url_array[3] )
        {
            case "new":
            {
                $Action = "new";
                include( "eztodo/admin/statustypeedit.php" );
            }
            break;
            
            case "insert":
            {
                $CategoryID = $url_array[4];
                $Action = "insert";
                include( "eztodo/admin/statustypeedit.php" );
            }
            break;
            
            case "edit":
            {
                $CategoryID = $url_array[4];
                $Action = "edit";
                include( "eztodo/admin/statustypeedit.php" );
            }
            break;
            
            case "update":
            {
                $CategoryID = $url_array[4];
                $Action = "update";
                include( "eztodo/admin/statustypeedit.php" );
            }
            break;
            
            case "delete":
            {
                $CategoryID = $url_array[4];
                $Action = "delete";
                include( "eztodo/admin/statustypeedit.php" );
            }
            break;
        }
    }
    break;

    default:
    {
        print( "<h1>Sorry, Your todo page could not be found. </h1>" );
    }
    break;
}

?>
