<?php
// 
// $Id: authorview.php,v 1.10 2001/08/17 13:35:58 jhe Exp $
//
// Created on: <16-Feb-2001 15:36:13 amos>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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
include_once( "classes/ezdatetime.php" );
include_once( "classes/ezlist.php" );

include_once( "ezrfp/classes/ezrfp.php" );
include_once( "ezuser/classes/ezauthor.php" );
include_once( "ezuser/classes/ezuser.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZRfpMain", "Language" );
$Limit = $ini->read_var( "eZRfpMain", "AuthorRfpLimit" );
$locale = new eZLocale( $Language );

$t = new eZTemplate( "ezrfp/user/" . $ini->read_var( "eZRfpMain", "TemplateDir" ),
                     "ezrfp/user/intl/", $Language, "authorview.php" );

$t->setAllStrings();

$t->set_file( "author_view_tpl", "authorview.tpl" );

$t->set_block( "author_view_tpl", "rfp_item_tpl", "rfp_item" );
$t->set_block( "author_view_tpl", "rfp_item_header_one_tpl", "rfp_item_header_one" );
$t->set_block( "author_view_tpl", "rfp_item_header_two_tpl", "rfp_item_header_two" );
$t->set_block( "author_view_tpl", "type_list_tpl", "type_list" );

if ( !isset( $Offset ) or !is_numeric( $Offset ) )
    $Offset = 0;
if ( !isset( $Limit ) or !is_numeric( $Limit ) )
    $Limit = 10;
if ( !isset( $SortOrder ) )
    $SortOrder = "published";

//$rfp_count =& eZRfp::authorRfpCount( $AuthorID );
$rfp_count =& eZRfp::authorRfpCount( $AuthorID );

$t->set_var( "rfp_count", $rfp_count );
$t->set_var( "rfp_start", $Offset + 1 );
$t->set_var( "rfp_end", min( $Offset + $Limit, $rfp_count ) );

// $rfps =& eZRfp::authorRfpList( $AuthorID, $Offset, $Limit, $SortOrder );
$rfps =& eZRfp::authorRfpList( $AuthorID, $Offset, $Limit, $SortOrder );

$t->set_var( "author_id", $AuthorID );

// $author = new eZAuthor( $AuthorID );
$author = new eZUser( $AuthorID );


$t->set_var( "author_name", $author->name() );

$author_email = $author->email();

// str_replace("%body%", "black", "<body text='%body%'>");
$author_email_obfuscated = str_replace("@", "&nbsp;[at]&nbsp;", "$author_email"); 	
$author_email_obfuscated = str_replace(".", "&nbsp;[dot]&nbsp;", "$author_email_obfuscated");

$t->set_var( "author_mail", $author_email_obfuscated );

$t->set_var( "sort", $SortOrder );

$t->set_var( "rfp_item", "" );

$db =& eZDB::globalDatabase();
$i = 0;
$dateTime = new eZDateTime();

// v_array($rfps);
$rfpCount = sizeof($rfps);

if ( $rfpCount > 0 ) {
	    $t->parse( "rfp_item_header_one", "rfp_item_header_one_tpl", true );
	    $t->parse( "rfp_item_header_two", "rfp_item_header_two_tpl", true );

	foreach( $rfps as $rfp )
	{
	    $t->set_var( "td_class", ( $i % 2 ) == 0 ? "bglight" : "bgdark" );
	    $t->set_var( "rfp_id", $rfp[$db->fieldName("ID")] );
	    $t->set_var( "rfp_name", htmlspecialchars( $rfp[$db->fieldName("Name")] ) );
	    $t->set_var( "category_id", $rfp[$db->fieldName("CategoryID")] );
	    $t->set_var( "rfp_category", $rfp[$db->fieldName("CategoryName")] );

	//    $t->set_var( "author_name", $rfp[$db->fieldName("AuthorName")] );
	//    $rfpAuthorName = $rfp[$db->fieldName("FirstName")];
		$rfpAuthorName = $author->name();
	    $t->set_var( "author_name", $rfpAuthorName );
	    $dateTime->setTimeStamp( $rfp[$db->fieldName("Published")] );
	    $t->set_var( "rfp_published", $locale->format( $dateTime ) );
	    $t->parse( "rfp_item", "rfp_item_tpl", true );

	    $i++;
	}
	eZList::drawNavigator( $t, $rfp_count, $Limit, $Offset, "author_view_tpl" );
}else {
	$t->set_var( "rfp_item", '<br /><br /><h1>No Rfps</h1><br />' );
	$t->set_var( "rfp_count", '' );
	
        $t->set_var( "rfp_item_header_one", '' );
        $t->set_var( "rfp_item_header_two", '' );
	$t->set_var( "type_list", '' );
}

$t->pparse( "output", "author_view_tpl" );

?>
