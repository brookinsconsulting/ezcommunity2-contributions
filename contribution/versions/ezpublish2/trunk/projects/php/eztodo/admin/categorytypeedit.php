<?
/*
  Edit a category type.
*/

include_once( "classes/INIFile.php" );
include_once( "classes/ezhttptool.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZTodoMain", "Language" );

include_once( "classes/eztemplate.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );

include_once( "eztodo/classes/ezcategory.php" );


if ( $Action == "insert" )
{

    $type = new eZCategory();
    $type->setName( $Name );
    $type->store();

    eZHTTPTool::header( "Location: /todo/categorytypelist/" );
    exit();
}

// Update a category.
if ( $Action == "update" )
{
    $type = new eZCategory();
    $type->get( $CategoryID );
    $type->setName( $Name );
    $type->store();

    eZHTTPTool::header( "Location: /todo/categorytypelist/" );
    exit();
}

// Delete a category.
if ( $Action == "delete" )
{

    $type = new eZCategory();
    $type->get( $CategoryID );
    $type->delete();

    eZHTTPTool::header( "Location: /todo/categorytypelist/" );
    exit();
}

$t = new eZTemplate( "eztodo/admin/" . $ini->read_var( "eZTodoMain", "AdminTemplateDir" ),
                     "eztodo/admin/intl", $Language, "categorytypeedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "categorytypeedit" => "categorytypeedit.tpl"
    ) );

$ini = new INIFIle( "eztodo/intl/" . $Language . "/categorytypeedit.php.ini", false );
$headline = $ini->read_var( "strings", "head_line_insert" );
$submittext = $ini->read_var( "strings", "submit_text_insert" );
$t->set_var( "action_value", "insert" );

// Edit a category.
if ( $Action == "edit" )
{
    $type = new eZCategory();
    $type->get( $CategoryID );

    $ini = new INIFIle( "eztodo/intl/" . $Language . "/categorytypeedit.php.ini", false );
    $headline = $ini->read_var( "strings", "head_line_edit" );
    $submittext = $ini->read_var( "strings", "submit_text_edit" );

    $CategoryName = $type->name();

    {
        $type_array = $type->get( $CategoryID );

        for ( $i=0; $i<count( $type_array); $i++ )
        {
            print( $type_array[$i][ "Name" ] );
        }
    }

    $t->set_var( "category_type_id", $CategoryID );
    $t->set_var( "action_value", "update" );
}

$t->set_var( "category_type_name", $CategoryName );
$t->set_var( "head_line", $headline );
$t->set_var( "submit_text", $submittext );

$t->pparse( "output", "categorytypeedit" );
?>


    
