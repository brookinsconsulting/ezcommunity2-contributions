<?
/*
  Viser liste over prioriteringer
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
include_once( "../eztodo/classes/ezpriority.php" );

// Sjekker rettigheter
$session = new eZSession();
if( $session->get( $AuthenticatedSession ) == 0 )
{
 
$t = new eZTemplate( "../" . $DOC_ROOT . "/" . $ini->read_var( "eZTodoMain", "TemplateDir" ), "../" . $DOC_ROOT . "/intl", $Language, "prioritytypelist.php" );
$t->setAllStrings();

    $t->set_file( array(
        "priority_type_page" =>  "prioritytypelist.tpl",
        "priority_type_item" =>  "prioritytypeitem.tpl"
        ) );

    $priority_type = new eZPriority();
    $priority_type_array = $priority_type->getAll();

    for ( $i=0; $i<count( $priority_type_array ); $i++ )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "bg_color", "#eeeeee" );
        }
        else
        {
            $t->set_var( "bg_color", "#dddddd" );
        }  

        
        $t->set_var( "priority_type_id", $priority_type_array[$i]->id() );
        $t->set_var( "priority_type_name", $priority_type_array[$i]->title() );

        $t->parse( "priority_type_list", "priority_type_item", true );
    } 

    $t->set_var( "document_root", $DOC_ROOT );
    $t->pparse( "output", "priority_type_page" );
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
