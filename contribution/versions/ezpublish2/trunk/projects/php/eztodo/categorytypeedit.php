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

include_once( "../eztodo/classes/ezcategory.php" );

// Chekc rights
//  $session = new eZSession();
//  if( $session->get( $AuthenticatedSession ) == 0 )
//  {
// Insert a category.
if ( $Action == "insert" )
{
    $type = new eZCategory();
    $type->setTitle( $Title );
    $type->store();

    Header( "Location: /todo/categorytypelist/" );
}

// Update a category.
if ( $Action == "update" )
{
    $type = new eZCategory();
    $type->get( $CategoryID );
    $type->setTitle( $Title );
    $type->update();

    Header( "Location: /todo/categorytypelist/" );
}

// Delete a category.
if ( $Action == "delete" )
{
    $type = new eZCategory();
    $type->get( $CategoryID );
    $type->delete();

    Header( "Location: /todo/categorytypelist/" );
}

$t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZTodoMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "categorytypeedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "categorytypeedit" => "categorytypeedit.tpl"
    ) );


$ini = new INIFIle( "./eztodo/intl/" . $Language . "/categorytypeedit.php.ini" );
$headline = $ini->read_var( "strings", "head_line_insert" );
$submittext = $ini->read_var( "strings", "submit_text_insert" );
$t->set_var( "action_value", "insert" );

    // Edit a category.
if ( $Action == "edit" )
{
    $type = new eZCategory();
    $type->get( $CategoryID );

    $ini = new INIFIle( "./eztodo/intl/" . $Language . "/categorytypeedit.php.ini" );
    $headline = $ini->read_var( "strings", "head_line_edit" );
    $submittext = $ini->read_var( "strings", "submit_text_edit" );

    $CategoryName = $type->title();

    {
        $type_array = $type->get( $CategoryID );

        for ( $i=0; $i<count( $type_array); $i++ )
        {
            print( $type_array[$i][ "Title" ] );
        }
    }

    $t->set_var( "category_type_id", $CategoryID );
    $t->set_var( "action_value", "update" );
}

$t->set_var( "category_type_name", $CategoryName );
$t->set_var( "head_line", $headline );
$t->set_var( "submit_text", $submittext );
$t->set_var( "document_root", $DOC_ROOT );

$t->pparse( "output", "categorytypeedit" );

//  }
//
?>


    
