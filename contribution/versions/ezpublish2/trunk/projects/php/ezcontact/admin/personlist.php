<?
include_once( "classes/INIFile.php" );
$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );

include_once( "classes/eztemplate.php" );

$t = new eZTemplate( "ezcontact/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ),  "ezcontact/admin/intl", $Language, "personedit.php" );
$t->setAllStrings();

include_once( "ezcontact/classes/ezperson.php" );

$t->set_file( array(
    "person_page" => "personlist.tpl"
    ) );    
$t->set_block( "person_page", "person_item_tpl", "person_item" );
$t->set_var( "person_item", "" );
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
        $t->set_var( "bg_color", "bglight" );
    }
    else
    {
        $t->set_var( "bg_color", "bgdark" );
    }

    $t->set_var( "person_id", $persons[$i]->id() );
    $t->set_var( "person_firstname", $persons[$i]->firstName() );
    $t->set_var( "person_lastname", $persons[$i]->lastName() );
    $t->parse( "person_item", "person_item_tpl", true );
    
}

$t->pparse( "output", "person_page" );

?>
