<?php
include  "template.inc";
require "ezcontact/dbsettings.php";

require  "ezphputils.php";
require $DOCUMENTROOT . "classes/ezperson.php";
require $DOCUMENTROOT . "classes/ezpersontype.php";
require $DOCUMENTROOT . "classes/ezsession.php";
require $DOCUMENTROOT . "classes/ezuser.php";
require $DOCUMENTROOT . "classes/ezusergroup.php";
require $DOCUMENTROOT . "classes/ezcompany.php";


include( $DOCUMENTROOT . "checksession.php" );

$t = new Template( "." );
$t->set_file( "admin", $DOCUMENTROOT .  "templates/admin.tpl" );

$t->set_var( "document_root", $DOCUMENTROOT );

$session = new eZSession();

if ( !$session->get( $AuthenticatedSession ) )
{
    die( "Du må logge deg på." );    
}        

$usr = new eZUser();
$usr->get( $session->userID() );
$t->pparse( "output", "admin" );

?>

