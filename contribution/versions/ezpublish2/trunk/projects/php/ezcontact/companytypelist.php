<?
include  "template.inc";
require "ezphputils.php";
require "ezcontact/dbsettings.php";
require $DOCUMENTROOT . "classes/ezsession.php";
require $DOCUMENTROOT . "classes/ezperson.php";
require $DOCUMENTROOT . "classes/ezuser.php";
require $DOCUMENTROOT . "classes/ezcompanytype.php";

// sjekke session
{
    include( $DOCUMENTROOT . "checksession.php" );
}



$t = new Template( "." );
$t->set_file( array(
    "companytype_page" => $DOCUMENTROOT . "templates/companytypelist.tpl",
    "companytype_item" => $DOCUMENTROOT . "templates/companytypeitem.tpl"
    ) );    

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

$t->set_var( "document_root", $DOCUMENTROOT );
$t->pparse( "output", "companytype_page" );
?>
