<?php
// 
// $Id: rfpedit.php,v 1.116.2.10 2002/05/14 06:52:21 ghb Exp $
//
// Created on: <18-Oct-2003 15:04:39 ghb>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 2003-2006 Brookins Consulting.  All rights reserved.
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
include_once( "classes/ezcachefile.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );

include_once( "ezuser/classes/ezauthor.php" );

include_once("ezcontact/classes/ezcompany.php");
include_once("ezcontact/classes/ezperson.php");

include_once( "classes/ezhttptool.php" );

include_once( "ezrfp/classes/ezrfpcategory.php" );
include_once( "ezrfp/classes/ezrfp.php" );
include_once( "ezrfp/classes/eztopic.php" );

include_once( "ezrfp/classes/ezrfpgenerator.php" );
include_once( "ezrfp/classes/ezrfprenderer.php" );

include_once( "ezrfp/classes/ezrfptool.php" );
include_once( "ezxml/classes/ezxml.php" );

include_once( "ezprocurement/classes/ezprocurementbid.php" );

// include_once( "ezcontact/classes/ezcontact.php" );
// include_once( "ezcontact/classes/ezcontact.php" );

//include_once( "ezbulkmail/classes/ezbulkmail.php" );
//include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );
include_once( "ezrfp/classes/fnc_viewArray.php" );


$ini =& INIFile::globalINI();

$AnonymousUserGroup = $ini->read_var( "eZRfpMain", "AnonymousUserGroup" );

$CategoryID = $HTTP_POST_VARS["CategoryID"];




/*

print($ContentWriterID[0]);

print($ContentWriterID ."<hr>");

viewArray($ContentWriterID);

exit();

*/

// rfp published from preview
/* dylan note - this is to view all the crap from the db

$rfp = new eZRfp();
$rfp->get($RfpID);
$vardata = $rfp->ContentsWriters();
echo("testing the db content here<br>");
echo("testing to see if now unserialized string is ok");
v_array($rfp->oContentsWriters);
foreach ($vardata as $writer) {
echo("using get_object_vars to check out what each object contains<br>");
	$arr = get_object_vars($writer);
	v_array($arr);
}
*/


if ( isset( $PublishRfp ) )
{
    $rfp = new eZRfp();

    if ( $rfp->get( $RfpID ) )
    {
        $rfp->setIsPublished( true );
        $rfp->store();
    }
    
    $category =& $rfp->categoryDefinition( );
   
    if ( $category )
    {
        $categoryID = $category->id();
    }

    eZHTTPTool::header( "Location: /rfp/archive/$categoryID/" );
    exit();
}


if ( $Action == "Cancel" )
{
    $rfp = new eZRfp( $RfpID );
    $category = $rfp->categoryDefinition( );

    if ( $category )
    {
        $categoryID = $category->id();
    }

    eZHTTPTool::header( "Location: /rfp/archive/$categoryID/" );
    exit();
}


if ( $Action == "Delete" )
{

  /*
    $rfp = new eZRfp( $RfpID );
    $category = $rfp->categoryDefinition( );

    if ( $category )
    {
        $categoryID = $category->id();
    }
  */

    eZHTTPTool::header( "Location: /rfp/archive/$categoryID/" );
    exit();
}


 
// update an existing rfp in the database

if ( $Action == "Update" || ( $Action == "Insert" ) )
{
    $sendNotification = false;
    $curDate = new eZDateTime();
    $rfp = new eZRfp( );
    if (isset($RfpID))
    {
      $bidRfp = new eZRfp( $RfpID );
      $bids = $bidRfp->bids();

        if (!empty($bids))
        {
         foreach ($bids as $bid)
         {
	     $bidField = 'BidInfo' . $bid->id();
	     $bidInfo = explode("|", $$bidField);
         if ($bidInfo[5] == 1)
         {
             if ($bid->winner() != 1)
             {
              $bidRfp->setAwardDate(&$curDate);
              $bidRfp->store();
             }
         }
	     if (isset($bidInfo[6]))
	     {
	       if ($bidInfo[6] == 1)
	       {
		 $bid->delete();
		 continue;
	       }
	     }

             $bid->setProcurement( $RfpID );
             $bid->setCompany($bidInfo[1]);
             $bid->setPerson($bidInfo[2]);
             $bid->setAmount($bidInfo[4]);
             $bid->setWinner($bidInfo[5]);
             $bid->setRank($bidInfo[3]);

             if( $newBidInfo[6] != 1 ) {
               $bid->setRemoved(0);
	     }else {
	       $bid->setRemoved($newBidInfo[6]);
	     }

             $bid->store();
         }
        }

	if ($NewInfo != "")
	{
	  $NewArr = explode("***", $NewInfo);

	  foreach ($NewArr as $NewBid)
	  {
	    if ($bidInfo[6] == 1)
	      continue;
          if ($bidInfo[5] == 1)
         {
              $bidRfp->setAwardDate(&$curDate);
              $bidRfp->store();
         }
	    $newBidInfo = explode("|", $NewBid);
             $bid = new eZProcurementBid();
	     $bid->setProcurement( $RfpID );
             $bid->setCompany($newBidInfo[1]);
             $bid->setPerson($newBidInfo[2]);
             $bid->setAmount($newBidInfo[4]);
             $bid->setWinner($newBidInfo[5]);
             $bid->setRank($newBidInfo[3]);
	     $bid->setRemoved($newBidInfo[6]);

             $bid->store();
       }
     }
    }

    // die("fin");

    if ( ( $Action == "Insert" ) or ( $rfp->get( $RfpID ) == true ) )

    {

        if ( $Action == "Insert" )
        {
            $rfp = new eZRfp( );
            $user =& eZUser::currentUser();

	    /*
	    // k: add holder link into user here? why does the admin, have author data being stored, on top
	    //k: need author to = admin user to track rfp creation not holder names that's contents writers

            $rfp->setAuthor( $user );
	    */

        }

        $rfp->setName( $Name );
	$rfp->setProjectEstimate( $ProjectEstimate );
        $rfp->setProjectNumber( $ProjectNumber );

        $rfp->setProjectManager( $ProjectManager[0] );

	//echo("looking at the viewArray for Contents Writer ID: <br>");
	//viewArray($ContentsWriterID);
	//exit();

	$ia = 0;

	//echo ("lets take a look at the post vars<br>");
	//	v_array($_POST);
	// ################# BREAKDOWN #################


	/*

	$cwDbArr = serialize($ContentsWriterID);

        echo("
<div style='border: 1px solid #696; margin:10px; padding:5px; background: #FDF;'>
Here's the array we are going to get ready for db input.
<br> <br>
	");
v_array($ContentsWriterID);
echo("
<br><br>
Here is what it looks like as an insert into the db, using the variable <b>\$cwDbArr</b>:
<br>
	<br>
	<span style='margin:5px; padding: 5px; border: 1px dashed #353; color: #022; background: #FFF;'>
	UPDATE rfptable WHERE thisid='\$postid' SET ContentWriters = '$cwDbArr'
</span>
<br><br>
	Then AFTER we SELECT it from the db, it goes a little something like this:
	<span style='margin:5px; padding: 5px; border: 1px dashed #353; color: #022; background: #FFF;'>
	\$usableArray = unserialize(\$selectedContentsWriters);
	</span>
	<br><br>
	Now inside \$usableArray is all the values
</div>   

");

*/

//die();
// v_array($ContentsWriterID); die();


// replacment function
$rfp->setPlanholders($ContentsWriterID);

// die("END OF LINE");


//$rfp->setContentsWriter();
// $rfp->ContentsWriters=$cwDbArr;

/* Entire Loop Not Required

     foreach ( $ContentsWriterID as $writer )

     {

        //                $categoryIDArray[] = $writer->id();
        //      if ( $ContentsWriterID == $author->id() )

//      if ( $writer == $author->id() )
//        {
//            $author = new eZAuthor( $ContentsWriterID );

	    $author = new eZAuthor( $writer );
	    $authorArray[] = $author;
//	     $authorArray[] = $author->id();
            $ia++;
//        }
//        else
//        {
//            $author = new eZAuthor( $ContentsWriterID );
//            $rfp->setContentsWriter( $author );
//        }
    }

// odd print dbg.
//	viewArray($authorArray[1]->id());
//dylan: (ghb) $cwDbArr here ?
// original
//  $rfp->setContentsWriter( $authorArray );
//	exit();
//            $author = new eZAuthor( $ContentsWriterID );
//            $rfp->setContentsWriter( $author );
//        }


*/






        $generator = new eZRfpGenerator();
        $contents = $generator->generateXML( $Contents );

        $rfp->setContents( $contents );
        $rfp->setPageCount( $generator->pageCount() );

        // check if the contents is parseable
        if ( eZXML::domTree( $contents ) )
        {
            // to get ID
	   $rfp->store();

            // add to categories
            $category = new eZRfpCategory( $CategoryID );
            $rfp->setCategoryDefinition( $category );

	    /*
            $iniVar = $ini->read_var( "eZRfpMain", "LowerCaseManualKeywords" );

            if ( $iniVar == "enabled" )
                $toLower = true;
            else
                $toLower = false;

            $rfp->setManualKeywords( $Keywords, $toLower );
	    */

            $categoryArray =& $rfp->categories();

            // Calculate new and unused categories
            $old_maincategory = $rfp->categoryDefinition();
            $old_categories =& array_unique( array_merge( $old_maincategory->id(),
                                                          $rfp->categories( false ) ) );

            $new_categories = array_unique( array_merge( $CategoryID, $CategoryArray ) );
            $remove_categories = array_diff( $old_categories, $new_categories );
            $add_categories = array_diff( $new_categories, $old_categories );

            $categoryIDArray = array();
            foreach ( $categoryArray as $cat )
            {
                $categoryIDArray[] = $cat->id();
            }

            // clear the cache files.
            eZRfpTool::deleteCache( $RfpID, $CategoryID, $old_categories );

            foreach ( $remove_categories as $categoryItem )
            {
                eZRfpCategory::removeRfp( $rfp, $categoryItem );
            }

            // add to categories
            $category = new eZRfpCategory( $CategoryID );
            $category->addRfp( $rfp );
            $rfp->setCategoryDefinition( $category );

            foreach ( $add_categories as $categoryItem )
            {
                eZRfpCategory::addRfp( $rfp, $categoryItem );
            }

            //EP: URL translation inside rfps -------------------------
            if ( $UrltranslatorEnabled )
            {
                $tmpCategory = $rfp->categoryDefinition();
                $url1 = "/rfp/rfpview/" . $rfp->id() . "/1/" . $tmpCategory->id();

                include_once( "ezurltranslator/classes/ezurltranslator.php" );
                $urltranslator = new eZURLTranslator();
                $urltranslator->getbydest ( $url1 );

                if ( $Urltranslator )
                {
                    $urltranslator->setSource( $Urltranslator );
                    $urltranslator->setDest( $url1 );
                    $urltranslator->store();
                }
                else
                {
                    $urltranslator->delete();
                }

            }
            //EP -------------------------------------------------------------


            //k: switch interpret date into other dates

            // Time publishing

            if ( checkdate( $StartMonth, $StartDay, $StartYear ) )
            {
                $publishDate = new eZDateTime( $StartYear,  $StartMonth, $StartDay, $StartHour, $StartMinute, 0 );
                $rfp->setPublishDate( &$publishDate );
            }

            if ( checkdate( $StopMonth, $StopDay, $StopYear ) )
            {
                $responceDueDate = new eZDateTime( $StopYear, $StopMonth, $StopDay, $StopHour, $StopMinute, 0 );
                $rfp->setResponseDueDate( &$responceDueDate );
            }

            eZObjectPermission::removePermissions( $rfp->id(), "rfp_rfp", 'w' );

            if ( isset( $WriteGroupArray ) )
            {
                if ( $WriteGroupArray[0] == 0 )
                {
                    eZObjectPermission::setPermission( -1, $rfp->id(), "rfp_rfp", 'w' );
                }
                else
                {
                    foreach ( $WriteGroupArray as $groupID )
                    {
                        eZObjectPermission::setPermission( $groupID, $rfp->id(), "rfp_rfp", 'w' );
                    }
                }
            }
            else
            {
                eZObjectPermission::removePermissions( $rfp->id(), "rfp_rfp", 'w' );
            }

            /* read access : crosscheck */

            eZObjectPermission::removePermissions( $rfp->id(), "rfp_rfp", 'r' );

            if ( isset( $GroupArray ) )
            {
                if ( $GroupArray[0] == 0 )
                {
                    eZObjectPermission::setPermission( -1, $rfp->id(), "rfp_rfp", 'r' );
                }
                else // some groups are selected.
                {
                    foreach ( $GroupArray as $groupID )
                    {
                        eZObjectPermission::setPermission( $groupID, $rfp->id(), "rfp_rfp", 'r' );
                    }
                }
            }
            else
            {
                eZObjectPermission::removePermissions( $rfp->id(), "rfp_rfp", 'r' );
            }


            // add check for publishing rights here
            if ( $IsPublished == "on" )
            {
                // check if the rfp is published now
                if ( $rfp->isPublished() == false )
                {
                    $sendNotification = true;
                }

                $rfp->setIsPublished( true );
            }
            else
            {
                $rfp->setIsPublished( false );
            }


            // generate keywords
            $contents = strip_tags( $contents );
            $contents = ereg_replace( "#\n#", "", $contents );
            $contents_array =& split( " ", $contents );
            $contents_array = array_unique( $contents_array );

            $keywords = "";

            foreach ( $contents_array as $word )
            {
                $keywords .= $word . " ";
            }
            $rfp->setKeywords( $keywords );

            // store rfp
	    $rfp->store();

            $RfpID = $rfp->id();
        // process bids

            if ( $sendNotification )
                eZRfpTool::notificationMessage( $rfp );


            // Go to insert item..
            if ( isset( $AddItem ) )
            {
                switch ( $ItemToAdd )
                {
                    case "Image":
                    {   
                        // add images
                        eZHTTPTool::header( "Location: /rfp/rfpedit/imagelist/$RfpID/" );
                        exit();
                    }
                    break;

                    case "Media":
                    {   
                        // add media
                        eZHTTPTool::header( "Location: /rfp/rfpedit/medialist/$RfpID/" );
                        exit();
                    }
                    break;

                    case "File":
                    {
			// rfp change: don't just let them do that original scheme . . 
                        // add files

                        eZHTTPTool::header( "Location: /rfp/rfpedit/filelist/$RfpID/" );

                        exit();
                    }
                    break;

                    case "Attribute":
                    {
                        // add attributes
                        eZHTTPTool::header( "Location: /rfp/rfpedit/attributelist/$RfpID/" );
                        exit();
                    }
                    break;

                    case "Form":
                    {
                        // add form
                        eZHTTPTool::header( "Location: /rfp/rfpedit/formlist/$RfpID/" );
                        exit();
                    }
                    break;
                }
            }

            // preview
            if ( isset( $Preview ) )
            {
                eZHTTPTool::header( "Location: /rfp/rfppreview/$RfpID/" );
                exit();
            }

            // log history
            if ( isset( $Log ) )
            {
                eZHTTPTool::header( "Location: /rfp/rfplog/$RfpID/" );
                exit();
            }

            // get the category to redirect to            
            $category = $rfp->categoryDefinition( );
            $categoryID = $category->id();

            if ( $rfp->isPublished() )
            {
                eZHTTPTool::header( "Location: /rfp/archive/$categoryID/" );
            }
            else
            {
                eZHTTPTool::header( "Location: /rfp/unpublished/$categoryID/" );
            }
            exit();
        }
        else
        {
            $invalidContents = $contents;
           
            if ( $Action == "Insert" )
                $Action = "New";
            else
                $Action = "Edit";

            $ErrorParsing = true;        
        }
    }
}



$Language = $ini->read_var( "eZRfpMain", "Language" );


$t = new eZTemplate( "ezrfp/admin/" . $ini->read_var( "eZRfpMain", "AdminTemplateDir" ),
                     "ezrfp/admin/intl/", $Language, "rfpedit.php" );


$t->setAllStrings();
$t->set_file( "rfp_edit_page_tpl",  "rfpedit.tpl"  );

$t->set_block( "rfp_edit_page_tpl", "topic_item_tpl", "topic_item" );

$t->set_block( "rfp_edit_page_tpl", "value_tpl", "value" );

$t->set_block( "rfp_edit_page_tpl", "multiple_value_tpl", "multiple_value" );

$t->set_block( "rfp_edit_page_tpl", "category_owner_tpl", "category_owner" );

$t->set_block( "rfp_edit_page_tpl", "group_item_tpl", "group_item" );

$t->set_block( "rfp_edit_page_tpl", "publish_dates_tpl", "publish_dates" );

$t->set_block( "rfp_edit_page_tpl", "rfp_pending_tpl", "rfp_pending" );

$t->set_block( "rfp_edit_page_tpl", "author_pending_information_tpl", "author_pending_information" );

$t->set_block( "rfp_edit_page_tpl", "author_item_tpl", "author_item" );


$t->set_block( "publish_dates_tpl", "published_tpl", "published" );
$t->set_block( "publish_dates_tpl", "un_published_tpl", "un_published" );


$t->set_block( "rfp_edit_page_tpl", "error_message_tpl", "error_message" );
$t->set_block( "rfp_edit_page_tpl", "urltranslator_tpl", "urltranslator" );


$Locale = new eZLocale( $Language );

if ( $ErrorParsing == true )
{
    $t->set_var( "rfp_invalid_contents", $invalidContents );
    $t->parse( "error_message", "error_message_tpl" );
}
else
{
    $t->set_var( "error_message", "" );
}

$t->set_var( "rfp_is_published", "checked" );

$t->set_var( "rfp_id", "" );
$t->set_var( "rfp_name", stripslashes( $Name ) );
$t->set_var( "rfp_project_estimate", stripslashes( $ProjectEstimate ) );
$t->set_var( "rfp_project_number", stripslashes( $ProjectNumber ) );

//EP: URL translation : new rfp -------------------------------------------

if ( $ini->read_var( "eZRfpMain", "AdminURLTranslator" ) == "enabled" )
{
    $t->set_var( "rfp_url", "" );
    $t->set_var( "rfp_urltranslator", "" );
    $t->parse( "urltranslator", "urltranslator_tpl" );  
}
else
{
    $t->set_var( "rfp_url", "" );
    $t->set_var( "intl-rfp_nourl", "" );
    $t->set_var( "rfp_urltranslator", "" );
    $t->set_var( "urltranslator", "" );
}

//EP --------------------------------------------------------------------------        

$t->set_var( "rfp_keywords", stripslashes( $Keywords ) );
$t->set_var( "rfp_contents_0", stripslashes( $Contents[0] ) );
$t->set_var( "rfp_contents_1", stripslashes( $Contents[1] ) );
$t->set_var( "rfp_contents_2", stripslashes( $Contents[2] ) );
$t->set_var( "rfp_contents_3", stripslashes( $Contents[3] ) );
$t->set_var( "link_text", stripslashes( $LinkText ) );

/*
       include_once( "classes/ezdate.php" );

       $bdate = new ezdate();

        //    $n_date = $bdate->month() . "/" . $bdate->day() . "/" .  $bdate->year();

        $n_date = ucfirst($bdate->monthName()) . " " . $bdate->day() . ", " .  $bdate->year();

        $now_date = $n_date;

        //$t->set_var( "now_date", $n_date );

        $now_date = new eZDateTime;

        //K: x

*/


// feature addition, added default time to article creation 
// (could use client script to update before submission to server).

if ( $Action == "New" )
{

     $startDate = new eZDateTime();

     $StartYear = $startDate->year();

     $StartMonth = $startDate->month(); 

     $StartDay = $startDate->day();

     $StartHour = $startDate->hour();

     $StartMinute = $startDate->minute();

     

     $stopDate = new eZDateTime();

     $StopYear = $stopDate->year();

     $StopMonth = $stopDate->month() + 1;

     $StopDay = $stopDate->day();

     $StopHour = $stopDate->hour();

     $StopMinute = $stopDate->minute();
}



$t->set_var( "start_day", stripslashes( $StartDay ) );

$t->set_var( "start_month", stripslashes( $StartMonth ) );

$t->set_var( "start_year", stripslashes( $StartYear ) );

$t->set_var( "start_hour", stripslashes( $StartHour ) );

$t->set_var( "start_minute", stripslashes( $StartMinute ) );



$t->set_var( "stop_day", stripslashes( $StopDay ) );

$t->set_var( "stop_month", stripslashes( $StopMonth ) );

$t->set_var( "stop_year", stripslashes( $StopYear ) );

$t->set_var( "stop_hour", stripslashes( $StopHour ) );

$t->set_var( "stop_minute", stripslashes( $StopMinute ) );



$t->set_var( "action_value", "insert" );

$t->set_var( "all_selected", "selected" );


//$t->set_var( "all_write_selected", "selected" );

$t->set_var( "all_write_selected", "" );



$writeGroupsID = array(); 

$readGroupsID = array(); 



if ( $Action == "New" )

{

    $user =& eZUser::currentUser();

    $t->set_var( "author_text", $user->firstName() . " " . $user->lastName() );

    $rfp = new eZRfp();

}



$t->set_var( "author_pending_information", "" );

$t->set_var( "publish_dates", "" );

$t->set_var( "rfp_pending", "" );

if ( $Action == "Edit" )

{

    $rfp = new eZRfp();



    if ( !$rfp->get( $RfpID ) )

    {

        eZHTTPTool::header( "Location: /error/404/" );

        exit();

    }



    $definition =& $rfp->categoryDefinition();

    



    $t->set_var( "rfp_id", $RfpID );



    $pending = false;

    if ( $rfp->isPublished() )

    {

        if ( $rfp->isPublished() == 2 )

        {

            $pending = true;

            $t->parse( "rfp_pending", "rfp_pending_tpl" );

        }

        else

            $t->set_var( "rfp_is_published", "checked" );

    }

    else

    {

        $t->set_var( "rfp_is_published", "" );

    }



    if ( $rfp->discuss() )

    {

        $t->set_var( "discuss_rfp", "checked" );

    }

    else

    {

        $t->set_var( "discuss_rfp", "" );

    }



    $publishDate =& $rfp->publishDate();

    $responceDueDate =& $rfp->responceDueDate();



    if ( $rfp->publishDate( false ) != 0 )

    {

        $t->set_var( "start_day", "" );

        $t->set_var( "start_month", "" );

        $t->set_var( "start_year", "" );

        $t->set_var( "start_hour", "" );

        $t->set_var( "start_minute", "" );

        if ( get_class( $publishDate ) == "ezdatetime" )

        {

            $t->set_var( "start_day", $publishDate->addZero( $publishDate->day() ) );

            $t->set_var( "start_month", $publishDate->addZero( $publishDate->month() ) );

            $t->set_var( "start_year", $publishDate->addZero( $publishDate->year() ) );

            $t->set_var( "start_hour", $publishDate->addZero( $publishDate->hour() ) );

            $t->set_var( "start_minute", $publishDate->addZero( $publishDate->minute() ) );

        }

    }



    if ( $rfp->responceDueDate( false ) != 0 )

    {

        $t->set_var( "stop_day", "" );

        $t->set_var( "stop_month", "" );

        $t->set_var( "stop_year", "" );

        $t->set_var( "stop_hour", "" );

        $t->set_var( "stop_minute", "" );

        

        if ( get_class( $responceDueDate ) == "ezdatetime" )

        {

            $t->set_var( "stop_day", $publishDate->addZero( $responceDueDate->day() ) );

            $t->set_var( "stop_month", $publishDate->addZero( $responceDueDate->month() ) );

            $t->set_var( "stop_year", $publishDate->addZero( $responceDueDate->year() ) );

            $t->set_var( "stop_hour", $publishDate->addZero( $responceDueDate->hour() ) );

            $t->set_var( "stop_minute", $publishDate->addZero( $responceDueDate->minute() ) );

        }

    }

    

    if ( !isset( $Name ) )
        $t->set_var( "rfp_name", $rfp->name() );
        $t->set_var( "rfp_project_estimate", $rfp->projectEstimate() );
        $t->set_var( "rfp_project_number", $rfp->projectNumber() );

    // rfp contents intro & body of rfp. 
    // refactor to only hold and store the body. 

    $generator = new eZRfpGenerator();
    $contentsArray = $generator->decodeXML( $rfp->contents() );

    $i = 0;

    foreach ( $contentsArray as $content )
    {
        if ( !isset( $Contents[$i] ) )
        {
            $t->set_var( "rfp_contents_$i", htmlspecialchars( $content ) );
        }
        $i++;
    }


    //EP: URL translation: rfp edit get translation
    if ( $ini->read_var( "eZRfpMain", "AdminURLTranslator" ) == "enabled" )
    {    
        $category = $rfp->categoryDefinition();

        $url1 = "/rfp/rfpview/" . $rfp->id() . "/1/" . $category->id();

        $t->set_var( "rfp_url", $url1 );

	$t->set_var( "intl-rfp_nourl", "" );

        include_once( "ezurltranslator/classes/ezurltranslator.php" );

        $urltranslator = new eZURLTranslator();
        $urltranslator->getbydest ( $url1 );

        $t->set_var( "rfp_urltranslator", $urltranslator->source() );
        $t->parse( "urltranslator", "urltranslator_tpl" );
    }



    /*
    $t->set_var( "rfp_keywords", $rfp->manualKeywords() );
    $t->set_var( "author_text", $rfp->authorText() );
    $t->set_var( "author_email", $rfp->authorEmail() );

    if ( $pending )
    {
        $t->parse( "author_pending_information", "author_pending_information_tpl" );
    }
    else
    {
        $t->set_var( "author_pending_information", "" );
    }

    $t->set_var( "link_text", $rfp->linkText() );
    */


    $t->set_var( "action_value", "update" );





    $authorz = $rfp->planholders();
    $ContentsWriterID = $authorz;

    $authorz__id = $authorz[0];


    /*
      viewArray($ContentsWriterID);
      print($ContentsWriterID[0]->id());
      print($ContentsWriterID[1]->id());
      print($ContentsWriterID[2]->id());
    */


    /*
    $writeGroupsID = eZObjectPermission::getGroups( $RfpID, "rfp_rfp", 'w' , false );
    $readGroupsID = eZObjectPermission::getGroups( $RfpID, "rfp_rfp", 'r', false );

    if ( $writeGroupsID[0] != -1 )
        $t->set_var( "all_write_selected", "" );

    if ( $readGroupsID[0] != -1 )
        $t->set_var( "all_selected", "" );
    */

    // dates

    $published =& $rfp->published();
    $created =& $rfp->created();
    $modified =& $rfp->modified();
    $responceDue =& $rfp->responceDueDate();
    $awardDate =& $rfp->awardDate(false);

    if( $awardDate ) {
      $awardDateObj = new eZDateTime();
      $awardDateObj->setTimestamp( $awardDate );
      $awardDateFormat = $Locale->format( $awardDateObj );
    }else{
      $awardDateFormat = "Not Awarded";
    }

    $t->set_var( "published_date", $Locale->format( $published ) );
    $t->set_var( "created_date", $Locale->format( $created ) );
    $t->set_var( "modified_date", $Locale->format( $modified ) );
    $t->set_var( "responce_due_date", $Locale->format( $responceDue ) );
    $t->set_var( "award_date", $awardDateFormat );


    if ( $rfp->isPublished() == true )
    {
        $t->parse( "published", "published_tpl" );
        $t->set_var( "un_published", "" );        
    }
    else
    {
        $t->parse( "un_published", "un_published_tpl" );
        $t->set_var( "published", "" );
    }

    $t->parse( "publish_dates", "publish_dates_tpl" );
}


// author / holder list select

// eZUser Replacement Code for eZAuthor Code
// note: still needs a group id check, name or id lookup. / compare
// if g->ID() ==6, g->Name() =="Holders" (durring each foreach)
// eZUser::groups( $as_object = true ) [public] [ source ]
// $groupW = eZUser::groups();


$author = new eZUser();
$authorArray = new eZUserGroup();
$authorArray = $authorArray->users( $AnonymousUserGroup );

 //   $usergroup = new eZUserGroup();
 //   $userList = $usergroup->users( $GroupID, $OrderBy );

/*echo("<br>below is author array<br>");
viewArray($authorArray);
foreach ($authorArray as $obj) {
	$cname = get_class($obj);
	$varr = get_object_vars($obj);
	$mvar = get_class_methods($cname);
	print ("it's class is $cname<br>");
	print ("it's vars are<br>");
	v_array($varr);
	echo("<br><br>it's class methods are<br>");
	v_array($mvar);
	echo("<br>");
}
// exit();
*/


foreach ( $authorArray as $author )
{
  $groupW = new eZUserGroup();
  
  $authora = new eZUser();
  $authora = $authora->getUser( $author );
  
  $groupYY = $groupW->getByUser( $author );
  $groupYYx = $groupYY;

  $userGroupz = new eZUserGroup(3);
  $userGroupzName = $userGroupz->name();

  $groupR = $userGroupzName;

	$loop1 = 0;
	$loop2 = 0;
	$loop3 = 0;

	foreach( $ContentsWriterID as $writer )
	{
		$wID = $writer->id();
		$aID = $author->id();

		if ( $wID == $aID )
	 	{
		  $selectToggle = true;
		  // $t->set_var( "selected", "selected" );
		  // $t->set_var( "option_level", str_repeat( "&nbsp;&nbsp;", $catItem[1] ) );

		  $t->set_var( "option_level", "" );
		  $loop1 = $aID;
	    	}
	    	else
	    	{
		  $selectToggle = false;
		  //  $t->set_var( "selected", "a" );
		  // $t->set_var( "option_level", "" );
		}

		if ($wID == $aID) {
		  //print("<br /> $wID ==  $aID ");
		  $foundOne = true;
		}
    	}

	if ($foundOne) {
	 $mario = 'selected';
	} else {
	 $mario = '';
	}

	$foundOne=false;

	$t->set_var( "mario", $mario);
    	$t->set_var( "author_id", $author->id() );
	$t->set_var( "author_name", $author->name() );
    	$t->parse( "author_item", "author_item_tpl", true );
}

$t->set_block( "rfp_edit_page_tpl", "project_manager_item_tpl", "project_manager_item" );

// project manager
$personArr = getPersonArray();
  $projectManager = $rfp->projectManager();
  $projectManagerID = $projectManager->id();
for($i=0; $i<sizeof($personArr); $i++)
{
  // $projectManagerID = $projectManger;

  if( $personArr[$i]['ID'] == $projectManagerID ){
    $pm_sel = 'selected';
  } else {
    $pm_sel = '';
  }

  //  $t->set_var("person_iteration", $i);
  if ($personArr[$i]['ID'] != 1){
  $t->set_var("person_id", $personArr[$i]['ID']);
  $t->set_var("person_name", $personArr[$i]['LastName'].', '.$personArr[$i]['FirstName']);
  $t->set_var("pm_sel", $pm_sel );
  $t->parse( "project_manager_item", "project_manager_item_tpl", true );
  }
}


// category select
$category = new eZRfpCategory();
$categoryArray = $category->getAll( );



$tree = new eZRfpCategory();
$treeArray =& $tree->getTree();

$user =& eZUser::currentUser();

$catCount = count( $treeArray );
$t->set_var( "num_select_categories", min( $catCount, 10 ) );

foreach ( $treeArray as $catItem )
{
    if ( eZObjectPermission::hasPermission( $catItem[0]->id(), "rfp_category", 'w', $user ) == true ||
         eZRfpCategory::isOwner( eZUser::currentUser(), $catItem[0]->id() ) )
    {
        if ( $Action == "Edit" )
        {
            $defCat = $rfp->categoryDefinition( );

            if ( get_class( $defCat ) == "ezrfpcategory" )
            {

                if ( $rfp->existsInCategory( $catItem[0] ) && $defCat->id() != $catItem[0]->id() )
                {
                    $t->set_var( "multiple_selected", "selected" );
                }
                else
                {
                    $t->set_var( "multiple_selected", "" );
                }
            }
            else
            {
                $t->set_var( "selected", "" );
            }


            if ( get_class( $defCat ) == "ezrfpcategory" )
            {
                if ( $defCat->id() == $catItem[0]->id() )
                {
                    $t->set_var( "selected", "selected" );
                }
                else
                {
                    $t->set_var( "selected", "" );
                }
            }
            else
            {
                $t->set_var( "selected", "" );
            }
        }
        else
        {
	  if ($catItem[0]->id() == 11 ) {
	    $t->set_var( "selected", "selected" );
	  }else{
	    $t->set_var( "selected", "" );
	  }

	  $t->set_var( "multiple_selected", "" );
        }


        $t->set_var( "option_value", $catItem[0]->id() );
        $t->set_var( "option_name", $catItem[0]->name() );

        if ( $catItem[1] > 1 )
            $t->set_var( "option_level", "" );
	    // $t->set_var( "option_level", str_repeat( "&nbsp;&nbsp;", $catItem[1] ) );
        else
            $t->set_var( "option_level", "" );

        $t->parse( "value", "value_tpl", true );
        $t->parse( "multiple_value", "multiple_value_tpl", true );
    }
}



// group selector
$group = new eZUserGroup();
$groupList = $group->getAll();

$t->set_var( "selected", "" );

foreach ( $groupList as $groupItem )
{
    //for the group owner selector */
    $t->set_var( "module_owner_id", $groupItem->id() );
    $t->set_var( "module_owner_name", $groupItem->name() );

    if ( in_array( $groupItem->id(), $writeGroupsID ) )
        $t->set_var( "is_selected", "" );
    else
        $t->set_var( "is_selected", "" );

    $t->parse( "category_owner", "category_owner_tpl", true );

    // for the read access groups selector */
    $t->set_var( "group_name", $groupItem->name() );
    $t->set_var( "group_id", $groupItem->id() );

    if ( in_array( $groupItem->id(), $readGroupsID ) )
        $t->set_var( "selected", "selected" );
    else
        $t->set_var( "selected", "" );

    $t->parse( "group_item", "group_item_tpl", true );
}

// bids
$rfp = new eZRfp($RfpID);
$bidArr = $rfp->bids();

$t->set_block( "rfp_edit_page_tpl", "bid_list_tpl", "bid_list" );
$t->set_block( "bid_list_tpl", "bid_list_options_tpl", "bid_list_options" );
$t->set_block( "bid_list_tpl", "bid_company_tpl", "bid_company" );
$t->set_block( "rfp_edit_page_tpl", "js_company_tpl", "js_company" );
$t->set_block( "rfp_edit_page_tpl", "js_person_tpl", "js_person" );
$t->set_block( "js_person_tpl", "js_person_pre_tpl", "js_person_pre" );
$t->set_block( "bid_list_tpl", "bid_rank_tpl", "bid_rank" );
$t->set_block( "bid_list_tpl", "bid_hidden_tpl", "bid_hidden" );
$t->set_block( "rfp_edit_page_tpl", "js_rank_tpl", "js_rank" );

if (empty($bidArr))
{
 $t->set_var("bid_id", 'new');
 $t->set_var("bid_list_name", 'New');
 $t->set_var("bid_hidden_info", '');
 $t->parse( "bid_hidden", "bid_hidden_tpl", true );
 $t->parse( "bid_list_options", "bid_list_options_tpl", true);
} else
{
  foreach ($bidArr as $bid)
 {
 	# Element Positioning
 	# BidId = 0
 	# BidCompany = 1
 	# BidPerson = 2
 	# BidRank = 3
 	# BidAmount = 4
 	# BidWinner = 5
 	# Deleted = 6

   $t->set_var("bid_id", $bid->id());
   $t->set_var("bid_list_name", $bid->rank() . ' - ' . getCompanyName($bid->company()) );
   $hiddenString = $bid->id() . '|' . $bid->company() . '|' . $bid->person() . '|'
   . $bid->rankID() . '|' . $bid->amount() . '|' . $bid->winner() . '|';
   $t->set_var("bid_hidden_info", $hiddenString);
   $t->parse( "bid_hidden", "bid_hidden_tpl", true );
   $t->parse( "bid_list_options", "bid_list_options_tpl", true);
 }
}

 /*
bid_id
bid_list_name
bid_company_id
bid_person_id
bid_person_name
bid_rank_id
bid_rank_name
bid_amount
bid_winner
*/

 // build company select
 $compArr = getCompanyArray();
 $t->set_var("company_iteration", 0);
 $t->set_var("bid_company_id", 0);
 $t->set_var("bid_company_name", 'No Company');
 $t->parse( "js_company", "js_company_tpl", true );
 $t->parse( "bid_company", "bid_company_tpl", true );
 for($i=0; $i<sizeof($compArr); $i++)
 {

    $t->set_var("company_iteration", $i+1);
    $t->set_var("bid_company_id", $compArr[$i]['ID']);
    $t->set_var("bid_company_name", $compArr[$i]['Name']);
    $t->parse( "js_company", "js_company_tpl", true );
    $t->parse( "bid_company", "bid_company_tpl", true );
 }
 // build person select
  $personArr = getPersonArray();
  $l=0;
  for($i=0; $i<sizeof($personArr); $i++)
 {
    if ($personArr[$i] == 1)
      continue;
    $personObj = new eZPerson($personArr[$i]['ID']);
    $pCompanies = $personObj->companies();
    $pTopCompany = $pCompanies[0];
    if (!is_object($pTopCompany))
    {
     $countvar = 'noneccount';
     $t->set_var("person_company_id", 0);
    }
    else
    {
    $countvar = $pTopCompany->id().'ccount';
    $t->set_var("person_company_id", $pTopCompany->id());
    }
    if (!isset($$countvar))
     $$countvar = 0;
    else
     $$countvar++;

    $t->set_var("person_iteration", $$countvar);
    $t->set_var("bid_person_id", $personArr[$i]['ID']);
    $t->set_var("bid_person_name", $personArr[$i]['LastName'].', '.$personArr[$i]['FirstName']);
    $t->parse("js_person_pre", "js_person_pre_tpl");
    $t->parse( "js_person", "js_person_tpl", true );
    $l++;
 }

 // build rank select
  $rankArr = getRankArray();
  for($i=0; $i<sizeof($rankArr); $i++)
 {

    $t->set_var("rank_iteration", $i);
    $t->set_var("bid_rank_id", $rankArr[$i]['ID']);
    $t->set_var("bid_rank_name", $rankArr[$i]['AlphaNumericName']);
    $t->parse( "js_rank", "js_rank_tpl", true );
    $t->parse( "bid_rank", "bid_rank_tpl", true );
 }
 $t->parse( "bid_list", "bid_list_tpl" );

$t->pparse( "output", "rfp_edit_page_tpl" );

function getCompanyArray()
{
 $db =& eZDB::globalDatabase();
 $db->array_query($ret, "SELECT ID, Name FROM eZContact_Company");
 return $ret;
}

function getPersonArray()
{
 $db =& eZDB::globalDatabase();
 $db->array_query($ret, "SELECT ID, FirstName, LastName FROM eZContact_Person Where ID <> 1");
 return $ret;
}

function getRankArray()
{
 $db =& eZDB::globalDatabase();
 $db->array_query($ret, "SELECT ID, Name, AlphaNumericName FROM eZProcurement_BidRank Order by ID");
 return $ret;
}
function getCompanyName($id)
{
 $db =& eZDB::globalDatabase();
 $db->array_query($ret, "SELECT Name FROM eZContact_Company WHERE ID='$id'");
 $ret = $ret[0]['Name'];
 return $ret;
}
?>

