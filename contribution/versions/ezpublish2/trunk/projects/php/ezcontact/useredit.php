<?
include  "template.inc";
require "ezphputils.php";
require "ezcontact/dbsettings.php";
require $DOCUMENTROOT . "classes/ezsession.php";
 require $DOCUMENTROOT . "classes/ezuser.php";
require $DOCUMENTROOT . "classes/ezusergroup.php";


// Slett
if ( $Action == "delete" )
{
    $user = new eZUser();
    $user->get( $UID );
    $user->delete();

    printRedirect( "../index.php?page=" . $DOCUMENTROOT . "userlist.php" );
}

// Legg til
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
   printRedirect( "../index.php?page=" . $DOCUMENTROOT . "userlist.php" );  
}

// Oppdater
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

   printRedirect( "../index.php?page=" . $DOCUMENTROOT . "userlist.php" );
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
if ( $usrGroup->userAdmin() == 'N' )
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
        "user_edit_page" => $DOCUMENTROOT . "templates/useredit.tpl",
        "user_group_select" => $DOCUMENTROOT . "templates/usergroupselect.tpl"
        ) );    

    $t->set_var( "submit_text", "Legg til" );
    $t->set_var( "action_value", "insert" );
    $t->set_var( "user_id", "" );
    $t->set_var( "head_line", "Legg til ny bruker" );
    
// Editer
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

    $t->set_var( "user_login", $Login );
    $t->set_var( "document_root", $DOCUMENTROOT );

    $t->pparse( "output", "user_edit_page" );
}
?>
