<?
$ini =& INIFile::globalINI();
$GlobalSectionID = $ini->read_var( "eZGroupEventCalendarMain", "DefaultSection" );
$Title = "Calendar";

/*
if( $GetByTypeID != 0 )
	$Type = $GetByTypeID;
else
	$Type = 0;
*/

switch ( $url_array[2] )
{

    case "yearview" :
    {
        $Year = $url_array[3];

        include( "ezgroupeventcalendar/user/yearview.php" );
    }
    break;

    case "monthview" :
    {
        $Year = $url_array[3];
        $Month = $url_array[4];

        include( "ezgroupeventcalendar/user/monthview.php" );
    }
    break;

    case "dayview" :
    {
        $Year = $url_array[3];
        $Month = $url_array[4];
        $Day = $url_array[5];

        include( "ezgroupeventcalendar/user/dayview.php" );
    }
    break;
    
    case "eventedit" :
    {
        switch ( $url_array[3] )
        {
            case "new" :
            {
                $Action = "New";
                $Year = $url_array[4];
                $Month = $url_array[5];
                $Day = $url_array[6];
                $StartTime = $url_array[7];
            }
            break;

            case "edit" :
            {
                $Action = "Edit";
                $EventID = $url_array[4];
            }
            break;

            case "update" :
            {
                $Action = "Update";
                $EventID = $url_array[4];
            }
            break;

            case "insert" :
            {
                $Action = "Insert";
                $EventID = $url_array[4];
            }
            break;

            default :
            {
                $Action = $url_array[3];
            }
        }

        include( "ezgroupeventcalendar/user/eventedit.php" );
    }
    break;

    case "eventview" :
    {
        $EventID = $url_array[3];

        include( "ezgroupeventcalendar/user/eventview.php" );
    }
	break;
	
	default;
	{
		eZHTTPTool::header( "Location: /error/404" );
        exit();
	}
}

?>