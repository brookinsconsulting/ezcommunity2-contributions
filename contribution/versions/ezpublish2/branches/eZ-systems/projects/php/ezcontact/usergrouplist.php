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
                               "user_page" => "templates/usergrouplist.tpl",
                               "user_group_item" => "templates/usergroupitem.tpl"
                               ) );    

$group = new eZUserGroup();
$user_group_array = $group->getAll();

for ( $i=0; $i<count( $user_group_array ); $i++ )
{
  if ( ( $i % 2 ) == 0 )
  {
    $menuTemplate->set_var( "bg_color", "#eeeeee" );
  }
  else
  {
    $menuTemplate->set_var( "bg_color", "#dddddd" );
  }  
  
  $menuTemplate->set_var( "user_group_id", $user_group_array[$i][ "ID" ] );
  $menuTemplate->set_var( "user_group_name", $user_group_array[$i][ "Name" ] );
  $menuTemplate->set_var( "user_group_description", $user_group_array[$i][ "Description" ] );
  $menuTemplate->parse( "user_group_list", "user_group_item", true );
} 

$menuTemplate->pparse( "output", "user_page" );

?>
