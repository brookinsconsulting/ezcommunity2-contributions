<?
//print $REQUEST_URI;

$url_array = explode( "/", $REQUEST_URI );

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
            include_once( "ezuser/classes/ezuser.php" );
            $user = eZUser::currentUser();
            
            if( $user != 0 )
            {
                $Add_User = false;
            }
            else
            {
                $Add_User = true;
            }
            
            $Action = "new";
            include( "ezcontact/user/personedit.php" );
        }
        else if ( $url_array[3] == "insert" )
        {
            $PersonID = $url_array[4];
            $Action = "insert";
            include( "ezcontact/user/personview.php" );
        }
        else if ( $url_array[3] == "view" )
        {
            $PersonID = $url_array[4];
            $Action = "view";
            include( "ezcontact/user/personview.php" );
        }
        else if ( $url_array[3] == "edit" )
        {
            $PersonID = $url_array[4];
            $Action = "edit";
            include( "ezcontact/user/personedit.php" );
        }
        else if ( $url_array[3] == "update" )
        {
            $PersonID = $url_array[4];
            $Action = "update";
            include( "ezcontact/user/personedit.php" );
        }
        else if ( $url_array[3] == "delete" )
        {
            $PersonID = $url_array[4];
            $Action = "delete";
            include( "ezcontact/user/persondelete.php" );
        }
    }
    break;



    default :
        print( "<h1>Sorry, This page isn't for you. </h1>" );
        break;
}

?>
