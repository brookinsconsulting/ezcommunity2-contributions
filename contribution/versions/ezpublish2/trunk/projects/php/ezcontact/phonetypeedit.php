<?

include_once( "class.INIFile.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZContactMain", "Language" );

$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );

include_once( "../classes/eztemplate.php" );
include_once( "ezphputils.php" );
// include_once( "../classes/ezsession.php" );
// include_once( "../ezcontact/classes/ezuser.php" );
include_once( "ezcontact/classes/ezusergroup.php" );
include_once( "ezcontact/classes/ezphonetype.php" );



// Legge til
if ( $Action == "insert" )
{
    $type = new eZPhoneType();
    $type->setName( $PhoneTypeName );
    $type->store();

    Header( "Location: index.php?page=" . $DOC_ROOT . "phonetypelist.php" ); 
}

// Oppdatere
if ( $Action == "update" )
{
  $type = new eZPhoneType();
  $type->get( $PID );
  print ( "$PID" );
  $type->setName( $PhoneTypeName );
  $type->update();

    Header( "Location: index.php?page=" . $DOC_ROOT . "phonetypelist.php" ); 
}

// Slette
if ( $Action == "delete" )
{
    $type = new eZPhoneType();
    $type->get( $PID );
    $type->delete( );

    Header( "Location: index.php?page=" . $DOC_ROOT . "phonetypelist.php" ); 
}

// sjekke session
// {
//   include( $DOC_ROOT . "checksession.php" );
// }


// sjekke rettigheter
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

// vise feilmelding dersom brukeren ikke har rettigheter.
//  if ( $usrGroup->phoneTypeAdmin() == 'N' )
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
//    $t = new Template( "." );

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
}
?>
