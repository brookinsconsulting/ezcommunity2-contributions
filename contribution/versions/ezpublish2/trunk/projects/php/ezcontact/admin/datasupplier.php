<?
//print $REQUEST_URI;

$url_array = explode( "/", $REQUEST_URI );

switch ( $url_array[2] )
{
    case "" :
        include( "ezcontact/admin/phonetypelist.php" );        
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
        include( "ezcontact/admin/phonetypeedit.php" );
        break;
    case "persontypeedit" :
        include( "ezcontact/admin/persontypeedit.php" );
        break;
    case "companytypeedit" :
        include( "ezcontact/admin/companytypeedit.php" );
        break;
    case "addresstypeedit" :
        include( "ezcontact/admin/addresstypeedit.php" );
        break;



    default :
        print( "<h1>Sorry, Your link page could not be found. </h1>" );
        break;
}

?>
