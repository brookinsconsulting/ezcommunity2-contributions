<?php
//
// $Id: mailview.php,v 1.8.2.1.4.1 2002/04/22 08:29:43 ce Exp $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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

include_once( "ezbulkmail/classes/ezbulkmail.php" );
include_once( "ezbulkmail/classes/ezbulkmailtemplate.php" );

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/ezhttptool.php" );

$delayID = 2;
print( "<pre>" );

if ( isset( $Edit ) )
{
    eZHTTPTool::header( "Location: /bulkmail/mailedit/$MailID" );
    exit();
}

if ( isset( $Send ) )
{
    $mail = new eZBulkMail( $MailID );
    $categoryArray = $mail->categories();
    if ( count( $categoryArray ) > 0 )
    {
//        $mail->send();

        foreach ( $categoryArray as $category )
        {
            $subscribers = $category->subscribedUsers( $category->id() );
            foreach ( $subscribers as $subscripter )
            {
                /*
                $settings = $category->settings( $subscripter );
                if ( ( get_class( $setting ) == "ezbulkmailcategorysettings" ) && ( $settings->delay() != 0 ) )
                {
                */
                $user = $subscripter->user();
                $mail->addToDelayList( $user->id(), $category->id(), $delayID );
                    /*
                }
                    */
            }
        }
        exit();

        $catID = $categoryArray[0]->id();
        eZHTTPTool::header( "Location: /bulkmail/categorylist/$catID" );
        exit();
    }
    else
    {
        echo "An error occured during sending....";
    }
}

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZBulkMailMain", "Language" );

$t = new eZTemplate( "ezbulkmail/admin/" . $ini->read_var( "eZBulkMailMain", "AdminTemplateDir" ),
                     "ezbulkmail/admin/intl/", $Language, "mailview.php" );
$t->setAllStrings();

$t->set_file( "mail_view_page_tpl", "mailview.tpl" );


$t->set_var( "site_style", $SiteStyle );
$t->set_block( "mail_view_page_tpl", "send_button_tpl", "send_button" );
$t->set_block( "mail_view_page_tpl", "edit_button_tpl", "edit_button" );
$t->set_var( "send_button", "" );
$t->set_var( "edit_button", "" );

/** Check if we want the buttons enabled **/
if ( $SendButton == true )
    $t->parse( "send_button", "send_button_tpl", false );
if ( $EditButton == true )
    $t->parse( "edit_button", "edit_button_tpl", false );

$mail = new eZBulkMail( $MailID );
if ( is_object( $mail ) )
{
    $fromString = $mail->fromName() . " &lt;" . $mail->sender() ."&gt;";
    $t->set_var( "current_mail_id", $MailID );
    $t->set_var( "from", $fromString );
    $t->set_var( "subject", $mail->subject() );

    /** check if this mail has a template associated with it **/
    $body = $mail->body();
    $template = $mail->template();
    if ( is_object( $template ) )
        $body = $template->header() . $body . $template->footer();
    $t->set_var( "mail_body", nl2br( $body ) );

    $category = $mail->categories();
    if ( count( $category ) > 0 )
    {
        $t->set_var( "category", $category[0]->name() );
    }
}
else
{
    eZHTTPTool::header( "Location: /bulkmail/drafts/" );
    exit();
}


$t->pparse( "output", "mail_view_page_tpl" );

?>
