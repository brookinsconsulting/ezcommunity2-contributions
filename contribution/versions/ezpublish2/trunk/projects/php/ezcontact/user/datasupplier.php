<?
//print $REQUEST_URI;

$url_array = explode( "/", $REQUEST_URI );

include_once( "ezuser/classes/ezuser.php" );
$user = eZUser::currentUser();

if( $user != 0 )
{
    $Add_User = false;
    $UserID = $user->id();
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
            $Action = "new";
            include( "ezcontact/user/personedit.php" );
        }
        else if ( $url_array[3] == "insert" )
        {
            if( $PersonID == $UserID )
            {
                $PersonID = $url_array[4];
                $Action = "insert";
                include( "ezcontact/user/personview.php" );
            }
            elseif( $UserID == 0 )
            {
                header("Redirect: login");
                exit();
            }
            else
            {
                header("Redirect: contact/user/forbidden.php?insert");
                exit();
            }
        }
        else if ( $url_array[3] == "view" )
        {
            $PersonID = $url_array[4];
            
            if( $PersonID == $UserID )
            {
                $Action = "view";
                include( "ezcontact/user/personview.php" );
            }
            elseif( $UserID == 0 )
            {
                header("Redirect: login");
                exit();
            }
            else
            {
                header("Redirect: contact/user/forbidden.php?view");
                exit();
            }
        }
        else if ( $url_array[3] == "edit" )
        {
            $PersonID = $url_array[4];

            if( $PersonID == $UserID )
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
            $PersonID = $url_array[4];
            if( $PersonID == $UserID )
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
            $PersonID = $url_array[4];
            
            if( $PersonID == $UserID )
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
