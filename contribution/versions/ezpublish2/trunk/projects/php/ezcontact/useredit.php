<?
include  "template.inc";
require "ezphputils.php";
require "ezcontact/dbsettings.php";
require $DOCUMENTROOT . "classes/ezsession.php";
 require $DOCUMENTROOT . "classes/ezuser.php";
require $DOCUMENTROOT . "classes/ezusergroup.php";


// sjekke session
{
    include( $DOCUMENTROOT . "checksession.php" );
}

$t = new Template( "." );
$t->set_file( array(
    "user_edit_page" => $DOCUMENTROOT . "templates/useredit.tpl",
    "user_group_select" => $DOCUMENTROOT . "templates/usergroupselect.tpl"
    ) );    

$t->set_var( "submit_text", "Legg til" );
$t->set_var( "action_value", "insert" );
$t->set_var( "user_id", "" );

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
    $user = new eZUser( $Login, $Pwd );
    $user->setGroup( $UserGroup );
    $user->store();
  }
}

// Oppdater
if ( $Action == "update" )
{
  $user = new eZUser();
  $user->get( $UID );

  $user->setLogin( $Login );
  $user->setGroup( $UserGroup );

  $user->update();

   printRedirect( "../index.php?page=" . $DOCUMENTROOT . "userlist.php" );
}
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

?>
