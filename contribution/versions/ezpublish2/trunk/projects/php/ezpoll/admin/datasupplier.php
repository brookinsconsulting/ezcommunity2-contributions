<?
//print $REQUEST_URI;

$url_array = explode( "/", $REQUEST_URI );

switch ( $url_array[2] )
{
    case "pollist" :
    {
        if( isset( $DeletePolls ) )
            $Action = "Delete";
        if( isset( $AddPoll ) )
        {
            include( "ezpoll/admin/polledit.php" );
            exit();
        }
        
        include( "ezpoll/admin/pollist.php" );
    }
    break;

    case "polledit" :
        if ( ( $url_array[3] == "new" ) )
        {
            include( "ezpoll/admin/polledit.php" );
        }

        else if ( ( $url_array[3] == "insert" ) )
        {
            $Action = "Insert";
            include( "ezpoll/admin/polledit.php" );
        }
        else if( ( $url_array[3] == "edit" ) )
        {
            $Action = "Edit";
            $PollID = $url_array[4];
            include( "ezpoll/admin/polledit.php" );
        }
        else if( ( $url_array[3] == "update" ) )
        {
            $Action = "Update";
            $PollID = $url_array[4];
            include( "ezpoll/admin/polledit.php" );
        }
        break;

        /** MARKED FOR DELETION BEFORE V2.0 **/
        /*    
    case "choiceedit":
    {
        if ( ( $url_array[3] == "new" ) )
        {
            $PollID = $url_array[4];
            include( "ezpoll/admin/choiceedit.php" );
        }
        else if ( ( $url_array[3] == "insert" ) )
        {
            $Action = "insert";
            $PollID = $url_array[4];
            include( "ezpoll/admin/choiceedit.php" );
        }
        else if ( ( $url_array[3] == "edit" ) )
        {
            $Action = "edit";
            $PollID = $url_array[4];
            $ChoiceID = $url_array[5];
            include( "ezpoll/admin/choiceedit.php" );
        }
        else if ( ( $url_array[3] == "update" ) )
        {
            $Action = "update";
            $PollID = $url_array[4];
            $ChoiceID = $url_array[5];
            include( "ezpoll/admin/choiceedit.php" );
        }
        else if ( ( $url_array[3] == "delete" ) )
        {
            $Action = "delete";
            $PollID = $url_array[4];
            $ChoiceID = $url_array[5];
            include( "ezpoll/admin/choiceedit.php" );
        }
        break;
    }
        */
}

?>
