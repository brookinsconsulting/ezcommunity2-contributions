<?
/*
  Editerer firma typer.
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

include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezcompanytype.php" );

require( "ezuser/admin/admincheck.php" );

// Legge til firma type.
if ( $Action == "insert" )
{
    if ( eZPermission::checkPermission( $user, "eZContact", "AdminAdd" ) )
    {
        $type = new eZCompanyType();
        $type->setName( $CompanyTypeName );
        $type->setDescription( $CompanyTypeDescription );
        $type->store(); 

        Header( "Location: /contact/companytypelist/" );
    }
    else
    {
        print( "Du har ikke rettigheter.");
    }
}

// Oppdatere firma type.
if ( $Action == "update" )
{
    if ( eZPermission::checkPermission( $user, "eZContact", "AdminModify" ) )
    {
        $type = new eZCompanyType();
        $type->get( $CID );
  
        $type->setName( $CompanyTypeName );
        $type->setDescription( $CompanyTypeDescription );
        $type->update();

        Header( "Location: /contact/companytypelist/" );
    }
    else
    {
        print( "Du har ikke rettigheter.");
    }
}

// Slette firma type.
if ( $Action == "delete" )
{
    if ( eZPermission::checkPermission( $user, "eZContact", "AdminDelete" ) )
    {
        $type = new eZCompanyType();
        $type->get( $CID );
        $type->delete( );

        Header( "Location: /contact/companytypelist/" );
    }
    else
    {
        print( "Du har ikke rettigheter.");
    }
}

// Setter template.
$t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "companytypeedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "companytype_edit_page" => "companytypeedit.tpl"
    ) );    

$t->set_var( "submit_text", "Legg til" );
$t->set_var( "action_value", "insert" );
$t->set_var( "companytype_id", "" );
$t->set_var( "head_line", "Legg til ny firmatype" );

// Editere firma type.
if ( $Action == "edit" )
{
    $type = new eZCompanyType();
    $type->get( $CID );
  
    $CompanyTypeName = $type->name();
    $CompanyTypeDescription = $type->description();

    $t->set_var( "submit_text", "Lagre endringer" );
    $t->set_var( "action_value", "update" );
    $t->set_var( "companytype_id", $CID );
    $t->set_var( "head_line", "Rediger firmatype" );
}

// Sette template variabler.
$t->set_var( "document_root", $DOC_ROOT );

$t->set_var( "companytype_name", $CompanyTypeName );
$t->set_var( "description", $CompanyTypeDescription );

$t->pparse( "output", "companytype_edit_page" );
?>
