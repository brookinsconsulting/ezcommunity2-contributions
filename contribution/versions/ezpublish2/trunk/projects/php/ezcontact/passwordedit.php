<?
/*
  Editere passord.
*/

include_once( "classes/class.INIFile.php" );

$ini = new INIFile( "site.ini" );
$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );
$Language = $ini->read_var( "eZContactMain", "Language" );

include_once( "../classes/eztemplate.php" );
include_once(  "ezphputils.php" ); 

include_once( "ezcontact/classes/ezsession.php" );
include_once( "ezcontact/classes/ezuser.php" );
include_once( "ezcontact/classes/ezusergroup.php" ); 


// Oppdater passord.
if ( $Action == "update" )
{
  $user = new eZUser();
  $user->get( $UID );

  if (( $Pwd == $PwdVer ) && $Pwd != "" )
  {
      $user->setPassword( $Pwd );
  }
  $user->update();
}

// Sjekke session.
{
    include( $DOC_ROOT . "checksession.php" );
}

// Hente ut gjeldende bruker.
{    
    $session = new eZSession();
    
    if ( !$session->get( $AuthenticatedSession ) )
    {
        die( "Du må logge deg på." );    
    }        
    
    $usr = new eZUser();
    $usr->get( $session->userID() );

    $UID = $usr->id();
}


{
    // Sette template.
    $t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var ( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "passwordedit.php" );
    $t->setAllStrings();

    $t->set_file( array(
        "user_edit_page" => "passwordedit.tpl"
         ) );    

    $t->set_var( "submit_text", "endre" );
    $t->set_var( "user_id", "" );
    $Action = "edit";

// Editer passord.
    if ( $Action == "edit" )
    {
        $user = new eZUser();
        $user->get( $UID );

        $Login = $user->login();
        $Group = $user->group();
 
        $t->set_var( "submit_text", "Lagre endringer" );
        $t->set_var( "action_value", "update" );
        $t->set_var( "user_id", $UID  );
    }

    $group = new eZUserGroup();
    $group_array = $group->getAll();

    $t->set_var( "user_login", $Login );
    $t->set_var( "document_root", $DOC_ROOT );

    $t->pparse( "output", "user_edit_page" );
}
?>
