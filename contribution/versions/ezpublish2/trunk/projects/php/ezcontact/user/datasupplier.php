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
    case "person" :
    {
        if ( $url_array[3] == "list" )
        {
            $Action = "list";
            include( "ezcontact/user/personlist.php" );
        }
        elseif ( $url_array[3] == "new" )
        {
            if( $PersonID > 0 )
            {
                header("Redirect: contact/user/edit/$PersonID" );
                exit();
            }
            else
            {
                $Action = "new";
                include( "ezcontact/user/personedit.php" );
            }
        }
        else if ( $url_array[3] == "insert" )
        {
            if( $url_array[4] == 0 )
            {
                $Action = "insert";
                include( "ezcontact/user/personedit.php" );
            }
            else
            {
                header("Redirect: contact/user/forbidden.php?insert");
                exit();
            }
        }
        else if ( $url_array[3] == "view" )
        {
            if( $PersonID == $url_array[4] )
            {
                $Action = "view";
                include( "ezcontact/user/personview.php" );
            }
            elseif( $UserID == 0 )
            {
                header("Redirect: login");
            }
            else
            {
                header("Redirect: contact/user/forbidden.php?view");
                exit();
            }
        }
        else if ( $url_array[3] == "edit" )
        {
            if( $PersonID == $url_array[4] )
            {
                $Action = "edit";
                include( "ezcontact/user/personedit.php" );
            }
            elseif( $UserID == 0 )
            {
                header("Redirect: login");
                exit();
            }
            else
            {
                header("Redirect: contact/user/forbidden.php?edit");
                exit();
            }
        }
        else if ( $url_array[3] == "update" )
        {
            if( $PersonID == $url_array[4] )
            {
                $Action = "update";
                include( "ezcontact/user/personedit.php" );
            }
            elseif( $UserID == 0 )
            {
                header("Redirect: login");
                exit();
            }
            else
            {
                header("Redirect: contact/user/forbidden.php?update");
                exit();
            }
        }
        else if ( $url_array[3] == "delete" )
        {
            if( $PersonID == $url_array[4] )
            {
                $Action = "delete";
                include( "ezcontact/user/persondelete.php" );
            }
            elseif( $UserID == 0 )
            {
                header("Redirect: login");
                exit();
            }
            else
            {
                header("Redirect: contact/user/forbidden.php?update");
                exit();
            }
        }
    }
    break;



    default :
        print( "<h1>Sorry, This page isn't for you. </h1>" );
        break;
}

?>
