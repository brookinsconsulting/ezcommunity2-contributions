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

//  // sjekke session
//  {
//      include( $DOC_ROOT . "checksession.php" );
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
    $t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "persontypelist.php" );
    $t->setAllStrings();

    $t->set_file( array(
        "persontype_page" => "persontypelist.tpl",
        "persontype_item" => "persontypeitem.tpl"
        ) );    

    $persontype = new eZPersonType();
    $persontype_array = $persontype->getAll();

    for ( $i=0; $i<count( $persontype_array ); $i++ )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "bg_color", "#eeeeee" );
        }
        else
        {
            $t->set_var( "bg_color", "#dddddd" );
        }

        $t->set_var( "persontype_id", $persontype_array[$i][ "ID" ] );
        $t->set_var( "persontype_name", $persontype_array[$i][ "Name" ] );
        $t->set_var( "description", $persontype_array[$i][ "Description" ] );
        $t->parse( "persontype_list", "persontype_item", true );
    }

    $t->set_var( "document_root", $DOC_ROOT );
    $t->pparse( "output", "persontype_page" );
}
?>
