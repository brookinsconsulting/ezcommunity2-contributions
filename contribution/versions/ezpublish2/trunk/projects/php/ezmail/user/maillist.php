<?
// 
// $Id: maillist.php,v 1.1 2001/03/19 20:09:35 fh Exp $
//
// Frederik Holljen <fh@ez.no>
// Created on: <19-Mar-2000 20:25:22 fh>
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
include_once( "classes/ezlocale.php" );

include_once( "ezmail/classes/ezmailaccount.php" );

/*
$user = eZUser::currentUser();
$account = new eZMailAccount(1);
$account->setUserID( $user->id() );
$account->setName( addslashes( "Larson's mail" ) );
$account->setLoginName( "larson" );
$account->setPassword( "AcRXYJJA" );
$account->setDeleteFromServer( 1 );
$account->setIsActive( 1 );
$account->setServerType( "pop" );
$account->store();
$account->setServer( "zap.ez.no" );
$account->store();
*/

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZArticleMain", "Language" ); // SET MAIL HERE!!!

$t = new eZTemplate( "ezmail/user/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                     "ezmail/user/intl/", $Language, "maillist.php" );
$t->setAllStrings();

$t->set_file( array(
    "mail_list_page_tpl" => "maillist.tpl"
    ) );



$t->pparse( "output", "mail_list_page_tpl" );
?>
