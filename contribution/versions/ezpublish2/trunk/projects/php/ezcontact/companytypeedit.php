<?
include  "template.inc";

require "ezcontact/dbsettings.php";
require "ezphputils.php";

require $DOCUMENTROOT . "classes/ezsession.php";
require $DOCUMENTROOT . "classes/ezuser.php";
require $DOCUMENTROOT . "classes/ezusergroup.php";
require $DOCUMENTROOT . "classes/ezcompanytype.php";

// Legge til
if ( $Action == "insert" )
{
  $type = new eZCompanyType();
  $type->setName( $CompanyTypeName );
  $type->setDescription( $CompanyTypeDescription );
  $type->store(); 

  printRedirect( "../index.php?page=" . $DOCUMENTROOT . "companytypelist.php" );
}

// Oppdatere
if ( $Action == "update" )
{
  $type = new eZCompanyType();
  $type->get( $CID );
  
  $type->setName( $CompanyTypeName );
  $type->setDescription( $CompanyTypeDescription );
  $type->update();

  printRedirect( "../index.php?page=" . $DOCUMENTROOT . "companytypelist.php" );
}

// Slette
if ( $Action == "delete" )
{
    $type = new eZCompanyType();
    $type->get( $CID );
    $type->delete( );

    printRedirect( "../index.php?page=" . $DOCUMENTROOT . "companytypelist.php" );
}

// sjekke session
{
  include( $DOCUMENTROOT . "checksession.php" );
}


// hente ut rettigheter
{    
    $session = new eZSession();
    
    if ( !$session->get( $AuthenticatedSession ) )
    {
        die( "Du må logge deg på." );    
    }        
    
    $usr = new eZUser();
    $usr->get( $session->userID() );

    $usrGroup = new eZUserGroup();
    $usrGroup->get( $usr->group() );
}

// vise feilmelding dersom brukeren ikke har rettigheter.
if ( $usrGroup->companyTypeAdmin() == 'N' )
{    
    $t = new Template( "." );
    $t->set_file( array(
        "error_page" => $DOCUMENTROOT . "templates/errorpage.tpl"
        ) );

    $t->set_var( "error_message", "Du har ikke rettiheter til dette." );
    $t->pparse( "output", "error_page" );
}
else
{
    $t = new Template( "." );
    $t->set_file( array(
        "companytype_edit_page" => $DOCUMENTROOT . "templates/companytypeedit.tpl"
        ) );    

    $t->set_var( "submit_text", "Legg til" );
    $t->set_var( "action_value", "insert" );
    $t->set_var( "companytype_id", "" );
    $t->set_var( "head_line", "Legg til firma type" );

// Editere
    if ( $Action == "edit" )
    {
        $type = new eZCompanyType();
        $type->get( $CID );
  
        $CompanyTypeName = $type->name();
        $CompanyTypeDescription = $type->description();

        $t->set_var( "submit_text", "Lagre endringer" );
        $t->set_var( "action_value", "update" );
        $t->set_var( "companytype_id", $CID );
        $t->set_var( "head_line", "Rediger firma type" );

    }

// Sette template variabler
    $t->set_var( "document_root", $DOCUMENTROOT );

    $t->set_var( "companytype_name", $CompanyTypeName );
    $t->set_var( "description", $CompanyTypeDescription );

    $t->pparse( "output", "companytype_edit_page" );
}
?>
