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
                break;
            }
            case "new":
            {
                $Action = "new";
                include( "ezcontact/admin/companyedit.php" );
                break;
            }
            case "Insert":
            {
                $Action = "insert";
                include( "ezcontact/admin/companyedit.php" );
                break;
            }
            case "Edit":
            {
                $Action = "edit";
                $CompanyID = $url_array[4];
                include( "ezcontact/admin/companyedit.php" );
                break;
            }

            case "Update":
            {
                $Action = "update";
                $CompanyID = $url_array[4];
                include( "ezcontact/admin/companyedit.php" );
                break;
            }
            
            case "Delete":
            {
                $Action = "delete";
                $CompanyID = $url_array[4];
                include( "ezcontact/admin/companyedit.php" );
                break;
            }
        }
        break;
    }

    case "companytype" :
    {
        switch( $url_array[3] )
        {
            case "view":
            {
                $TypeID = $url_array[4];
                $Action = "view";
                include( "ezcontact/admin/companytypelist.php" );
                break;
            }
            case "list":
            {
                $TypeID = $url_array[4];
                $Action = "list";
                include( "ezcontact/admin/companytypelist.php" );
                break;
            }

            case "new":
            {
                $Action = "new";
                include( "ezcontact/admin/companytypeedit.php" );
                break;
            }
            case "insert":
            {
                $TypeID = $url_array[4];
                $Action = "insert";
                include( "ezcontact/admin/companytypeedit.php" );
                break;
            }
            case "edit":
            {
                $TypeID = $url_array[4];
                $Action = "edit";
                include( "ezcontact/admin/companytypeedit.php" );
                break;
            }
            case "update":
            {
                $TypeID = $url_array[4];
                $Action = "update";
                include( "ezcontact/admin/companytypeedit.php" );
                break;
            }
            case "delete":
            {
                $TypeID = $url_array[4];
                $Action = "delete";
                include( "ezcontact/admin/companytypeedit.php" );
                break;
            }
        }
        break;
    }
    case "person":
    {
        switch( $url_array[3] )
        {
            case "list":
            {
                $PersonID = $url_array[4];
                $Action = "list";
                include( "ezcontact/admin/personlist.php" );
                break;
            }
            case "new":
            {
                $Action = "new";
                include( "ezcontact/admin/personedit.php" );
                break;
            }
            case "insert":
            {
                $PersonID = $url_array[4];
                $Action = "insert";
                include( "ezcontact/admin/personview.php" );
                break;
            }
            case "view":
            {
                $PersonID = $url_array[4];
                $Action = "view";
                include( "ezcontact/admin/personview.php" );
                break;
            }
            case "edit":
            {
                $PersonID = $url_array[4];
                $Action = "edit";
                include( "ezcontact/admin/personedit.php" );
                break;
            }
            case "update":
            {
                $PersonID = $url_array[4];
                $Action = "update";
                include( "ezcontact/admin/personedit.php" );
                break;
            }
            case "delete":
            {
                $PersonID = $url_array[4];
                $Action = "delete";
                include( "ezcontact/admin/persondelete.php" );
                break;
            }
            default:
                header( "Redirect: ezcontact/admin/" );
                break;
        }
        break;
    }

    default :
        print( "<h1>Sorry, Your link page could not be found. </h1>" );
        break;
}

?>
