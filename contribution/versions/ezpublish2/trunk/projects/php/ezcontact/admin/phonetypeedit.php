<?
/*
  Editerer en kontakt type
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

include_once( "../ezcontact/classes/ezphonetype.php" );

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
        $type = new eZPhoneType();
        $type->setName( $PhoneTypeName );
        $type->store();

        Header( "Location: /contact/phonetypelist/" );
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
        $type = new eZPhoneType();
        $type->get( $PID );
        print ( "$PID" );
        $type->setName( $PhoneTypeName );
        $type->update();

        Header( "Location: /contact/phonetypelist/" );
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
        $type = new eZPhoneType();
        $type->get( $PID );
        $type->delete( );

        Header( "Location: /contact/phonetypelist/" );
    }
    else
    {
        print( "Du har ikke rettigheter.");
    }
}

$t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "phonetypeedit.php" );
$t->setAllStrings();

$t->set_file( array(
"phone_type_edit_page" => "phonetypeedit.tpl"
) );    

$t->set_var( "submit_text", "Legg til" );
$t->set_var( "action_value", "insert" );
$t->set_var( "phone_type_id", "" );
$t->set_var( "head_line", "Legg til nytt kontaktmedium" );

// Editere
if ( $Action == "edit" )
{
$type = new eZPhoneType();
$type->get( $PID );
$type->name( $PhoneTypeName );

$t->set_var( "submit_text", "Lagre endringer" );
$t->set_var( "action_value", "update" );
$t->set_var( "phone_type_id", $PID  );
$t->set_var( "head_line", "Rediger kontaktmedium" );

$PhoneTypeName = $type->name();
}

// Sette template variabler
$t->set_var( "document_root", $DOC_ROOT );
$t->set_var( "phone_type_name", $PhoneTypeName );

$t->pparse( "output", "phone_type_edit_page" );
?>
