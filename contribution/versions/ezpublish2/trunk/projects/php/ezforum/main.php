<?
/*!
    $Id: main.php,v 1.25 2000/08/28 13:48:03 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:49:01 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

$ini = new INIFile( "site.ini" ); // get language settings
$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );

include_once( "ezphputils.php" );
include_once( "template.inc" );
include_once( "class.INIFile.php" );

include_once( $DOC_ROOT . "classes/ezdb.php" );
include_once( $DOC_ROOT . "classes/ezforumcategory.php" );
include_once( $DOC_ROOT . "classes/ezforummessage.php" );

include_once( "classes/ezuser.php" );
include_once( "classes/ezsession.php" );
include_once( "classes/eztemplate.php" );

echo "";
$session = new eZSession();

$ini = new INIFile( "site.ini" ); // get language settings
$Language = $ini->read_var( "eZForumMain", "Language" );

$t = new eZTemplate( "$DOC_ROOT/templates", "$DOC_ROOT/intl", $Language, "main.php" );
$t->setAllStrings();

$t->set_file( Array("main" => "main.tpl",
                    "elements" => "main-elements.tpl",
                    "login" => "main-login.tpl",
                    "search" => "main-search.tpl",
                    "results" => "main-search-results.tpl",
                    "search-elements" =>"main-search-results-elements.tpl",
                    "navigation" => "navigation.tpl",
                    "logout" => "logout.tpl"
                    ) );

$t->set_var( "docroot", $DOC_ROOT);
$category = new eZForumCategory();
$categories = $category->getAllCategories();

if ( $session->get( $AuthenticatedSession ) == 0 )
{
   $t->set_var( "user", eZUser::resolveUser( $session->UserID() ) );
   $t->parse( "logout-message", "logout", true);
}
else
{
   $t->set_var( "user", "Anonym" );
   $t->set_var( "logout-message", "" );
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
    $t->set_var( "color", switchColor( $i, "#f0f0f0", "#dcdcdc" ) );
                                       
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
        arrayTemplate( $t, $headers[$i], Array( Array("Id", "message_id"),
                                                Array("Topic", "topic"),
                                                Array("UserId", "author"),
                                                Array("PostingTime", "time" )
                                                )
                       );
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
