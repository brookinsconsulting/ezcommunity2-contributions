<?
include_once( "classes/INIFile.php" );
$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );

include_once( "classes/eztemplate.php" );

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZContactMain", "TemplateDir" ),  $DOC_ROOT . "/admin/intl", $Language, "personlist.php" );
$t->setAllStrings();

include_once( "ezcontact/classes/ezperson.php" );

$t->set_file( array(
    "person_page" => "personlist.tpl"
    ) );    

$person = new eZPerson();
$persons = $person->getAll();

$count = count( $persons );

if( $i < 0 )
{
    $t->set_block( );
}

for( $i = 0; $i < $count; $i++ )
{
    if( ( $i % 2 ) == 0 )
    {
        $t->set_var( "bg_color", "#eeeeee" );
    }
    else
    {
        $t->set_var( "bg_color", "#dddddd" );
    }

    $t->set_var( "person_id", $persons[$i]->id() );
    $t->set_var( "person_firstname", $persons[$i]->firstName() );
    $t->set_var( "person_lastname", $persons[$i]->lastName() );
    $t->parse( "person_list", "person_item", true );
    
}

$t->pparse( "output", "person_page" );

?>
