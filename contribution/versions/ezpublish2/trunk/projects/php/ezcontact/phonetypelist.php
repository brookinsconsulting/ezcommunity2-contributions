<?
/*
  Viser liste over kontakt typer.
*/
include_once( "classes/class.INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezsession.php" );
include_once( "classes/ezusergroup.php" );
include_once( "classes/ezuser.php" );

include_once( "common/ezphputils.php" );

include_once( "../ezcontact/classes/ezphonetype.php" );

// Sjekker rettigheter
$session = new eZSession();
if( $session->get( $AuthenticatedSession ) == 0 )
{
    if ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_Read" ) == 1 )
    {

    $t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "phonetypelist.php" );
    $t->setAllStrings();

    $t->set_file( array(
        "phone_type_page" =>  "phonetypelist.tpl",
        "phone_type_item" =>  "phonetypeitem.tpl"
        ) );

    $phone_type = new eZPhoneType();
    $phone_type_array = $phone_type->getAll();

    for ( $i=0; $i<count( $phone_type_array ); $i++ )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "bg_color", "#eeeeee" );
        }
        else
        {
            $t->set_var( "bg_color", "#dddddd" );
        }  

        $t->set_var( "document_root", $DOC_ROOT );
        $t->set_var( "phone_type_id", $phone_type_array[$i][ "ID" ] );
        $t->set_var( "phone_type_name", $phone_type_array[$i][ "Name" ] );

        $t->parse( "phone_type_list", "phone_type_item", true );
    } 

    $t->pparse( "output", "phone_type_page" );
    }
    else
    {
        print( "\nDu har ikke rettigheter\n" );
    }
}
else
{
    Header( "Location: index.php?page=common/error.php" );
}

?>
