<?php
//
// $Id: rfpdeadlinereminders.php,v 1.84 2003/11/17 13:14:38 ghb Exp $
//
// Created on: <17-Nov-2003 16:31:53 ghb>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 2001-2003 eZ Systems.  All rights reserved.
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

//unset( $PaymentSuccess );

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
//include_once( "classes/ezcurrency.php" );

include_once( "classes/ezhttptool.php" );
include_once( "classes/ezcachefile.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezsession/classes/ezsession.php" );
include_once( "ezmail/classes/ezmail.php" );
include_once( "ezrfp/classes/ezrfp.php" );


$ini =& INIFile::globalINI();
$SiteURL =  $ini->read_var( "site", "SiteURL" );

$session =& eZSession::globalSession();

$rfpList =& eZRfp::rfps();

//$rfpID = 25;
//$rfpCategoryID = 20;

// foreach() {
// variable assignment
//$rfpID = $rfp->id();
//$rfpUser = $rfp->user();
//$rfpDeadlineResponceDate = $rfp->deadlineResponce();
// $currentDateTimeStamp = $rfpDeadlineResponceDate;
// $currentDateTimeStamp = new eZDateTime();
// $currentDateTimeStamp = xxxx?

// if ($rfpDeadlineResponceDate) {
// if ($rfpDeadlineResponceDate == $currentDateTimeStamp) {
// if ($rfpDeadlineResponceDate <== $currentDateTimeStamp) {
// print("boooooo");
// }


// &rfps( $sortMode="time", $fetchNonPublished=true,
  //                      $offset=0, $limit=50 )
  
  
  
    //
    // Send mail confirmation
    //      

    $mailTemplate = new eZTemplate( "ezrfp/user/" . $ini->read_var( "eZRfpMain", "TemplateDir" ),
                                    "ezrfp/user/intl", $Language, "/rfpdeadlinereminders.php" );
    
    $mailTemplateIni = new INIFile( "ezarticle/user/intl/" . $Language . "/rfpdeadlinereminders.php.ini", false );
    $mailTemplate->set_file( "order_sendt_tpl", "mailorder.tpl" );
    $mailTemplate->setAllStrings();

    $mailTemplate->set_block( "order_sendt_tpl", "credit_card_information_tpl", "credit_card_information" );

    $mailTemplate->set_block( "order_sendt_tpl", "customer_account_number_information_tpl", "customer_account_number_information" );
/*
    $mailTemplate->set_block( "order_sendt_tpl", "billing_address_tpl", "billing_address" );
    $mailTemplate->set_block( "order_sendt_tpl", "shipping_address_tpl", "shipping_address" );
    $mailTemplate->set_block( "order_sendt_tpl", "order_item_list_tpl", "order_item_list" );

    $mailTemplate->set_block( "order_sendt_tpl", "full_cart_tpl", "full_cart" );
    $mailTemplate->set_block( "full_cart_tpl", "cart_item_list_tpl", "cart_item_list" );

    $mailTemplate->set_block( "cart_item_list_tpl", "cart_item_tpl", "cart_item" );

    $mailTemplate->set_block( "cart_item_tpl", "cart_item_option_tpl", "cart_item_option" );

    $mailTemplate->set_block( "full_cart_tpl", "tax_specification_tpl", "tax_specification" );
    $mailTemplate->set_block( "tax_specification_tpl", "tax_item_tpl", "tax_item" );
*/
    
// mail variables

    $mailTemplate->set_var( "site_url", $SiteURL );
//    $mailBody = $mailTemplate->parse( "dummy", "full_cart_tpl" );

//	$mailTo = 'info@brookinsconsulting.com';
	$mailTo = $UserNameEmail;
//	$mailFrom = 'info@brookinsconsulting.com';
	$mailFrom = 'nospam@ladivaloca.org';
	$mailSubject = 'Request For Proposal Deadline Reminder Email';
//	$UserName = 'UserName!';
    	$mailBody = "\nDear $UserName,\n\nYour request for proposal is drawing near it's final deadline for responces in xx days.\n\nPlease review:\nhttp://ladivaloca.org/index.php/rfp/view/$rfpID/1/$rfpCategoryID \n\n";

//{rfp_id}/1/{category_id}

//    $mailBody .= "\r\n";

	// questions: who to mail
	// answere: look in db table where a date = now (test frist)
	// then out of that list foreach lookup user and see if the user
	// wants an email notification. for each user build an aray of eZUsers / RFPID
	// for each send email w/ link back to ssystem.

	/*			

    $mailTemplate->set_var( "site_url", $SiteURL );
    $mailBody = $mailTemplate->parse( "dummy", "full_cart_tpl" );
    $subjectINI = new INIFile( "ezarticle/user/intl/" . $Language . "/mailorder.php.ini", false );

    $mailSubjectUser = $subjectINI->read_var( "strings", "mail_subject_user" ) . " " . $ini->read_var( "site", "SiteURL" );
    $mailSubject = $subjectINI->read_var( "strings", "mail_subject_admin" ) . " " . $ini->read_var( "site", "SiteURL" );

    $paymentMethod = $instance->paymentName( $order->paymentMethod() );
    $mailTemplate->set_var( "payment_method", $paymentMethod );
    $mailTemplate->set_var( "comment", $order->comment() );

    $shippingType = $order->shippingType();
    if ( $shippingType )
    {
        $mailTemplate->set_var( "shipping_type", $shippingType->name() );
    }
    $mailTemplate->set_var( "order_vat_sum", $locale->format( $currency ) );

    $mailTemplate->set_var( "order_id", $order->id() );
    $mailTemplate->set_var( "credit_card_information", "" );

*/
			
// #############################################################################
// Begin Mail 				
// #############################################################################

    // Send E-mail    
    $mail = new eZMail();
    $mail->setFrom( $mailFrom );
    $mail->setTo( $mailTo );
    $mail->setSubject( $mailSubject );

    // set and send customer email
    $mail->setBody( $mailBody );
    $mail->send();
	
	$result = 'success';
// }
?>
