<?
/*
  Editerer firma typer.
*/

include_once( "class.INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );

include_once( "../classes/eztemplate.php" );
include_once( "ezphputils.php" );

// require $DOC_ROOT . "classes/ezsession.php";
// require $DOC_ROOT . "classes/ezuser.php";

include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezusergroup.php" );
include_once( "ezcontact/classes/ezcompanytype.php" );

// Legge til firma type.
if ( $Action == "insert" )
{
  $type = new eZCompanyType();
  $type->setName( $CompanyTypeName );
  $type->setDescription( $CompanyTypeDescription );
  $type->store(); 

  Header( "Location: index.php?page=" . $DOC_ROOT . "companytypelist.php" ); 
}

// Oppdatere firma type.
if ( $Action == "update" )
{
  $type = new eZCompanyType();
  $type->get( $CID );
  
  $type->setName( $CompanyTypeName );
  $type->setDescription( $CompanyTypeDescription );
  $type->update();

  Header( "Location: index.php?page=" . $DOC_ROOT . "companytypelist.php" ); 
}

// Slette firma type.
if ( $Action == "delete" )
{
    $type = new eZCompanyType();
    $type->get( $CID );
    $type->delete( );

    Header( "Location: index.php?page=" . $DOC_ROOT . "companytypelist.php" ); 
}

//  // Sjekke session.
//  {
//    include( $DOC_ROOT . "checksession.php" );
//  }


//  // Hente ut rettigheter.
//  {    
//      $session = new eZSession();
    
//      if ( !$session->get( $AuthenticatedSession ) )
//      {
//          die( "Du må logge deg på." );    
//      }        
    
//      $usr = new eZUser();
//      $usr->get( $session->userID() );

//      $usrGroup = new eZUserGroup();
//      $usrGroup->get( $usr->group() );
//  }

//  // Vise feilmelding dersom brukeren ikke har rettigheter.
//  if ( $usrGroup->companyTypeAdmin() == 'N' )
//  {    
//      $t = new Template( "." );
//      $t->set_file( array(
//          "error_page" => $DOC_ROOT . "templates/errorpage.tpl"
//          ) );

//      $t->set_var( "error_message", "Du har ikke rettiheter til dette." );
//      $t->pparse( "output", "error_page" );
//  }
//  else
{
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
}
?>
