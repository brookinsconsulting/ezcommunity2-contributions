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

include_once( "../ezcontact/classes/ezphonetype.php" );

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

        Header( "Location: index.php?page=" . $DOC_ROOT . "categorytypelist.php" );
    }

    // Update a category.
    if ( $Action == "update" )
    {
        $type = new eZCategory();
        $type->get( $CategoryID );
        $type->setTitle( $Title );
        $type->update();

        Header( "Location: index.php?page=" . $DOC_ROOT . "categorytypelist.php" );
    }

    // Delete a category.
    if ( $Action == "delete" )
    {
        $type = new eZCategory();
        $type->get( $CategoryID );
        $type->delete();

        Header( "Location: index.php?page=" . $DOC_ROOT . "categorytypelist.php" );
    }

    $t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZTodoMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "categorytypeedit.php" );
    $t->setAllStrings();

    $t->set_file( array(
        "categorytypeedit" => "categorytypeedit.tpl"
        ) );

    $t->set_var( "action_value", "insert" );

    // Edit a category.
    if ( $Action == "edit" )
    {
        $type = new eZCategory();
        $type->get( $CategoryID );

        $t->set_var( "action_value", "update" );
    }

    $t->set_var( "document_root", $DOC_ROOT );

    $t->pparse( "output", "categorytypeedit" );
//  }
//
?>


    
