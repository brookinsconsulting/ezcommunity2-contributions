<?
/*!
    $Id: main.php,v 1.29 2000/09/01 13:29:00 ce-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:49:01 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "classes/class.INIFile.php" );

$ini = new INIFile( "site.ini" ); // get language settings
$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );

include_once( "common/ezphputils.php" );
include_once( "classes/template.inc" );

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
                    "login" => "login.tpl",                    
                    "logout" => "logout.tpl"
                    ) );

$t->set_var( "docroot", $DOC_ROOT);
$category = new eZForumCategory();
$categories = $category->getAllCategories();

$t->set_var( "forum_path", "" );

if ( $session->get( $AuthenticatedSession ) == 0 )
{
    $user = new eZUser();    
    $t->set_var( "user", $user->resolveUser( $session->UserID() ) );
    $t->parse( "logout-message", "logout", true);
}
else
{
    $t->set_var( "user", "Anonym" );
    $t->parse( "logout-message", "login", true);   
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


//parse template file
$t->pparse( "output", "main" );
?>
