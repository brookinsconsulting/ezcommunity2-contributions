<?
/*
  Viser liste over kontakt typer.
*/
include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZTodoMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZTodoMain", "DocumentRoot" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezsession.php" );
include_once( "classes/ezusergroup.php" );
include_once( "classes/ezuser.php" );

include_once( "common/ezphputils.php" );

include_once( "../eztodo/classes/eztodo.php" );
include_once( "../eztodo/classes/ezcategory.php" );

// Sjekker rettigheter
$session = new eZSession();
if( $session->get( $AuthenticatedSession ) == 0 )
{
 
$t = new eZTemplate( "../" . $DOC_ROOT . "/" . $ini->read_var( "eZTodoMain", "TemplateDir" ), "../" . $DOC_ROOT . "/intl", $Language, "categorytypelist.php" );
$t->setAllStrings();

    $t->set_file( array(
        "category_type_page" =>  "categorytypelist.tpl",
        "category_type_item" =>  "categorytypeitem.tpl"
        ) );

    $category_type = new eZCategory();
    $category_type_array = $category_type->getAll();

    for ( $i=0; $i<count( $category_type_array ); $i++ )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "bg_color", "#eeeeee" );
        }
        else
        {
            $t->set_var( "bg_color", "#dddddd" );
        }  

        $t->set_var( "document_root", $DOC_ROOT );
        $t->set_var( "category_type_id", $category_type_array[$i]->id() );
        $t->set_var( "category_type_name", $category_type_array[$i]->title() );

        $t->parse( "category_type_list", "category_type_item", true );
    } 

    $t->pparse( "output", "category_type_page" );
    }

//      else
//      {
//          print( "\nDu har ikke rettigheter\n" );
//      }
//  }
//  else
//  {
//      Header( "Location: index.php?page=common/error.php" );
//  }

?>
