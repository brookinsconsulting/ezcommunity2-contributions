<?
/*!
    $Id: main.php,v 1.14 2000/07/31 21:40:49 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:49:01 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "ezforum/dbsettings.php" );
include_once( "ezphputils.php" );
include_once( "template.inc" );

include_once( "$DOCROOT/classes/ezdb.php" );
include_once( "$DOCROOT/classes/ezforumcategory.php" );
include_once( "$DOCROOT/classes/ezuser.php" );
include_once( "$DOCROOT/classes/ezsession.php" );
include_once( "$DOCROOT/classes/ezforummessage.php" );

$session = new eZSession();

$t = new Template( "$DOCROOT/templates" );

$t->set_file( Array("main" => "main.tpl",
                    "elements" => "main-elements.tpl",
                    "login" => "main-login.tpl",
                    "logout" => "main-logout.tpl",
                    "search" => "main-search.tpl",
                    "results" => "main-search-results.tpl",
                    "search-elements" =>"main-search-results-elements.tpl",
                    "navigation" => "navigation.tpl"
                    ) );

$t->set_var( "docroot", $DOCROOT);
$categories = eZforumCategory::getAllCategories();

if ( $session->get( $AuthenticatedSession ) == 0 )
{
   $t->set_var( "user", eZUser::resolveUser( $session->UserID() ) );
}
else
{
   $t->set_var( "user", "Anonym" );
}
$t->parse( "navigation-bar", "navigation", true);

// category list
for ($i = 0; $i < count($categories); $i++)
{
    arrayTemplate( $t, $categories[$i], Array( Array( "Id", "id"),
                                           Array( "Name", "name"),
                                           Array( "Description", "description")
                                           )
                   );
    $t->set_var( "color", switchColor( $i, "#eeeeee", "#bbbbbb" ) );
                                       
    $t->parse( "categories", "elements", true );
}

//search field
if ( $search )
{
    $criteria = addslashes( $criteria );
    $headers = eZforumMessage::search( $criteria );

    if ( count( $headers ) == 0 )
        $t->set_var( "fields", "<b>Ingen treff</b>");
    
    for ( $i = 0; $i < count ( $headers ); $i++)
    {
/*        $t->set_var( "message_id", $headers[$i]["Id"] );
        $t->set_var( "topic", $headers[$i]["Topic"] );
        $t->set_var( "author", $usr->resolveUser( $headers[$i]["UserId"] ) );
        $t->set_var( "time", eZforumMessage::formatTime( $headers[$i]["PostingTime"] ) );
*/
        arrayTemplate( $t, $headers[$i], Array( Array("Id", "message_id"),
                                                Array("Topic", "topic"),
                                                Array("UserId", "author"),
                                                Array("PostingTime", "time" )
                                                )
                       );
        $t->set_var( "forum", "&nbsp;" );

        $t->set_var( "color", switchColor( $i, "#eeeeee", "#bbbbbb" ) );
        
        $t->parse( "fields", "search-elements", true );
    }
    $t->parse( "searchfield", "results", true );
}
else
{
    $t->parse( "searchfield", "search" );
}

// login / logout

if ( $session->validate( $AuthenticatedSession ) == 0   )
{
    $t->set_var( "login-msg", "" );
    $t->set_var( "loginlogout", "" );
}
else
{
    if ( $login == "failed" )
    {
        $t->set_var( "login-msg", "Påloggingen var mislykket, prøv igjen." );
    }
    else
    {
        $t->set_var( "login-msg", "" );
    }

    $t->parse( "loginlogout", "login", true );
}

//parse template file
$t->pparse( "output", "main" );
?>
