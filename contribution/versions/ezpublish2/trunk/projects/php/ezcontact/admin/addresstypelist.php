<?
/*
  Viser liste over adresse typer.
*/

include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );

include_once( "classes/eztemplate.php" );
include_once( "common/ezphputils.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );

include_once( "ezcontact/classes/ezaddresstype.php" );

$user = eZUser::currentUser();
if ( !$user ) 
{
    Header( "Location: /user/login/" );
    exit();
}

// Sette template.
$t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "addresstypelist.php" );
$t->setAllStrings();

$t->set_file( array(
    "address_type_page" => "addresstypelist.tpl",
    "address_type_item" => "addresstypeitem.tpl"
    ) );

// Liste telefon typer.
$address_type = new eZAddressType();
$address_type_array = $address_type->getAll();

for ( $i=0; $i<count( $address_type_array ); $i++ )
{
    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "bg_color", "#eeeeee" );
    }
    else
    {
        $t->set_var( "bg_color", "#dddddd" );
    }  

    $t->set_var( "address_type_id", $address_type_array[$i][ "ID" ] );
    $t->set_var( "address_type_name", $address_type_array[$i][ "Name" ] );

    $t->parse( "address_type_list", "address_type_item", true );
} 

$t->set_var( "document_root", $DOC_ROOT );
$t->pparse( "output", "address_type_page" );
?>
