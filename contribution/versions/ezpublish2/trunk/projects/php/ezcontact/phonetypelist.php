<?
include_once( "class.INIFile.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZContactMain", "Language" );

$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );

include_once( "../classes/eztemplate.php" );
include_once( "ezphputils.php" );
// include_once( "../classes/ezsession.php" );
// include_once( "../ezcontact/classes/ezuser.php" );
include_once( "../ezcontact/classes/ezusergroup.php" );
include_once( "../ezcontact/classes/ezphonetype.php" );

// sjekke session
//  {
//    include( $DOC_ROOT . "checksession.php" );
//  }

// hente ut rettigheter
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
?>
