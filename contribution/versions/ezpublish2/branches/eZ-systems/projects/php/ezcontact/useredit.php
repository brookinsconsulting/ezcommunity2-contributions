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

$t = new Template( "." );
$t->set_file( array(
                    "user_edit_page" => "templates/useredit.tpl",
                    "user_group_select" => "templates/usergroupselect.tpl"
                    ) );    


$t->set_var( "submit_text", "Legg til" );
$t->set_var( "action_value", "insert" );
$t->set_var( "user_id", "" );

$group = new eZUserGroup();
$group_array = $group->getAll();

for ( $i=0; $i<count( $group_array ); $i++ )
{
  $t->set_var( "user_group_id", $group_array[$i][ "ID" ] );
  $t->set_var( "user_group_name", $group_array[$i][ "Name" ] );
  
  $t->parse( "user_group", "user_group_select", true );
  
}

$t->set_var( "user_login", $Login );


$t->pparse( "output", "user_edit_page" );

?>
