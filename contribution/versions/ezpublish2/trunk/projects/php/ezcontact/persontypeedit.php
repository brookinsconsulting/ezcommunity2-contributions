<?

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
include_once( "ezcontact/classes/ezpersontype.php" );

// Legge til
if ( $Action == "insert" )
{
  $type = new eZPersonType();
  $type->setName( $PersonTypeName );
  $type->setDescription( $PersonTypeDescription );
  $type->store();

  Header( "Location: index.php?page=" . $DOC_ROOT . "persontypelist.php" ); 
}

// Oppdatere
if ( $Action == "update" )
{
  $type = new eZPersonType();
  $type->get( $PID );
  print ( "$PID ..." );

  $type->setName( $PersonTypeName );
  $type->setDescription( $PersonTypeDescription );
  $type->update();

  Header( "Location: index.php?page=" . $DOC_ROOT . "persontypelist.php" ); 
}

// Slette
if ( $Action == "delete" )
{
    $type = new eZPersonType();
    $type->get( $PID );
    $type->delete( );
  Header( "Location: index.php?page=" . $DOC_ROOT . "persontypelist.php" ); 
}

//  // sjekke session
//  {
//    include( $DOC_ROOT . "checksession.php" );
//  }

//  // hente ut rettigheter
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

//  // vise feilmelding dersom brukeren ikke har rettigheter.
//  if ( $usrGroup->personTypeAdmin() == 'N' )
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
    $t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "persontypeedit.php" );
    $t->setAllStrings();

    $t->set_file( array(
        "persontype_edit_page" => "persontypeedit.tpl"
        ) );    

    $t->set_var( "submit_text", "Legg til" );
    $t->set_var( "action_value", "insert" );
    $t->set_var( "persontype_id", "" );
    $t->set_var( "head_line", "Legg til ny persontype" );

// Editere
    if ( $Action == "edit" )
    {
        $type = new eZPersonType();
        $type->get( $PID );
  
        $PersonTypeName = $type->name();
        $PersonTypeDescription = $type->description();

        $t->set_var( "submit_text", "Lagre endringer" );
        $t->set_var( "action_value", "update" );
        $t->set_var( "persontype_id", $PID );
        $t->set_var( "head_line", "Rediger persontype" );

    }

// Sette tempalte variabler
    $t->set_var( "document_root", $DOC_ROOT );
    $t->set_var( "persontype_name", $PersonTypeName );
    $t->set_var( "description", $PersonTypeDescription );

    $t->pparse( "output", "persontype_edit_page" );
}
?>
