<?
include  "template.inc";
require "ezphputils.php";
require "ezcontact_ce/dbsettings.php";
require $DOCUMENTROOT . "classes/ezsession.php";
 require $DOCUMENTROOT . "classes/ezuser.php";
require $DOCUMENTROOT . "classes/ezusergroup.php";


// Oppdater
if ( $Action == "update" )
{
  $user = new eZUser();
  $user->get( $UID );
//  $user->setLogin( $Login );

  if (( $Pwd == $PwdVer ) && $Pwd != "" )
  {
      $user->setPassword( $Pwd );
  }
  $user->update();

//   printRedirect( "../index.php?page=" . $DOCUMENTROOT . "userlist.php" );
}

// sjekke session
{
    include( $DOCUMENTROOT . "checksession.php" );
}

// hente ut gjeldende bruker
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
    $t = new Template( "." );
    $t->set_file( array(
        "user_edit_page" => $DOCUMENTROOT . "templates/passwordedit.tpl"
         ) );    

    $t->set_var( "submit_text", "endre" );
    $t->set_var( "user_id", "" );
    $Action = "edit";

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


    $t->set_var( "user_login", $Login );
    $t->set_var( "document_root", $DOCUMENTROOT );


    $t->pparse( "output", "user_edit_page" );
}
?>
