<?

switch ( $url_array[2] )
{
    case "archive" :        
    {
        $ModuleID = $url_array[3];
        $Action = "";
        include( "ezbug/admin/buglist.php" );
    }
    break;

    case "search" :        
    {
        include( "ezbug/admin/search.php" );
    }
    break;
    
    case "bugpreview" :
    case "view" :        
    {
        $BugID = $url_array[3];
        
        include( "ezbug/admin/bugpreview.php" );
    }
    break;
    
    case "unhandled" :
    {
        include( "ezbug/admin/unhandledbugs.php" );
    }
    break;

    case "priority" :
    {
        switch( $url_array[3] )
        {
            case "list":
            {
                include( "ezbug/admin/prioritylist.php" );
            }
            break;
        }
    }
    break;

    case "category" :
    {
        switch( $url_array[3] )
        {
            case "list":
            {
                include( "ezbug/admin/categorylist.php" );
            }
            break;
        }
    }
    break;

    case "module" :
    {
        switch( $url_array[3] )
        {
            case "list":
            {
                if( isset( $AddModule ) )  // new
                {
                    $Action = "new";
                    include( "ezbug/admin/moduleedit.php" );
                }
                else 
                {
                    $ParentID = $url_array[4];
                    include( "ezbug/admin/modulelist.php" );
                }
            }
            break;

            case "insert":
            {
                $Action = "insert";
                include( "ezbug/admin/moduleedit.php" );
            }
            break;

            case "edit":
            {
                $Action = "edit";
                $ModuleID = $url_array[4];
                include( "ezbug/admin/moduleedit.php" );
            }
            break;

            case "update":
            {
                $Action = "update";
                $ModuleID = $url_array[4];
                include( "ezbug/admin/moduleedit.php" );
            }
            break;

            case "delete":
            {
                $Action = "delete";
                $ModuleID = $url_array[4];
                include( "ezbug/admin/moduleedit.php" );
            }
            break;

        }
    }
    break;

    case "status" :
    {
        switch( $url_array[3] )
        {
            case "list":
            {
                $ParentID = $url_array[4];
                include( "ezbug/admin/statuslist.php" );
            }
            break;
        }
    }
    break;

    
    case "edit" :
    {
        if ( $url_array[3] == "new" )
        {
            $Action = "New";
        }

        if ( $url_array[3] == "edit" )
        {
            $Action = "Edit";
            $BugID = $url_array[4];
        }
        
        include( "ezbug/admin/bugedit.php" );
    }
    break;
}
?>
