<?php
//
// $Id: jobedit.php,v 1.1.2.1 2002/06/04 11:23:49 br Exp $
//
// Definition of ||| class
//
// <Bjørn Reiten> <br@ez.no>
// Created on: <13-May-2002 17:58:48 br>
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
include_once( "classes/ezhttptool.php" );
include_once( "ezaddress/classes/ezaddress.php" );
include_once( "ezaddress/classes/ezcountry.php" );
include_once( "ezaddress/classes/ezlanguage.php" );
include_once( "ezjob/classes/ezjob.php" );
include_once( "ezjob/classes/ezworktype.php" );
include_once( "ezjob/classes/ezworksector.php" );
include_once( "ezjob/classes/ezcareersector.php" );

if ( !is_Numeric( $JobID ) && $Action == "edit" )
{
    eZHTTPTool::header( "Location: /job/joblist" );
    exit();
}

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZJobMain", "Language" );
$furtherInformation = "http://";

// store the job.
if ( isSet( $OK ) )
{
    if ( trim( $JobTitle ) != "" && $CareerSectorID != -1 && $CountryJobBased != -1 )
    {
        if ( $Action == "edit" )
        {
            $job = new eZJob( $JobID );
        }
        else
        {
            $job = new eZJob();
        }

        $job->setTitle( $JobTitle );
        $job->setCareerSectorID( $CareerSectorID );
        $job->setDescription( $Description );
        $job->setURL( $FurtherInformation );
        $job->setSalary( $SalaryOrBenefits );
        $job->setInstructions( $HowToApply );
        $job->store();

        $job->addTypeOfWork( $TypeOfWork );
        $job->addRelatedSector( $RelatedSectors );
        $job->addLanguage( $Languages );
    }
}



$t = new eZTemplate( "ezjob/admin/" . $ini->read_var( "eZJobMain", "AdminTemplateDir" ),
                     "ezjob/admin/intl", $Language, "jobedit.php" );
$t->setAllStrings();

$t->set_file( "job_edit_tpl", "jobedit.tpl" );
$t->set_block( "job_edit_tpl", "career_sector_tpl", "career_sector" );
$t->set_block( "job_edit_tpl", "type_of_work_list_tpl", "type_of_work_list" );
$t->set_block( "type_of_work_list_tpl", "type_of_work_tpl", "type_of_work" );
$t->set_block( "job_edit_tpl", "related_sectors_list_tpl", "related_sectors_list" );
$t->set_block( "related_sectors_list_tpl", "related_sectors_tpl", "related_sectors" );
$t->set_block( "job_edit_tpl", "closing_day_tpl", "closing_day" );
$t->set_block( "job_edit_tpl", "languages_tpl", "languages" );
$t->set_block( "job_edit_tpl", "earliest_advert_day_tpl", "earliest_advert_day" );
$t->set_block( "job_edit_tpl", "country_job_based_tpl", "country_job_based" );

$t->set_var( "location_within_country", "" );
$t->set_var( "further_information", $furtherInformation );
$t->set_var( "job_title", "" );
$t->set_var( "job_status", "" );
$t->set_var( "description", "" );
$t->set_var( "salary_or_benefits", "" );
$t->set_var( "how_to_apply", "" );
$t->set_var( "closing_year", "" );
$t->set_var( "earliest_advert_year", "" );
$t->set_var( "closing_day_list", "" );
$t->set_var( "type_of_work_list", "" );
$t->set_var( "related_sectors_list", "" );
$t->set_var( "offset", "" );


// parse the countries.
$countryArray = eZCountry::getAllArray();
if ( count( $countryArray ) > 0 )
{
    foreach ( $countryArray as $countryItem )
    {
        // parse the job based items
        $t->set_var( "language_id", $countryItem["ID"] );
        $t->set_var( "language_name", $countryItem["Name"] );
        $t->parse( "country_job_based", "country_job_based_tpl", true );
    }
}

// parse the languages.
$languageArray =& eZLanguage::getAllArray();
if ( count( $languageArray ) > 0 )
{
    foreach ( $languageArray as $language )
    {
        $t->set_var( "language_id", $language["ID"] );
        $t->set_var( "language_name", $language["Name"] );
        $t->parse( "languages", "languages_tpl", true );
    }
}


// parse the career sectors.
$careerSectorArray =& eZCareerSector::getAll( true );
if ( count( $careerSectorArray ) > 0 )
{
    foreach ( $careerSectorArray as $careerSector )
    {
        $t->set_var( "career_sector_id", $careerSector->id() );
        $t->set_var( "career_sector_name", $careerSector->name() );
        $t->parse( "career_sector", "career_sector_tpl", true );
    }
}


// parse the closing and advert days
for ( $i=1; $i<=31; $i++ )
{
    $t->set_var( "closing_day_value", $i );
    $t->parse( "closing_day", "closing_day_tpl", true );
    
    $t->set_var( "advert_day_value", $i );
    $t->parse( "earliest_advert_day", "earliest_advert_day_tpl", true );
}


// parse the type of work.
$workTypes = eZWorkType::getAll( true );
$t->set_var( "type_of_work_id", "-1" );
$t->set_var( "type_of_work_name", "" );
if ( count ( $workTypes ) > 0 )
{
    foreach ( $workTypes as $workType )
    {
        $t->set_var( "type_of_work_id", $workType->id() );
        $t->set_var( "type_of_work_name", $workType->name() );
        $t->parse( "type_of_work", "type_of_work_tpl", true );
    }
    $t->parse( "type_of_work_list", "type_of_work_list_tpl" );
}

// parse the sectors the work is related to.
$relatedSectors = eZWorkSector::getAll();
if ( count( $relatedSectors ) > 0 )
{
    foreach ( $relatedSectors as $relatedSector )
    {
        $t->set_var( "related_sectors_id", $relatedSector->id() );
        $t->set_var( "related_sectors_name", $relatedSector->name()  );
        $t->parse( "related_sectors", "related_sectors_tpl", true );
    }
    $t->parse( "related_sectors_list", "related_sectors_list_tpl" );
}

if ( $Action == "new" )
{
    $t->set_var( "action_value", $Action );
}
else if ( $Action == "edit" )
{
    $t->set_var( "action_value", "editjob" );
}
else
{
    $t->set_var( "action_value", "" );
}

$t->pparse( "output", "job_edit_tpl" );

?>
