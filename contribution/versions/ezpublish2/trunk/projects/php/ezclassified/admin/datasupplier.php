<?
//print $REQUEST_URI;

$url_array = explode( "/", $REQUEST_URI );

switch ( $url_array[2] )
{
    case "list":
    {
        $CategoryID = $url_array[3];
        include( "ezclassified/admin/classifiedlist.php" );
    }
    break;

    case "view":
    {
        $ClassifiedID = $url_array[3];
        include( "ezclassified/admin/classifiedview.php" );
    }
    break;

    case "new":
    {
        $Action = "new";
        include( "ezclassified/admin/classifiededit.php" );
    }
    break;

    
    case "edit":
    {
        $Action = "edit";
        $ClassifiedID = $url_array[3];
        include( "ezclassified/admin/classifiededit.php" );
    }
    break;

    case "update":
    {
        $Action = "update";
        $ClassifiedID = $url_array[3];
        include( "ezclassified/admin/classifiededit.php" );
    }
    break;

    case "insert":
    {
        $Action = "insert";
        include( "ezclassified/admin/classifiededit.php" );
    }
    break;

    case "delete":
    {
        $Action = "delete";
        $ClassifiedID = $url_array[3];
        include( "ezclassified/admin/classifiededit.php" );
    }
    break;

    case "category" :
    {
        if ( $url_array[3] == "new" )
        {
            $Action = "New";
        }

        if ( $url_array[3] == "insert" )
        {
            $Action = "Insert";
        }

        if ( $url_array[3] == "edit" )
        {
            $Action = "Edit";
            $CategoryID = $url_array[4];
        }

        if ( $url_array[3] == "Update" )
        {
            $Action = "Update";
            $CategoryID = $url_array[4];
        }

        if ( $url_array[3] == "delete" )
        {
            $Action = "Delete";
            $CategoryID = $url_array[4];
        }
        
        include( "ezclassified/admin/classifiedcategoryedit.php" );
    }
    break;

    default :
        print( "blæ" );
//        header( "Location: /error.php?type=404&reason=missingpage&hint[]=/contact/company/list/&hint[]=/contact/person/list&module=ezcontact" );
        break;
}

?>
