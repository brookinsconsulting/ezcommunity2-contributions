<?
include_once( "classes/INIFile.php" );
$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );
$Max = $ini->read_var( "eZContactMain", "MaxPersonList" );

if ( !is_numeric( $Max ) )
{
    $Max = 10;
}

include_once( "classes/eztemplate.php" );

$t = new eZTemplate( "ezcontact/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ),  "ezcontact/admin/intl", $Language, "personedit.php" );
$t->setAllStrings();

include_once( "ezcontact/classes/ezperson.php" );

$t->set_file( array(
    "person_page" => "personlist.tpl"
    ) );    
$t->set_block( "person_page", "no_persons_tpl", "no_persons" );

$t->set_block( "person_page", "person_table_tpl", "person_table" );
$t->set_block( "person_table_tpl", "person_item_tpl", "person_item" );

$t->set_block( "person_table_tpl", "person_list_tpl", "person_list" );
$t->set_block( "person_list_tpl", "person_list_previous_tpl", "person_list_previous" );
$t->set_block( "person_list_tpl", "person_list_item_tpl", "person_list_item" );
$t->set_block( "person_list_tpl", "person_list_next_tpl", "person_list_next" );
$t->set_block( "person_list_tpl", "person_list_previous_inactive_tpl", "person_list_previous_inactive" );
$t->set_block( "person_list_tpl", "person_list_next_inactive_tpl", "person_list_next_inactive" );

$t->set_var( "person_item", "" );

$person = new eZPerson();

if ( !isset( $Index ) )
{
    $Index = 0;
}
else if ( !is_numeric( $Index ) )
{
    $Index = 0;
}

$t->set_var( "action", $Action );

if ( !isset( $SearchText ) )
{
    $total_persons = $person->getAllCount();
    $persons = $person->getAll( "", $Index, $Max );
    $t->set_var( "search_form_text", "" );
    $t->set_var( "search_text", "" );
}
else
{
    $search_encoded = $SearchText;
    $search_encoded = preg_replace( "/[+]/", "%2B", $search_encoded );
    $search_encoded = preg_replace( "/[ ]/", "+", $search_encoded );
    $t->set_var( "search_form_text", $SearchText );
    $t->set_var( "search_text", $search_encoded );
    $total_persons = $person->getAllCount( $SearchText );
    $persons = $person->getAll( $SearchText, $Index, $Max );
}

$count = count( $persons );

$t->set_var( "person_table", "" );
$t->set_var( "no_persons", "" );

if( $count == 0 )
{
    $t->parse( "no_persons", "no_persons_tpl" );
}
else
{
    for( $i = 0; $i < $count && $i < $Max; $i++ )
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

    $t->parse( "person_table", "person_table_tpl" );
}

if ( $total_persons > $Max || $Index > 0 )
{
    $t->set_var( "person_list_previous", "" );
    $t->set_var( "person_list_item", "" );
    $t->set_var( "person_list_next", "" );
    $t->set_var( "person_list_previous_inactive", "" );
    $t->set_var( "person_list_next_inactive", "" );

    if ( $Index > 0 )
    {
        $t->set_var( "item_previous_index", max( $Index - $Max, 0 ) );
        $t->parse( "person_list_previous", "person_list_previous_tpl" );
    }
    else
    {
        $t->parse( "person_list_previous_inactive", "person_list_previous_inactive_tpl" );
    }
    if ( $Index + $Max < $total_persons )
    {
        $t->set_var( "item_next_index", $Index + $Max );
        $t->parse( "person_list_next", "person_list_next_tpl" );
    }
    else
    {
        $t->parse( "person_list_next_inactive", "person_list_next_inactive_tpl" );
    }

    $total = $total_persons;
    $i = 0;
    while ( $total > 0 )
    {
        $t->set_var( "item_index", $i*$Max );
        $t->set_var( "item_name", $i );
        $t->parse( "person_list_item", "person_list_item_tpl", true );

        $total = $total - $Max;
        $i++;
    }

    $t->parse( "person_list", "person_list_tpl" );
}
else
{
    $t->set_var( "person_list", "" );
}

$t->pparse( "output", "person_page" );

?>
