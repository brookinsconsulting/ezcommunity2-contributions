<?
/*
  Edit a category type.
*/

include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZTodoMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZTodoMain", "DocumentRoot" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezsession.php" );
include_once( "classes/ezusergroup.php" );
include_once( "common/ezphputils.php" );

include_once( "../eztodo/classes/ezpriority.php" );

// Chekc rights
//  $session = new eZSession();
//  if( $session->get( $AuthenticatedSession ) == 0 )
//  {
// Insert a priority.
if ( $Action == "insert" )
{
    $type = new eZPriority();
    $type->setTitle( $Title );
    $type->store();

    Header( "Location: /todo/prioritytypelist/" );
}

// Update a priority.
if ( $Action == "update" )
{
    $type = new eZPriority();
    $type->get( $PriorityID );
    $type->setTitle( $Title );
    $type->update();

    Header( "Location: /todo/prioritytypelist/" );
}

// Delete a priority.
if ( $Action == "delete" )
{
    $type = new eZPriority();
    $type->get( $PriorityID );
    $type->delete();

    Header( "Location: /todo/prioritytypelist/" );
}

$t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZTodoMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "prioritytypeedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "prioritytypeedit" => "prioritytypeedit.tpl"
    ) );


$ini = new INIFIle( "./eztodo/intl/" . $Language . "/prioritytypeedit.php.ini" );
$headline = $ini->read_var( "strings", "head_line_insert" );
$submittext = $ini->read_var( "strings", "submit_text_insert" );
$t->set_var( "action_value", "insert" );

    // Edit a priority.
if ( $Action == "edit" )
{
    $type = new eZPriority();
    $type->get( $PriorityID );

    $ini = new INIFIle( "./eztodo/intl/" . $Language . "/prioritytypeedit.php.ini" );
    $headline = $ini->read_var( "strings", "head_line_edit" );
    $submittext = $ini->read_var( "strings", "submit_text_edit" );

    $PriorityName = $type->title();

    {
        $type_array = $type->get( $PriorityID );

        for ( $i=0; $i<count( $type_array); $i++ )
        {
            print( $type_array[$i][ "Title" ] );
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

//  }
//
?>
