<?
/*!
    $Id: search.php,v 1.8 2000/09/01 13:41:34 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <26-Jul-2000 17:22:47 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "classes/class.INIFile.php" );

$ini = new INIFile( "site.ini" ); // get language settings
$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );

include_once( "common/ezphputils.php" );
include_once( $DOC_ROOT . "/classes/ezforummessage.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezuser.php" );
include_once( "classes/ezsession.php" );

$ini = new INIFile( "site.ini" ); // get language settings
$Language = $ini->read_var( "eZForumMain", "Language" );


//preliminary setup
$usr = new eZUser;
$session = new eZSession;

$t = new eZTemplate( "$DOC_ROOT/templates", "$DOC_ROOT/intl", $Language, "main.php" );
$t->setAllStrings();

$t->set_file( Array("main" => "search.tpl",
                    "search" => "main-search.tpl",
                    "search-elements" =>"main-search-results-elements.tpl",
                    "results" => "main-search-results.tpl",                    
                    "navigation" => "navigation.tpl",
                    "login" => "login.tpl",                    
                    "logout" => "logout.tpl"                    
                    ) );

$t->set_var( "docroot", $DOC_ROOT);
$t->set_var( "forum_path", "");

if ( $session->get( $AuthenticatedSession ) == 0 )
{
    $user = new eZUser();
    $t->set_var( "user", $user->resolveUser( $session->UserID() ) );
    $t->parse( "logout-message", "logout", true);
}
else
{
   $t->set_var( "user", "Anonym" );
   $t->set_var( "logout-message", "" );
}
$t->parse( "navigation-bar", "navigation", true);

//search field
if ( $search )
{
    $criteria = addslashes( $criteria );
    $message = new eZForumMessage();
    $headers = $message->search( $criteria );

    if ( count( $headers ) == 0 )
        $t->set_var( "fields", "<b>Ingen treff</b>");

    $user = new eZUser();    
    for ( $i = 0; $i < count ( $headers ); $i++)
    {
        $message->get( $headers[$i][ "Id" ] );


     

        if ( $message->userID() == 0 )
            $userName = "Anonym";
        else
        {
            $user->get( $message->userID() );
            $userName = $user->firstName() . " " . $user->lastName();
        }
                
        $t->set_var( "message_id", $message->id() );
        $t->set_var( "topic", $message->topic() );
        $t->set_var( "author", $userName );
        $t->set_var( "time", $message->postingTime() );
        
        $t->set_var( "forum", "&nbsp;" );

        $t->set_var( "color", switchColor( $i, "#f0f0f0", "#dcdcdc" ) );
        
        $t->parse( "fields", "search-elements", true );
    }
    $t->parse( "searchfield", "results", true );
}
else
{
    $t->parse( "searchfield", "search" );
}


//$t->parse( "searchfield", "search", true );

$t->pparse("output","main");
?>
