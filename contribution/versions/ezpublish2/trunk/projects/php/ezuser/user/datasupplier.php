<?


$ini =& INIFile::globalINI();
$GlobalSectionID = $ini->read_var( "site", "DefaultSection" );

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

    case "norights":
    {
        include( "ezuser/user/norights.php" );        
    }
    break;

    case "user" :
    case "userwithaddress" :
    {

        if ( $url_array[3] == "new" )
            $Action = "New";
        if ( $url_array[3] == "edit" )
        {
            if ( $url_array[5] == "MissingAddress" )
                $MissingAddress = true;
            else
                $MissingAddress = false;
            if ( $url_array[5] == "MissingCountry" )
                $MissingCountry = true;
            else
                $MissingCountry = false;

            $UserID = $url_array[4];
            $Action = "Edit";
        }
        if ( $url_array[3] == "update" )
        {
            $UserID = $url_array[4];
            $Action = "Update";
        }

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


    case "address" :
    {
        if ( $url_array[3] == "new" )
            $Action = "New";
        if ( $url_array[3] == "insert" )
            $Action = "Insert";

        if ( $url_array[3] == "edit" )
        {
            $Action = "Edit";
        }
        if ( $url_array[3] == "update" )
        {
            $Action = "Update";
        }
        
        include( "ezuser/user/addressedit.php" );
    }
    break;

    case "logout" :
    {
        $Action = $url_array[2];
        include( "ezuser/user/login.php" );
    }
    break;

    case "unsuccessfull":
    {
        $unsuccessfull = true;
        include( "ezuser/user/forgotmessage.php" );
    }
    break;

    case "successfull":
    {
        $successfull = true;
        include( "ezuser/user/forgotmessage.php" );
    }
    break;

    case "generated":
    {
        $generated = true;
        include( "ezuser/user/forgotmessage.php" );
    }
    break;

    default :
    {
        include( "ezuser/user/login.php" );
    }
    break;


}
?>
