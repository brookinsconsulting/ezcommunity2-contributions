<?php
//
// Created on: <08-May-2002 09:50:37 jhe>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( "classes/ezdb.php" );
include_once( "classes/ezdatatime.php" );
include_once( "ezcontact/classes/ezcompany.php" );
include_once( "ezmail/classes/ezmail.php" );

$db =& eZDB::globalDatabase();
$timeStamp = eZDataTime::timeStamp( true );

$db->array_query( $companies, "SELECT eZContact_Company. ID FROM
                               eZContact_Company, eZContact_ProjectType, eZContact_CompanyProjectDict WHERE
                               eZContact_Company.ID = eZContact_CompanyProjectDict.CompanyID AND
                               eZContact_CompanyProjectDict.ProjectID = eZContact_ProjectType.ID AND
                               eZContact_Company.SentWarning = '0' AND
                               eZContact_Company.Approved = '1' AND
                               eZContact_Compant.WarningDate > '0' AND
                               eZContact_Company.WarningDate < '$timeStamp'" );

foreach ( $companies as $companyID )
{
    $company = new eZCompany( $companyID[$db->fieldName( "ID" )] );
    $company->setSentWarning( true );
    $company->store();

    $mailTemplate = new eZTemplate( "ezcontact/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ),
                                    "ezcontact/admin/intl", $ini->read_var( "eZContactMain", "Language" ), "mailtemplate.php" );
    
    $mailTemplate->set_file( "mailtemplate", "mailtemplate.tpl" );
    $mailTemplate->set_block( "mailtemplate", "subject_tpl", "subject" );
    $mailTemplate->setAllStrings();

    $mailTemplate->set_var( "subject", "" );

    $subject = $mailTemplate->parse( "dummy", "subject_tpl" );
    $mailTemplate->set_var( "company", $company->name() );
    $bodyText = $mailTemplate->parse( "dummy", "mailtemplate" );
    
    // send a notice mail
    $noticeMail = new eZMail();
    $noticeMail->setFrom( $PublishNoticeSender );
    $noticeMail->setSubject( $subject );
    $noticeMail->setBodyText( $bodyText );

    foreach ( $senders as $sender )
    {
        $noticeMail->setTo( $sender );
        $noticeMail->send();
    }
}
                               
$db->array_query( $companies, "SELECT eZContact_Company. ID FROM
                               eZContact_Company, eZContact_ProjectType, eZContact_CompanyProjectDict WHERE
                               eZContact_Company.ID = eZContact_CompanyProjectDict.CompanyID AND
                               eZContact_CompanyProjectDict.ProjectID = eZContact_ProjectType.ID AND
                               eZContact_Company.Approved = '1' AND
                               eZContact_Compant.ExpiryDate > '0' AND
                               eZContact_Company.ExpiryDate < '$timeStamp'" );

foreach ( $companies as $companyID )
{
    $company = new eZCompany( $companyID[$db->fieldName( "ID" )] );
    $company->setIsApproved( false );
    $company->store();
}

?>
