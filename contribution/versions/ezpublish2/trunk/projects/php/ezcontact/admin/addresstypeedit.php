<?
/*
  Editerer en adresse type.
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

// Legge til
if ( $Action == "insert" )
{
    if ( eZPermission::checkPermission( $user, "eZContact", "AdminAdd" ) )
    {
        $type = new eZAddressType();
        $type->setName( $AddressTypeName );
        $type->store();    

        Header( "Location: /contact/addresstypelist/" );
    }
    else
    {
        print( "Du har ikke rettigheter.");
    }
}

// Oppdatere
if ( $Action == "update" )
{
    if ( eZPermission::checkPermission( $user, "eZContact", "AdminModify" ) )
    {
        $type = new eZAddressType();
        $type->get( $AID );
        $type->setName( $AddressTypeName );
        $type->update();

        Header( "Location: /contact/addresstypelist" );
    }
    else
    {
        print( "Du har ikke rettigheter.");
    }
}

// Slette
if ( $Action == "delete" )
{
    if ( eZPermission::checkPermission( $user, "eZContact", "AdminDelete" ) )
    {
        $type = new eZAddressType();
        $type->get( $AID );
        $type->delete( );

        Header( "Location: /contact/addresstypelist" );
    }
    else
    {
        print( "Du har ikke rettigheter.");
    }
}

$t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "addresstypeedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "address_type_edit_page" =>  "addresstypeedit.tpl"
    ) );    

$t->set_var( "submit_text", "Legg til" );
$t->set_var( "action_value", "insert" );
$t->set_var( "address_type_id", "" );
$t->set_var( "head_line", "Legg til ny addressetype" );

// Editere
if ( $Action == "edit" )
{
    $type = new eZAddressType();
    $type->get( $AID );
    $type->name( $AddressTypeName );
    
    $t->set_var( "submit_text", "Lagre endringer" );
    $t->set_var( "action_value", "update" );
    $t->set_var( "address_type_id", $AID  );  
    $t->set_var( "head_line", "Rediger addressetype");

    $AddressTypeName = $type->name();
}

// Sette template variabler
$t->set_var( "document_root", $DOC_ROOT );
$t->set_var( "address_type_name", $AddressTypeName );

$t->pparse( "output", "address_type_edit_page" );
?>
