<?
//print $REQUEST_URI;

$url_array = explode( "/", $REQUEST_URI );

switch ( $url_array[2] )
{
    case "" :
        include( "ezcontact/admin/phonetypelist.php" );        
        break;

    case "company":
    {
        switch ( $url_array[3] )
        {
            case "list":
            {
                $CategoryID = $url_array[4];
                include( "ezcontact/admin/companylist.php" );
            }
            break;
            case "new":
            {
                $Action = "new";
                include( "ezcontact/admin/companyedit.php" );
            }
            break;
            case "Insert":
            {
                $Action = "insert";
                include( "ezcontact/admin/companyedit.php" );
            }
            break;
            case "Edit":
            {
                $Action = "edit";
                $CompanyID = $url_array[4];
                include( "ezcontact/admin/companyedit.php" );
            }
            break;

            case "Update":
            {
                $Action = "update";
                $CompanyID = $url_array[4];
                include( "ezcontact/admin/companyedit.php" );
            }
            break;
            
            case "Delete":
            {
                $Action = "delete";
                $CompanyID = $url_array[4];
                include( "ezcontact/admin/companyedit.php" );
            }
            break;
        }
    }
    break;
    case "phonetypelist" :
        include( "ezcontact/admin/phonetypelist.php" );
        break;
    case "persontypelist" :
        include( "ezcontact/admin/persontypelist.php" );
        break;
    case "companytypelist" :
        include( "ezcontact/admin/companytypelist.php" );
        break;
    case "addresstypelist" :
        include( "ezcontact/admin/addresstypelist.php" );
        break;

    case "phonetypeedit" :
    {
        if ( $url_array[3] == "new" )
        {
            include( "ezcontact/admin/phonetypeedit.php" );
        }
        else if ( $url_array[3] == "insert" )
        {
            $PID = $url_array[4];
            $Action = "insert";
            include( "ezcontact/admin/phonetypeedit.php" );
        }

        else if ( $url_array[3] == "edit" )
        {
            $PID = $url_array[4];
            $Action = "edit";
            include( "ezcontact/admin/phonetypeedit.php" );
        }
        else if ( $url_array[3] == "update" )
        {
            $PID = $url_array[4];
            $Action = "update";
            include( "ezcontact/admin/phonetypeedit.php" );
        }
        else if ( $url_array[3] == "delete" )
        {
            $PID = $url_array[4];
            $Action = "delete";
            include( "ezcontact/admin/phonetypeedit.php" );
        }
    }
    break;
    case "persontypeedit" :
    {
        if ( $url_array[3] == "new" )
        {
            include( "ezcontact/admin/persontypeedit.php" );
        }
        else if ( $url_array[3] == "insert" )
        {
            $PID = $url_array[4];
            $Action = "insert";
            include( "ezcontact/admin/persontypeedit.php" );
        }

        else if ( $url_array[3] == "edit" )
        {
            $PID = $url_array[4];
            $Action = "edit";
            include( "ezcontact/admin/persontypeedit.php" );
        }
        else if ( $url_array[3] == "update" )
        {
            $PID = $url_array[4];
            $Action = "update";
            include( "ezcontact/admin/persontypeedit.php" );
        }
        else if ( $url_array[3] == "delete" )
        {
            $PID = $url_array[4];
            $Action = "delete";
            include( "ezcontact/admin/persontypeedit.php" );
        }
    }
    break;
    case "companytypeedit" :
    {
        if ( $url_array[3] == "new" )
        {
            include( "ezcontact/admin/companytypeedit.php" );
        }
        else if ( $url_array[3] == "insert" )
        {
            $CID = $url_array[4];
            $Action = "insert";
            include( "ezcontact/admin/companytypeedit.php" );
        }

        else if ( $url_array[3] == "edit" )
        {
            $CID = $url_array[4];
            $Action = "edit";
            include( "ezcontact/admin/companytypeedit.php" );
        }
        else if ( $url_array[3] == "update" )
        {
            $CID = $url_array[4];
            $Action = "update";
            include( "ezcontact/admin/companytypeedit.php" );
        }
        else if ( $url_array[3] == "delete" )
        {
            $CID = $url_array[4];
            $Action = "delete";
            include( "ezcontact/admin/companytypeedit.php" );
        }
    }
    break;
    case "addresstypeedit" :
    {
        if ( $url_array[3] == "new" )
        {
            include( "ezcontact/admin/addresstypeedit.php" );
        }
        else if ( $url_array[3] == "insert" )
        {
            $AID = $url_array[4];
            $Action = "insert";
            include( "ezcontact/admin/addresstypeedit.php" );
        }

        else if ( $url_array[3] == "edit" )
        {
            $AID = $url_array[4];
            $Action = "edit";
            include( "ezcontact/admin/addresstypeedit.php" );
        }
        else if ( $url_array[3] == "update" )
        {
            $AID = $url_array[4];
            $Action = "update";
            include( "ezcontact/admin/addresstypeedit.php" );
        }
        else if ( $url_array[3] == "delete" )
        {
            $AID = $url_array[4];
            $Action = "delete";
            include( "ezcontact/admin/addresstypeedit.php" );
        }
    }
    break;
    case "person" :
    {
        if ( $url_array[3] == "list" )
        {
            $Action = "list";
            include( "ezcontact/admin/personlist.php" );
        }
        elseif ( $url_array[3] == "new" )
        {
            $Action = "new";
            include( "ezcontact/admin/personedit.php" );
        }
        else if ( $url_array[3] == "insert" )
        {
            $PersonID = $url_array[4];
            $Action = "insert";
            include( "ezcontact/admin/personview.php" );
        }
        else if ( $url_array[3] == "view" )
        {
            $PersonID = $url_array[4];
            $Action = "view";
            include( "ezcontact/admin/personview.php" );
        }
        else if ( $url_array[3] == "edit" )
        {
            $PersonID = $url_array[4];
            $Action = "edit";
            include( "ezcontact/admin/personedit.php" );
        }
        else if ( $url_array[3] == "update" )
        {
            $PersonID = $url_array[4];
            $Action = "update";
            include( "ezcontact/admin/personedit.php" );
        }
        else if ( $url_array[3] == "delete" )
        {
            $PersonID = $url_array[4];
            $Action = "delete";
            include( "ezcontact/admin/persondelete.php" );
        }
    }
    break;



    default :
        print( "<h1>Sorry, Your link page could not be found. </h1>" );
        break;
}

?>
