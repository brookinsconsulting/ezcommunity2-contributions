<?
include  "template.inc";
require "ezphputils.php";
require "ezsession.php";
require "ezuser.php";
require "ezusergroup.php";


// sjekke session
{
    include( "checksession.php" );
}

$t = new Template( "." );
$t->set_file( array(
    "user_edit_page" => "templates/useredit.tpl",
    "user_group_select" => "templates/usergroupselect.tpl"
    ) );    

$t->set_var( "submit_text", "Legg til" );
$t->set_var( "action_value", "insert" );
$t->set_var( "user_id", "" );


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

if ( $Action == "update" )
{
  $user = new eZUser();
  $user->get( $UID );

  $user->setLogin( $Login );
  $user->setGroup( $UserGroup );

  $user->update();
}

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
//$t->set_var( "user_group", $Group );


$t->pparse( "output", "user_edit_page" );

?>
