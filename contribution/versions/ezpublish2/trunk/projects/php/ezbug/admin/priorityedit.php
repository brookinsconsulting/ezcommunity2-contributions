<?
/*
  Edit a category type.
*/

include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZBugMain", "Language" );

include_once( "classes/eztemplate.php" );

include_once( "ezbug/classes/ezbugpriority.php" );

if ( $Action == "insert" )
{
    $priority = new eZBugPriority();
    $priority->setName( $Name );
    $priority->store();

    Header( "Location: /bug/priority/list/" );
    exit();
}

// Updates a priority.
if ( $Action == "update" )
{
    $priority = new eZBugPriority( $PriorityID );
    $priority->setName( $Name );
    $priority->store();

    Header( "Location: /bug/priority/list/" );
    exit();
}

// Delete a priority.
if ( $Action == "delete" )
{
    $priority = new eZBugPriority( $PriorityID );
    $priority->delete();

    Header( "Location: /bug/priority/list/" );
    exit();
}

$t = new eZTemplate( "ezbug/admin/" . $ini->read_var( "eZBugMain", "AdminTemplateDir" ),
                     "ezbug/admin/intl", $Language, "priorityedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "priorityedit" => "priorityedit.tpl"
    ) );

if ( $Action == "new" )
{
    $t->set_var( "priority_name", "" );
    $t->set_var( "action_value", "insert" );
}

// Edit a priority.
if ( $Action == "edit" )
{
    $priority = new eZBugPriority( $PriorityID );

    $t->set_var( "priority_name", $priority->name() );
    $t->set_var( "priority_id", $priority->id() );

    $t->set_var( "action_value", "update" );
}

$t->pparse( "output", "priorityedit" );
?>
