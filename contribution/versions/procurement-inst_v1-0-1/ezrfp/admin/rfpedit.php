

<?php

// 

// $Id: rfpedit.php,v 1.116.2.10 2002/05/14 06:52:21 bf Exp $

//

// Created on: <18-Oct-2000 15:04:39 bf>

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

include_once( "classes/ezcachefile.php" );



include_once( "ezuser/classes/ezuser.php" );

include_once( "ezuser/classes/ezobjectpermission.php" );

include_once( "ezuser/classes/ezauthor.php" );

include_once( "classes/ezhttptool.php" );



include_once( "ezrfp/classes/ezrfpcategory.php" );

include_once( "ezrfp/classes/ezrfp.php" );

include_once( "ezrfp/classes/eztopic.php" );

include_once( "ezrfp/classes/ezrfpgenerator.php" );

include_once( "ezrfp/classes/ezrfprenderer.php" );



//include_once( "ezbulkmail/classes/ezbulkmail.php" );

//include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );



include_once( "ezrfp/classes/ezrfptool.php" );



include_once( "ezxml/classes/ezxml.php" );

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

//    eZHTTPTool::header( "Location: /article/archive/0/" );

    exit();

}





// update an existing rfp in the database

if ( $Action == "Update" || ( $Action == "Insert" ) )

{

    $sendNotification = false;

    $rfp = new eZRfp( );



    if ( ( $Action == "Insert" ) or ( $rfp->get( $RfpID ) == true ) )

    {

        if ( $Action == "Insert" )

        {

            $rfp = new eZRfp( );

            $user =& eZUser::currentUser();



	// k: add holder link into user here? why does the admin, have author data being stored, on top

//k: need author to = admin user to track rfp creation not holder names that's contents writers
            $rfp->setAuthor( $user );

        }



        $rfp->setName( $Name );

	$rfp->setProjectEstimate( $ProjectEstimate );



// k: code for holder / user switch!


/*
        if ( trim( $NewAuthorName ) != "" )

        {

  //          $author = new eZAuthor();

	    $author = new eZUser(); 

            $author->setName( $NewAuthorName );

            $author->setEmail( $NewAuthorEmail );

            $author->store();

            $rfp->setContentsWriter( $author );

        }

        else

        {
*/


// hey dylan, here is the author section, see my foreach that loops over the $ContentsWriterID Array

// seems like i was building a result array to display the author objects

// just so you know two keyword variables = author, ContentsWriterID, follow them to track the associated vars

// btw i have another hey dylan comment below?



//print($ContentsWriterID[1]);

//print($ContentsWriterID[2]);



//echo("looking at the viewArray for Contents Writer ID: <br>");
//viewArray($ContentsWriterID);

//exit();

$ia = 0;

//echo ("lets take a look at the post vars<br>");
//	v_array($_POST);

################## DJ DYLAN'S GONNA BREAK IT DOWN #################
		
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

//$rfp->setContentsWriter();
$rfp->ContentsWriters=$cwDbArr;


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



// kill topic

//        $topic = new eZTopic( $TopicID );

//        $rfp->setTopic( $topic );



        $generator = new eZRfpGenerator();



        $contents = $generator->generateXML( $Contents );



        $rfp->setContents( $contents );

        $rfp->setPageCount( $generator->pageCount() );





// k

        $rfp->setAuthorText( $AuthorText );

        $rfp->setAuthorEmail( $AuthorEmail );



        $rfp->setLinkText( $LinkText );





// k: needed? 



        if ( trim( $LogMessage ) != "" )

            $rfp->addLog( $LogMessage );



// example of boolian switch!



        if ( $Discuss == "on" )

            $rfp->setDiscuss( true );

        else

            $rfp->setDiscuss( false );



        // check if the contents is parseable
  // this is extra prolly -- dylan note
        if ( eZXML::domTree( $contents ) )

        {

            // to get ID

           // $rfp->store();
	   $rfp->store();



            // add to categories

            $category = new eZRfpCategory( $CategoryID );

            $rfp->setCategoryDefinition( $category );

            

            $iniVar = $ini->read_var( "eZRfpMain", "LowerCaseManualKeywords" );

        

            if ( $iniVar == "enabled" )

                $toLower = true;

            else

                $toLower = false;

        

            $rfp->setManualKeywords( $Keywords, $toLower );



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



            /* read access thingy */

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



          //  $rfp->store();
	$rfp->store();


            $RfpID = $rfp->id();



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

$t->set_var( "author_text", stripslashes( $AuthorText ) );

$t->set_var( "author_email", stripslashes( $AuthorEmail ) );

$t->set_var( "link_text", stripslashes( $LinkText ) );



/*

       include_once( "classes/ezdate.php" );



       $bdate = new ezdate();

        //    $n_date = $bdate->month() . "/" . $bdate->day() . "/" .  $bdate->year();

        $n_date = ucfirst($bdate->monthName()) . " " . $bdate->day() . ", " .  $bdate->year();

        $now_date = $n_date;

        //$t->set_var( "now_date", $n_date );

$now_date = new eZDateTime;

//K: 

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





// rfp contents intro & body of rfp. refactor to only hold and store the body. so check out the sub components all ready and fix the code you chode.



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



    $t->set_var( "action_value", "update" );





//    $author = $rfp->contentsWriter();


if ( $Action == "New" )
{

// xxx

}


    $authorz = $rfp->contentsWriters();

// die('|'. $authorz .'|');

//    $ContentsWriterID = $author->id();

     $ContentsWriterID = $authorz;



/*

viewArray($ContentsWriterID);

print($ContentsWriterID[0]->id());



print($ContentsWriterID[1]->id());



print($ContentsWriterID[2]->id());

*/



//    print ( $rfp->contentsWriters() .'!!!!!!!!!');



    //    $topic = $rfp->topic(); 

    //    $TopicID = $topic->id();



    $writeGroupsID = eZObjectPermission::getGroups( $RfpID, "rfp_rfp", 'w' , false );

    $readGroupsID = eZObjectPermission::getGroups( $RfpID, "rfp_rfp", 'r', false );



    if ( $writeGroupsID[0] != -1 )

        $t->set_var( "all_write_selected", "" );

    if ( $readGroupsID[0] != -1 )

        $t->set_var( "all_selected", "" );



    // dates

    $published =& $rfp->published();

    $created =& $rfp->created();

    $modified =& $rfp->modified();

    $responceDue =& $rfp->responceDueDate();



    $t->set_var( "published_date", $Locale->format( $published ) );

    $t->set_var( "created_date", $Locale->format( $created ) );

    $t->set_var( "modified_date", $Locale->format( $modified ) );

    $t->set_var( "responce_due_date", $Locale->format( $responceDue ) );



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





// author select

/*



$author = new eZAuthor();

$authorArray = $author->getAll();

*/



// eZUser Replacement Code for eZAuthor Code

// note: still needs a group id check, name or id lookup. / compare

// if g->ID() ==6, g->Name() =="Holders" (durring each foreach)

// eZUser::groups( $as_object = true ) [public] [ source ]

// $groupW = eZUser::groups();



$author = new eZUser();

//$authorArray = $author->getAll();

//$authorArray = $author->getUsersByGroup($AnonymousUserGroup);

$authorArray = new eZUserGroup();

$authorArray = $authorArray->users( $AnonymousUserGroup );



//    $usergroup = new eZUserGroup();

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
}*/
	
//exit();

foreach ( $authorArray as $author )

{

$groupW = new eZUserGroup();

// 3

//$groupW->get( 3 );

//  $groupR = $groupW->getByUser( $author );

// eZUserGroup::isMember( $user ) [public] [ source ]



$authora = new eZUser();

$authora = $authora->getUser( $author );



$groupYY = $groupW->getByUser( $author );

$groupYYx = $groupYY;

//$groupYYx = $groupYY[0];

//$groupYYx = $groupYY[3];

//print($author->id());

//viewArray($groupYYx);

//exit();



$userGroupz = new eZUserGroup(3);

//$userGroupz = $userGroupz->get($AnonymousUserGroup);



$userGroupzName = $userGroupz->name();

$groupR = $userGroupzName;



/*

 foreach ( $groupYYx as $msn )

{



//viewArray($msn);

// print($msn->Name ."<br />");

//$groupR = $msn->Name; // $rfp["URI"]



}

*/



//$groupR = $authora;

//print ("<hr><b>". $ContentsWriterID .' '. $author->id() ."</b>");

//$ContentsWriterID = '';

//$ContentsWriterID[0] = 3;

//$ContentsWriterID[1] = 2;



//print( "<b>". $ContentsWriterID .' '. $author->id() ."</b><br>");

//$ContentsWriterID = $ContentsWriterID[1]->id();

//viewArray($ContentsWriterID); print("|- <br />");



// hey dylan! comment #2, thing is first (above this) is the authorList foreach loop 

// at this point (right here) we are in a loop that as you can see bellow is my rfpAuthorArray foreach loop where i try to do some matching



// would you believe i got most of your stats array code to display perfectly (thank you!) but i'm having trouble doing the same thing, printing the users (my if loop kills the server / file via maxtimeout?) . in a run, will try to add more comments before 4pm today , check the timestamp , i need sleep





// $writer = '';



	$loop1 = 0;

	$loop2 = 0;

	$loop3 = 0;
	
// dylan: here is the bug, i wonder if the ContentsWriterID shouldn't be preceeded by a check for if this is new rfp ie no selected writers yet or changed to ContentsWriters . . . i'm lost.

	foreach( $ContentsWriterID as $writer )

	{



		$wID = $writer->id();

		$aID = $author->id();

	



		if ( $wID == $aID )   

	 	{



//	print("dylan note: hey dylan here is where i can enable a printout of the author / holder ID array items. ya dig?");

	//print("<br /> $wID ==  $aID ");
	$selectToggle = true;


	       // $t->set_var( "selected", "selected" );

//	        $t->set_var( "option_level", str_repeat( "&nbsp;&nbsp;", $catItem[1] ) );

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
	$foundOne = true; }

    	}
	// dylan note - please forgive the weird variable names, it sort of happened while I was debugging the selected var
	

	if ($foundOne) {
		$mario = 'selected';
	} else {
//		$mario = 'mushroom';
	 $mario = '';
	}

/* 



	if ($aID != $loop1 ){

	

        $t->set_var( "author_item", '' );



	}else {

*/
		$foundOne=false;


		$t->set_var( "mario", $mario);
    	$t->set_var( "author_id", $author->id() );

//    	$t->set_var( "author_name", $author->name() .' -- '. $groupR .' -|' );



	$t->set_var( "author_name", $author->name() );

    	$t->parse( "author_item", "author_item_tpl", true );

//	}

}



// topic select



$topic = new eZTopic();

$topicArray = $topic->getAll();

foreach ( $topicArray as $topic )

{

    if ( $TopicID == $topic->id() )

    {

        $t->set_var( "selected", "selected" );

    }

    else

    {

        $t->set_var( "selected", "" );

    }

    $t->set_var( "topic_id", $topic->id() );

    $t->set_var( "topic_name", $topic->name() );

    $t->parse( "topic_item", "topic_item_tpl", true );

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

/*

        if ( $Action == "New" )

        {



	}

*/



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
//            $t->set_var( "option_level", str_repeat( "&nbsp;&nbsp;", $catItem[1] ) );

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



$t->pparse( "output", "rfp_edit_page_tpl" );



?>

