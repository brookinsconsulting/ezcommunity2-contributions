<?

switch ( $url_array[2] )
{

    case "yearview" :
    {
        $Year = $url_array[3];

        include( "ezcalendar/user/yearview.php" );
    }
    break;

    case "monthview" :
    {
        $Year = $url_array[3];
        $Month = $url_array[4];

        include( "ezcalendar/user/monthview.php" );
    }
    break;

    case "dayview" :
    {
        $Year = $url_array[3];
        $Month = $url_array[4];
        $Day = $url_array[5];

        include( "ezcalendar/user/dayview.php" );
    }
    break;
    
    case "appointmentedit" :
    {
        switch ( $url_array[3] )
        {
            case "new" :
            {
                $Action = "New";
            }
            break;

            case "edit" :
            {
                $Action = "Edit";
                $AppointmentID = $url_array[4];
            }
            break;

            default :
            {
                $Action = $url_array[3];
            }
        }

        include( "ezcalendar/user/appointmentedit.php" );
    }
    break;

    case "appointmentview" :
    {
        $AppointmentID = $url_array[3];

        include( "ezcalendar/user/appointmentview.php" );
    }    
}

?>
