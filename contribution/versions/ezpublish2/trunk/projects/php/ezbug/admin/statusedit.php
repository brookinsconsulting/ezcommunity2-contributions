<?
/*
  Edit a status type.
*/

include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZBugMain", "Language" );

include_once( "classes/eztemplate.php" );

include_once( "ezbug/classes/ezbugstatus.php" );

if ( $Action == "insert" )
{
    $status = new eZBugStatus();
    $status->setName( $Name );
    $status->store();

    Header( "Location: /bug/status/list/" );
    exit();
}

// Updates a status.
if ( $Action == "update" )
{
    $status = new eZBugStatus( $StatusID );
    $status->setName( $Name );
    $status->store();

    Header( "Location: /bug/status/list/" );
    exit();
}

// Delete a status.
if ( $Action == "delete" )
{
    $status = new eZBugStatus( $StatusID );
    $status->delete();

    Header( "Location: /bug/status/list/" );
    exit();
}

$t = new eZTemplate( "ezbug/admin/" . $ini->read_var( "eZBugMain", "AdminTemplateDir" ),
                     "ezbug/admin/intl", $Language, "statusedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "statusedit" => "statusedit.tpl"
    ) );

if ( $Action == "new" )
{
    $t->set_var( "status_name", "" );
    $t->set_var( "action_value", "insert" );
}

// Edit a status.
if ( $Action == "edit" )
{
    $status = new eZBugStatus( $StatusID );

    $t->set_var( "status_name", $status->name() );
    $t->set_var( "status_id", $status->id() );

    $t->set_var( "action_value", "update" );
}

$t->pparse( "output", "statusedit" );
?>
