<?
/*
  Editere en bruker.
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


// Slett en bruker.
if ( $Action == "delete" )
{
    $user = new eZUser();
    $user->get( $UID );
    $user->delete();

    printRedirect( "../index.php?page=" . $DOC_ROOT . "userlist.php" );
}

// Legg til en bruker.
if ( $Action == "insert" )
{
  if (( $Pwd != $PwdVer ) || $Pwd == "" )
  {
    print( "Passord er ikke like" );
  }
  else
  {
    $user = new eZUser( );
    $user->setLogin( $Login );
    $user->setPassword( $Pwd );
    $user->setGroup( $UserGroup );
    $user->store();
  }
   printRedirect( "../index.php?page=" . $DOC_ROOT . "userlist.php" );  
}

// Oppdater en bruker.
if ( $Action == "update" )
{
  $user = new eZUser();
  $user->get( $UID );

  $user->setLogin( $Login );
  $user->setGroup( $UserGroup );

  if (( $Pwd == $PwdVer ) && $Pwd != "" )
  {
      $user->setPassword( $Pwd );
  }


  $user->update();

   printRedirect( "../index.php?page=" . $DOC_ROOT . "userlist.php" );
}

// Sjekke session.
{
    include( $DOC_ROOT . "checksession.php" );
}

// Hente ut rettigheter.
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

// Vise feilmelding dersom brukeren ikke har rettigheter.
if ( $usrGroup->userAdmin() == 'N' )
{    
    $t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var ( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "useredit.php" );
    $t->setAllStrings();

    $t->set_file( array(
        "error_page" => "errorpage.tpl"
        ) );

    $t->set_var( "error_message", "Du har ikke rettiheter til dette." );
    $t->pparse( "output", "error_page" );
}
else
{
    // Sette template.
    $t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var ( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "useredit.php" );
    $t->setAllStrings();
    
    $t->set_file( array(
        "user_edit_page" => "useredit.tpl",
        "user_group_select" => "usergroupselect.tpl"
        ) );    

    $t->set_var( "submit_text", "Legg til" );
    $t->set_var( "action_value", "insert" );
    $t->set_var( "user_id", "" );
    $t->set_var( "head_line", "Legg til ny bruker" );
    
// Editer en gruppe.
    if ( $Action == "edit" )
    {
        $user = new eZUser();
        $user->get( $UID );

        $Login = $user->login();
        $Group = $user->group();
 
        $t->set_var( "submit_text", "Lagre endringer" );
        $t->set_var( "action_value", "update" );
        $t->set_var( "user_id", $UID  );  
    	$t->set_var( "head_line", "Rediger bruker" );
    }

    $group = new eZUserGroup();
    $group_array = $group->getAll();

    for ( $i=0; $i<count( $group_array ); $i++ )
    {
        if  ( $Group == $group_array[$i][ "ID" ] )
        {
            $t->set_var( "is_selected", "selected" );
        }
        else
        {
            $t->set_var( "is_selected", "" );        
        }
        
        $t->set_var( "user_group_id", $group_array[$i][ "ID" ] );
        $t->set_var( "user_group_name", $group_array[$i][ "Name" ] );
    
        $t->parse( "user_group", "user_group_select", true );  
    }

    // Setter template variabler.
    $t->set_var( "user_login", $Login );
    $t->set_var( "document_root", $DOC_ROOT );

    $t->pparse( "output", "user_edit_page" );
}
?>
