<?
include  "template.inc";
require "ezcontact/dbsettings.php";
require "ezphputils.php";
require $DOCUMENTROOT . "classes/ezsession.php";
require $DOCUMENTROOT . "classes/ezuser.php";
require $DOCUMENTROOT . "classes/ezphonetype.php";

// sjekke session
{
  include( $DOCUMENTROOT . "checksession.php" );
}

$t = new Template( "." );
$t->set_file( array(
                    "phone_type_edit_page" => $DOCUMENTROOT . "templates/phonetypeedit.tpl"
                    ) );    


$t->set_var( "submit_text", "Legg til" );
$t->set_var( "action_value", "insert" );
$t->set_var( "phone_type_id", "" );
$t->set_var( "head_line", "Legg til telefon type" );

if ( $Action == "insert" )
{
  $type = new eZPhoneType();
  $type->setName( $PhoneTypeName );
  $type->store(); 
}

if ( $Action == "edit" )
{
  $type = new eZPhoneType();
  $type->get( $PID );
  $type->name( $PhoneTypeName );

  $t->set_var( "submit_text", "Lagre endringer" );
  $t->set_var( "action_value", "update" );
  $PhoneTypeName = $type->name();
  $t->set_var( "phone_type_id", $PID  );
  $t->set_var( "head_line", "Rediger telefon type" );
}

if ( $Action == "update" )
{
  $type = new eZPhoneType();
  $type->get( $PID );
  print ( "$PID" );
  $type->setName( $PhoneTypeName );
  $type->update(); 
}

$t->set_var( "document_root", $DOCUMENTROOT );
$t->set_var( "phone_type_name", $PhoneTypeName );

$t->pparse( "output", "phone_type_edit_page" );

?>
