<?

switch ( $url_array[2] )
{
    case "archive" :        
    {
        $ModuleID = $url_array[3];
        
        include( "ezbug/admin/buglist.php" );
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

            case "new":
            {
                $Action = "new";
                include( "ezbug/admin/priorityedit.php" );
            }
            break;

            case "insert":
            {
                $Action = "insert";
                include( "ezbug/admin/priorityedit.php" );
            }
            break;

            case "edit":
            {
                $Action = "edit";
                $PriorityID = $url_array[4];
                include( "ezbug/admin/priorityedit.php" );
            }
            break;

            case "update":
            {
                $Action = "update";
                $PriorityID = $url_array[4];
                include( "ezbug/admin/priorityedit.php" );
            }
            break;

            case "delete":
            {
                $Action = "delete";
                $PriorityID = $url_array[4];
                include( "ezbug/admin/priorityedit.php" );
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

            case "new":
            {
                $Action = "new";
                include( "ezbug/admin/categoryedit.php" );
            }
            break;

            case "insert":
            {
                $Action = "insert";
                include( "ezbug/admin/categoryedit.php" );
            }
            break;

            case "edit":
            {
                $Action = "edit";
                $CategoryID = $url_array[4];
                include( "ezbug/admin/categoryedit.php" );
            }
            break;

            case "update":
            {
                $Action = "update";
                $CategoryID = $url_array[4];
                include( "ezbug/admin/categoryedit.php" );
            }
            break;

            case "delete":
            {
                $Action = "delete";
                $CategoryID = $url_array[4];
                include( "ezbug/admin/categoryedit.php" );
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
