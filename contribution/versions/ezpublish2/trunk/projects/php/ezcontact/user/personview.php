<?
/*
    View a person
 */

include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );

include_once( "classes/eztemplate.php" );
include_once( "ezcontact/classes/ezperson.php" );
include_once( "classes/ezdate.php" );
    

$error = false;

$t = new eZTemplate( "ezcontact/user/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ),
                     "ezcontact/user/intl", $Language, "personedit.php" );
$intl = new INIFile( "ezcontact/user/intl/$Language/personedit.php.ini", false );
$t->setAllStrings();

$t->set_file( array(                    
    "person_edit" => "personview.tpl"
    ) );
$t->set_block( "person_edit", "address_item_tpl", "address_item" );
$t->set_block( "address_item_tpl", "address_line_tpl", "address_line" );
$t->set_block( "person_edit", "no_address_item_tpl", "no_address_item" );

$t->set_block( "person_edit", "phone_item_tpl", "phone_item" );
$t->set_block( "phone_item_tpl", "phone_line_tpl", "phone_line" );
$t->set_block( "person_edit", "no_phone_item_tpl", "no_phone_item" );

$t->set_block( "person_edit", "online_item_tpl", "online_item" );
$t->set_block( "online_item_tpl", "online_line_tpl", "online_line" );
$t->set_block( "person_edit", "no_online_item_tpl", "no_online_item" );

$t->set_var( "firstname", "" );
$t->set_var( "lastname", "" );
$t->set_var( "personno", "" );
$t->set_var( "birthday", "" );
$t->set_var( "birthmonth", "" );
$t->set_var( "birthyear", "" );
$t->set_var( "comment", "" );

$t->set_var( "user_name", "" );
$t->set_var( "old_password", "" );

$t->set_var( "street1", "" );
$t->set_var( "street2", "" );
$t->set_var( "zip", "" );
$t->set_var( "place", "" );

$t->set_var( "home_phone", "" );
$t->set_var( "work_phone", "" );

$t->set_var( "web", "" );
$t->set_var( "email", "" );

$person = new eZPerson( $PersonID, true );

if( is_object( $user ) )
{
    $UserID = $user->id();
    
}
else
{
    header( "Location: /contact/person/new/" );
    exit();
}

if( $UserID > 0 )
{
    $user = $person->user();
}
else
{
    header( "Location: /contact/person/new/" );
    exit();
}

if( is_object( $user[0] ) )
{
    if( $user[0]->id() != $UserID || eZPermission::checkPermission( $user, "eZCV", "CVView" ) )
    {

        // We will need to check if people can view, if they have special
        // info.
        $person = $person->getByUserID( $UserID );
        $PersonID = $person->id();
        header( "Location: /contact/person/view/$PersonID" );
        exit();
    }
}
else
{
    header( "Location: /contact/person/list" );
}

/*
    The user wants to view an existing person.
    
    We present a page with the info.
 */
if ( $Action == "view" )
{
    $Action_value = "view";
    
    $t->set_var( "firstname", $person->firstName() );
    $t->set_var( "lastname", $person->lastName() );
    $t->set_var( "personno", $person->personNo() );
    
    $BirthDate = $person->birthDate();
    
    $t->set_var( "birthdate", $BirthDate );
    
    $date = new eZDate();
    $date->setMySQLDate( $BirthDate );
    
    $t->set_var( "birthyear", $date->year() );
    $t->set_var( "birthmonth", $date->month() );
    $t->set_var( "birthday", $date->day() );
    
    $t->set_var( "comment", $person->comment() );

    // Telephone list
    $phoneList = $person->phones( $person->id() );

    $count = count( $phoneList );
    if( $count != 0 )
    {
        for( $i=0; $i < $count; $i++ )
        {
            $t->set_var( "phone_id", $phoneList[$i]->id() );
            $t->set_var( "phone", $phoneList[$i]->number() );

            $phoneType = $phoneList[$i]->phoneType();

            $t->set_var( "phone_type_id", $phoneType->id() );
            $t->set_var( "phone_type_name", $phoneType->name() );

            $t->parse( "phone_line", "phone_line_tpl", true );
        }
        $t->parse( "phone_item", "phone_item_tpl" );
        $t->set_var( "no_phone_item", "" );
    }
    else
    {
        $t->set_var( "phone_item", "" );
        $t->parse( "no_phone_item", "no_phone_item_tpl" );
    }


    // Address list
    $addressList = $person->addresses( $person->id() );
    $count = count( $addressList );
    
    if( $count != 0 )
    {
        foreach( $addressList as $addressItem )
        {
            $t->set_var( "address_id", $addressItem->id() );
            $t->set_var( "street1", $addressItem->street1() );
            $t->set_var( "street2", $addressItem->street2() );
            $t->set_var( "zip", $addressItem->zip() );
            $t->set_var( "place", $addressItem->place() );
            
            $addressType = $addressItem->addressType();

            $t->set_var( "address_type_id", $addressType->id() );
            $t->set_var( "address_type_name", $addressType->name() );
            
            $t->set_var( "script_name", "personedit.php" );
            $t->parse( "address_line", "address_line_tpl", true );

        }
        $t->parse( "address_item", "address_item_tpl" );
        $t->set_var( "no_address_item", "" );
    }
    else
    {
        $t->set_var( "address_item", "" );
        $t->parse( "no_address_item", "no_address_item_tpl" );
    }
    
    // Online list
    $OnlineList = $person->onlines( $person->id() );
    $count = count( $OnlineList );
    if ( $count != 0)
    {
        for( $i=0; $i<count ( $OnlineList ); $i++ )
        {
            $t->set_var( "online_id", $OnlineList[$i]->id() );
            $t->set_var( "online", $OnlineList[$i]->URL() );
            $t->set_var( "online_url_type", $OnlineList[$i]->URLType() );
            
            $onlineType = $OnlineList[$i]->onlineType();

            $t->set_var( "online_type_id", $onlineType->id() );
            $t->set_var( "online_type_name", $onlineType->name() );
            $t->set_var( "online_url_type", $OnlineList[$i]->urlType() );
            
            $t->parse( "online_line", "online_line_tpl", true );
        }
        $t->parse( "online_item", "online_item_tpl" );
        $t->set_var( "no_online_item", "" );            
    }
    else
    {
        $t->set_var( "online_item", "" );
        $t->parse( "no_online_item", "no_online_item_tpl" );
    }
    
    $t->set_var( "person_id", $PersonID );
}

$t->set_var( "action_value", $Action_value );

$t->pparse( "output", "person_edit"  );

?>
