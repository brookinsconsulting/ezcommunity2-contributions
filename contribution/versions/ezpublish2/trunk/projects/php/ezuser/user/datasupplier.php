<?
//  $url_array = explode( "/", $REQUEST_URI );
switch ( $url_array[2] )
{
    case "login" :
    {
        $Action = $url_array[3];
        include( "ezuser/user/login.php" );
    }
    break;

    case "loginmain" :
    {
        $Action = $url_array[3];
        include( "ezuser/user/loginmain.php" );
    }
    break;

    
    case "userwithaddress" :
    {
        if ( $url_array[3] == "new" )
            $Action = "New";
        if ( $url_array[3] == "insert" )
            $Action = "Insert";
        include( "ezuser/user/userwithaddress.php" );
    }
    break;

    case "forgot" :
    {
        $Action = $url_array[3];
        $Hash = $url_array[4];
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
        
        
        include( "ezuser/user/useredit.php" );
    }
    break;    
    
    case "logout" :
    {
        $Action = $url_array[3];
        include( "ezuser/user/login.php" );
    }
    break;
}
?>
