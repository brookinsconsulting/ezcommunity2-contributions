<?

switch ( $url_array[2] )
{
    case "typelist":
    {
        include( "ezgroupeventcalendar/admin/typelist.php" );
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
        
        include( "ezgroupeventcalendar/admin/typeedit.php" );
    }
    break;

 case "categorylist":
   {
     include( "ezgroupeventcalendar/admin/categorylist.php" );
   }
   break;

 case "categoryedit" :
   {
     if ( $url_array[3] == "edit" )
       {
	 $Action = "Edit";
	 $CategoryID = $url_array[4];
       }
     else if ( $url_array[3] == "delete" )
       {
	 $Action = "Delete";
	 $CategoryID = $url_array[4];
       }
     else if ( $url_array[3] == "new" )
       {
	 $Action = "New";
       }

     include( "ezgroupeventcalendar/admin/categoryedit.php" );
   }
   break;

	case "grpdspl" :
	{
		include( "ezgroupeventcalendar/admin/groupdisplay.php" );
	}
	break;

	case "editor" :
	{
		switch ( $url_array[3] )
		{
			case "edit" :
			{
				$Action  = "Edit";
				$GroupID = $url_array[4];
				include( "ezgroupeventcalendar/admin/groupeditor.php" );
			}
			break;

			case "" :
			{
				$Action = "Display";
				include( "ezgroupeventcalendar/admin/groupeditor.php" );
			}
			break;
		}
	}
	break;

    default :
    {
        // go to default module page or show an error message
        print( "Error: your page request was not found" );
    }
}

?>
