<?
$ini =& INIFile::globalINI();
$GlobalSectionID = $ini->read_var( "eZTodoMain", "DefaultSection" );

switch ( $url_array[2] )
{
    case "" :
        include( "eztodo/user/todolist.php" );        
        break;

    case "todolist" :
        include( "eztodo/user/todolist.php" );        
        break;

    case "todoedit" :
    {
        switch ( $url_array[3] )
        {
            case "new":
            {
                $Action = "new";
                include( "eztodo/user/todoedit.php" );
            }
            break;

            case "insert":
            {
                $Action = "insert";
                include( "eztodo/user/todoedit.php" );
            }
            break;
            
            case "edit":
            {
                $Action = "edit";
                $TodoID = $url_array[4];
                include( "eztodo/user/todoedit.php" );
            }
            break;

            case "update":
            {
                $Action = "update";
                $TodoID = $url_array[4];
                include( "eztodo/user/todoedit.php" );
            }
            break;
            case "delete":
            {
                $Action = "delete";
                $TodoID = $url_array[4];
                include( "eztodo/user/todoedit.php" );
            }
            break;

        }
    }
    break;

    case "todoview":
    {
        $TodoID = $url_array[3];
        include( "eztodo/user/todoview.php" );
    }
    break;
            
    
    case "todoinfo" :
        include( "eztodo/user/todoinfo.php" );
        break;

    default:
        print( "<h1>Sorry, Your link page could not be found. </h1>" );
        break;
}

?>
