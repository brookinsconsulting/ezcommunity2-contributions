<?
//print $REQUEST_URI;

$url_array = explode( "/", $REQUEST_URI );

if( $UserID > 0 )
{
    $Add_User = false;
}
else
{
    $Add_User = true;
}

switch ( $url_array[2] )
{
    case "person":
    {
        switch( $url_array[3] )
        {
            case "list":
            {
                $PersonID = $url_array[4];
                $Action = "list";
                include( "ezcontact/user/personlist.php" );
                break;
            }
            case "new":
            {
                $Action = "new";
                include( "ezcontact/user/personedit.php" );
                break;
            }
            case "insert":
            {
                $PersonID = $url_array[4];
                $Action = "insert";
                include( "ezcontact/user/personedit.php" );
                break;
            }
            case "view":
            {
                $PersonID = $url_array[4];
                $Action = "view";
                include( "ezcontact/user/personview.php" );
                break;
            }
            case "edit":
            {
                $PersonID = $url_array[4];
                $Action = "edit";
                include( "ezcontact/user/personedit.php" );
                break;
            }
            case "update":
            {
                $PersonID = $url_array[4];
                $Action = "update";
                include( "ezcontact/user/personedit.php" );
                break;
            }
            case "delete":
            {
                $PersonID = $url_array[4];
                $Action = "delete";
                include( "ezcontact/user/persondelete.php" );
                break;
            }
        }
        break;
    }
    break;

    case "company":
    {
        if ( $url_array[3] == "list" )
        {
            $CategoryID = $url_array[4];
            include( "ezcontact/user/companylist.php" );
        }
        if ( $url_array[3] == "view" )
        {
            $CompanyID = $url_array[4];
            include( "ezcontact/user/companyview.php" );
        }

    }
    break;



    default :
        print( "<h1>Sorry, This page isn't for you. </h1>" );
        break;
}

?>
