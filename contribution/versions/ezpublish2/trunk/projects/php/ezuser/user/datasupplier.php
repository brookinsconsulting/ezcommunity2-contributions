<?
//  $url_array = explode( "/", $REQUEST_URI );
switch ( $url_array[2] )
{
    case "login" :
    {
        $Action = $url_array[3];
        include( "ezuser/login.php" );
    }
    break;

    case "userwithaddress" :
    {
        if ( $url_array[3] == "new" )
            $Action = "New";
        if ( $url_array[3] == "insert" )
            $Action = "Insert";
        include( "ezuser/userwithaddress.php" );
    }
    break;

    case "forgot" :
    {
        $Action = $url_array[3];
        include( "ezuser/user/forgot.php" );
    }
    break;

    case "user" :
    {

        if ( $url_array[3] == "new" )
            $Action = "New";
        if ( $url_array[3] == "insert" )
            $Action = "Insert";
        if ( $url_array[3] == "edit" )
        {
            $Action = "Edit";
            $UserID = $url_array[4];
        }
        if ( $url_array[3] == "update" )
        {
            $Action = "Update";
            $UserID = $url_array[4];
        }
        
        
        include( "ezuser/useredit.php" );
    }
    break;    
    
    case "success" :
    {
        $Action = $url_array[3];
        include( "ezuser/success.php" );
    }
    break;

    case "logout" :
    {
        $Action = $url_array[3];
        include( "ezuser/login.php" );
    }
    break;

    case "passwordchange" :
    {
        $Action = $url_array[3];
        include( "ezuser/passwordchange.php" );
    }
    break;

}
?>
