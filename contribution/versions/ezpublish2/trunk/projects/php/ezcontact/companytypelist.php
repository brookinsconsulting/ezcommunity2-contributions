<?
/*
  Viser firma typer.
*/

include_once( "class.INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezsession.php" );
include_once( "classes/ezusergroup.php" );
include_once( "ezphputils.php" );

include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezcompanytype.php" );

// Sjekker rettigheter
$session = new eZSession();
if( $session->get( $AuthenticatedSession ) == 0 )
{
    if ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_Read" ) == 1 )
    {

    // Sette template.
    $t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "companytypelist.php" );
    $t->setAllStrings();

    $t->set_file( array(
        "companytype_page" => "companytypelist.tpl",
        "companytype_item" => "companytypeitem.tpl"
        ) );    

    // Viser firma typer.
    $companytype = new eZCompanyType();
    $companytype_array = $companytype->getAll();

    for ( $i=0; $i<count( $companytype_array ); $i++ )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "bg_color", "#eeeeee" );
        }
        else
        {
            $t->set_var( "bg_color", "#dddddd" );
        }  

        $t->set_var( "companytype_id", $companytype_array[$i][ "ID" ] );
        $t->set_var( "companytype_name", $companytype_array[$i][ "Name" ] );
        $t->set_var( "description", $companytype_array[$i][ "Description" ] );
        $t->parse( "companytype_list", "companytype_item", true );
    }               

    $t->set_var( "document_root", $DOC_ROOT );
    $t->pparse( "output", "companytype_page" );
    }
    else
    {
        print( "\nDu har ikke rettigheter\n" );
    }
}
else
{
    Header( "Location: index.php?page=common/error.php" );
}
?>
