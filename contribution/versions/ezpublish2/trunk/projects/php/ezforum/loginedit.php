<?
/*!
    $Id: loginedit.php,v 1.1 2000/08/29 08:06:21 bf-cvs Exp $

    Author: Bård Farstad <bf@ez.no>
    
    Created on: <14-Jul-2000 12:49:01 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

$ini = new INIFile( "site.ini" ); // get language settings
$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );

include_once( "classes/ezsession.php" );


$t = new eZTemplate( "$DOC_ROOT/templates", "$DOC_ROOT/intl", $Language, "main.php" );
$t->setAllStrings();

$t->set_file( Array(
                    "login" => "main-login.tpl",
                    "logout" => "logout.tpl"
                    ) );

// login / logout

$t->set_var( "docroot", $DOC_ROOT);

$session = new eZSession();
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

//    $t->parse( "loginlogout", "login", true );
}

//parse template file
$t->pparse( "output", "login" );


?>
