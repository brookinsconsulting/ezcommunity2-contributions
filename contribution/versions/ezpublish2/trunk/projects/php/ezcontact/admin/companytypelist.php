<?
/*
  Viser firma typer.
*/

include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );

include_once( "classes/eztemplate.php" );
include_once( "ezcontact/classes/ezcompanytype.php" );

require( "ezuser/admin/admincheck.php" );

// Sette template.
$t = new eZTemplate( "ezcontact/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ),
                     "ezcontact/admin/intl/", $Language, "companytype.php" );
$t->setAllStrings();

$t->set_file( array(
    "companytype_page" => "companytypelist.tpl",
    ) );    

$t->set_block( "companytype_page", "type_list_tpl", "type_list" );
$t->set_block( "type_list_tpl", "type_item_tpl", "type_item" );
$t->set_block( "companytype_page", "no_type_item_tpl", "no_type_item" );

// Viser firma typer.
$type = new eZCompanyType();
$type_array = $type->getAll();

$type_count = count( $type_array );

if( $type_count != 0 )
{
    for( $i=0; $i < $type_count; $i++ )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "theme-type_class", "bglight" );
        }
        else
        {
            $t->set_var( "theme-type_class", "bgdark" );
        }  

        $t->set_var( "type_id", $type_array[$i]->id() );
        $t->set_var( "type_name", $type_array[$i]->name() );
        $t->set_var( "type_description", $type_array[$i]->description() );
        $t->parse( "type_item", "type_item_tpl", true );
    } 
    $t->set_var( "no_type_item", "" );             
    $t->parse( "type_list", "type_list_tpl" );
}
else
{
    $t->set_var( "type_list", "" );
    $t->parse( "no_type_item", "no_type_item_tpl" );
}
$t->pparse( "output", "companytype_page" );
?>
