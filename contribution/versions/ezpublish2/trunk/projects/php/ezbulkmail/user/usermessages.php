<?
// 
// $Id: 
//
// Frederik Holljen <fh@ez.no>
// Created on: <02-Mar-2001 10:19:02 ce>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
$ini =& $GLOBALS["GlobalSiteIni"];
$Language = $ini->read_var( "eZBulkMailMain", "Language" );

$t = new eZTemplate( "ezbulkmail/user/" . $ini->read_var( "eZBulkMailMain", "TemplateDir" ),
                     "ezbulkmail/user/intl/", $Language, "usermessages.php" );

$t->setAllStrings();
$t->set_file( array( "message" => "usermessages.tpl" ) );

$t->set_block( "message", "mail_sent_tpl", "mail_sent" );
$t->set_block( "message", "address_confirmed_tpl", "address_confirmed" );

if( isset( $mailConfirm ) )
{
    $t->set_var( "address_confirmed", "" );
    $t->parse( "mail_sent", "mail_sent_tpl", false );
}
if( isset( $unsuccessfull ) )
{
    $t->set_var( "mail_sent", "" );
    $t->set_var( "generated_password", "" );
    $t->parse( "user_not_exists", "user_not_exists_tpl", false );
}
                 

$t->pparse( "output", "message" );
?>
