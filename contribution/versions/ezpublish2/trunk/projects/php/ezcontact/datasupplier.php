<?
//print $REQUEST_URI;
$url_array = explode( "/", $REQUEST_URI );
switch ( $url_array[2] )
{
    case "" :
        include( "ezcontact/contactlist.php" );        
        break;
    case "contact" :
        include( "ezcontact/contactlist.php" );        
        break;
    case "personedit" :
        include( "ezcontact/personedit.php" );
        break;

    case "companyedit" :
        include( "ezcontact/companyedit.php" );
        break;

    case "personinfo" :
        include( "ezcontact/personinfo.php" );
        break;


    default:
        print( "<h1>Sorry, Your link page could not be found. </h1>" );
        break;
}

?>
