<?

include  "template.inc";
require "ezcontact/dbsettings.php";
require "ezphputils.php";
require $DOCUMENTROOT . "classes/ezsession.php";
require $DOCUMENTROOT . "classes/ezuser.php";
require $DOCUMENTROOT . "classes/ezpersontype.php";

// sjekke session
{
  include( $DOCUMENTROOT . "checksession.php" );
}

$t = new Template( "." );
$t->set_file( array(
                    "persontype_edit_page" => $DOCUMENTROOT . "templates/persontypeedit.tpl"
                    ) );    


$t->set_var( "submit_text", "Legg til" );
$t->set_var( "action_value", "insert" );
$t->set_var( "persontype_id", "" );
$t->set_var( "head_line", "Legg til person type" );

// Legge til
if ( $Action == "insert" )
{
  $type = new eZPersonType();
  $type->setName( $PersonTypeName );
  $type->setDescription( $PersonTypeDescription );
  $type->store(); 
}

// Editere
if ( $Action == "edit" )
{
  $type = new eZPersonType();
  $type->get( $PID );
  
  $PersonTypeName = $type->name();
  $PersonTypeDescription = $type->description();

  $t->set_var( "submit_text", "Lagre endringer" );
  $t->set_var( "action_value", "update" );

  $t->set_var( "persontype_id", $PID );

  $t->set_var( "head_line", "Rediger person type" );
}

// Oppdatere
if ( $Action == "update" )
{
  $type = new eZPersonType();
  $type->get( $PID );
  print ( "$PID ..." );

  $type->setName( $PersonTypeName );
  $type->setDescription( $PersonTypeDescription );
  $type->update(); 
}

$t->set_var( "document_root", $DOCUMENTROOT );

$t->set_var( "persontype_name", $PersonTypeName );
$t->set_var( "description", $PersonTypeDescription );

$t->pparse( "output", "persontype_edit_page" );

?>
