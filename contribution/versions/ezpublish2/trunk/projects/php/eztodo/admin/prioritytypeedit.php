<?
/*
  Edit a category type.
*/

include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZTodoMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZTodoMain", "DocumentRoot" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );

include_once( "eztodo/classes/ezpriority.php" );

if ( $Action == "insert" )
{
    $type = new eZPriority();
    $type->setName( $Name );
    $type->store();

    eZHTTPTool::header( "Location: /todo/prioritytypelist/" );
}

// Updates a priority.
if ( $Action == "update" )
{
    $type = new eZPriority();
    $type->get( $PriorityID );
    $type->setName( $Name );
    $type->store();

    eZHTTPTool::header( "Location: /todo/prioritytypelist/" );
    exit();
}

// Delete a priority.
if ( $Action == "delete" )
{
    $type = new eZPriority();
    $type->get( $PriorityID );
    $type->delete();

    eZHTTPTool::header( "Location: /todo/prioritytypelist/" );
    exit();
}

$t = new eZTemplate( "eztodo/admin/" . $ini->read_var( "eZTodoMain", "AdminTemplateDir" ),
                     "eztodo/admin/intl", $Language, "prioritytypeedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "prioritytypeedit" => "prioritytypeedit.tpl"
    ) );

$t->set_var( "action_value", "insert" );

// Edit a priority.
if ( $Action == "edit" )
{
    $type = new eZPriority();
    $type->get( $PriorityID );

    $PriorityName = $type->name();

    {
        $type_array = $type->get( $PriorityID );

        for ( $i=0; $i<count( $type_array); $i++ )
        {
            print( $type_array[$i][ "Name" ] );
        }
    }

    $t->set_var( "priority_type_id", $PriorityID );
    $t->set_var( "action_value", "update" );
}

$t->set_var( "priority_type_name", $PriorityName );
$t->set_var( "head_line", $headline );
$t->set_var( "submit_text", $submittext );
$t->set_var( "document_root", $DOC_ROOT );

$t->pparse( "output", "prioritytypeedit" );
?>
