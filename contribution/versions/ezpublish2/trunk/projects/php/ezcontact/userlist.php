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

$menuTemplate = new Template( "." );
$menuTemplate->set_file( array(
                               "user_page" => "templates/userlist.tpl",
                               "user_item" => "templates/useritem.tpl"
                               ) );    

$user = new eZUser();
$user_array = $user->getAll();

for ( $i=0; $i<count( $user_array ); $i++ )
{
  if ( ( $i % 2 ) == 0 )
  {
    $menuTemplate->set_var( "bg_color", "#eeeeee" );
  }
  else
  {
    $menuTemplate->set_var( "bg_color", "#dddddd" );
  }  

  $menuTemplate->set_var( "user_id", $user_array[$i][ "ID" ] );
  $menuTemplate->set_var( "user_name", $user_array[$i][ "Login" ] );

  $group = new eZUserGroup( );
  $group->get( $user_array[$i][ "Grp" ] );
  $menuTemplate->set_var( "user_group", $group->name() );
  
  $menuTemplate->parse( "user_list", "user_item", true );
} 

$menuTemplate->pparse( "output", "user_page" );
?>
