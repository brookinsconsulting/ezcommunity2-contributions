<?
/*
    View a person
 */
 
include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );

include_once( "classes/eztemplate.php" );
include_once( "ezuser/classes/ezuser.php" );

include_once( "ezcontact/classes/ezperson.php" );

$user = eZUser::currentUser();

/*
    The user wants to view an existing person.
    
    We present a page with the info.
 */
if ( $Action == "view" )
{
    // use code from edit.
}

?>
