<?php 
// 
// $Id: productreport.php,v 1.7 2001/07/20 11:28:54 jakobn Exp $
//
// Created on: <11-Jan-2001 14:47:56 bf>
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

//#############################################

	function view_array($arr, $td=0)
{
    if ($td) {
        echo '<td>';
    } 
    echo '<table style="border: 1px dashed black;">';
    foreach ($arr as $key => $elem) {
        echo '<tr>';
        echo '<td>' . $key . '&nbsp;</td>';
        if (is_array($elem)) {
            view_array($elem, 1);
        } else {
            echo '<td>' . htmlspecialchars($elem) . '&nbsp;</td>';
        } 
        echo '</tr>';
    } 
    echo '</table>';
    if ($td) {
        echo '</td>';
    } 
} 
// ###################### New Functions for Getting the Info #####################
// #  $MaxPower = the total view count for the whole array.                     ##
// #  $totalView = total items viewed by a user                                 ##
// ###############################################################################
function getMaxPower($homer)
{
    if (is_array($homer)) {
        for ($i = 0;$i < sizeof($homer);$i++) {
            $MaxPower += $homer[$i]['maxcount'];
        } 
        return $MaxPower;
    } else {
        $marge = "Chesty LaRue";
        return false;
    } 
} 

function getUserTotalCount($uid, $arr)
{
    if (is_array($arr)) {
        for ($i = 0;$i < sizeof($arr);$i++) {
            if (is_array($arr[$i]['stats'])) {
                for ($j = 0; $j < sizeof($arr[$i]['stats']);$j++) {
                    if ($uid == $arr[$i]['stats'][$j]['UserID']) {
                        $totalView += $arr[$i]['stats'][$j]['count'];
                    } 
                } 
            } 
        } 
        return $totalView;
    } else {
        return false;
    } 
} 

//###############################################################################
// this function converts a URI into an array with the module name and the URI id into 'module' and 'id' respectively

function getRfpStatInfo($uri) {
  $uri_arr = explode("/", $uri);
  $info = array();
  $info['module'] = $uri_arr[1];
  $info['id'] = $uri_arr[3];
  return $info;
}

// ###############################################################################
include_once("classes/INIFile.php");
$ini = &INIFile::globalINI();
$Language = $ini->read_var("eZStatsMain", "Language");

include_once("classes/eztemplate.php");
include_once("classes/ezdate.php");

include_once("ezstats/classes/ezpageview.php");
include_once("ezstats/classes/ezpageviewquery.php");

include_once("ezrfp/classes/ezrfp.php");
include_once("ezuser/classes/ezuser.php");
// include_once( "eztrade/classes/ezproduct.php" );
// include_once( "eztrade/classes/ezorder.php" );
// #############################################
$t = new eZTemplate("ezrfp/admin/" . $ini->read_var("eZStatsMain", "AdminTemplateDir"),
    "ezrfp/admin/intl", $Language, "rfpreport.php");

$t->setAllStrings();

$t->set_file(array("rfp_report_tpl" => "rfpreport.tpl"
        ));

$t->set_block("rfp_report_tpl", "viewed_by_user_tpl", "viewed_by_user");
$t->set_block("rfp_report_tpl", "most_viewed_rfp_tpl", "most_viewed_rfp");

$t->set_block("rfp_report_tpl", "empty_rfp_header_tpl", "empty_rfp_header");
$t->set_block("rfp_report_tpl", "most_viewed_rfp_header_tpl", "most_viewed_rfp_header");

$t->set_block("rfp_report_tpl", "most_added_to_cart_rfps_tpl", "most_added_to_cart_rfps");
$t->set_block("rfp_report_tpl", "most_added_to_wishlist_rfps_tpl", "most_added_to_wishlist_rfps");
$t->set_block("rfp_report_tpl", "most_bought_rfps_tpl", "most_bought_rfps");

//$t->set_block("rfp_report_tpl", "month_tpl", "month");
//$t->set_block("month_tpl", "month_previous_tpl", "month_previous");
//$t->set_block("month_tpl", "month_previous_inactive_tpl", "month_previous_inactive");
//$t->set_block("month_tpl", "month_next_tpl", "month_next");
//$t->set_block("month_tpl", "month_next_inactive_tpl", "month_next_inactive");

    $t->set_var("most_viewed_rfp_header", '');

// $t->parse("most_viewed_rfp_header", "most_viewed_rfp_header_tpl", true);

// $t->set_var( "user_bg_color", "bgdark" );
// #############################################
if (!is_numeric($Year) || !is_numeric($Month)) {
    $cur_date = new eZDate();
    $Year = $cur_date->year();
    $Month = $cur_date->month();
} 

$query = new eZPageViewQuery();

$tmpRfp = new eZRfp();
$tmpRfpFile = new eZFile();
// most viewed rfps
$rfpReport = &$query->topRfpRequests();
$rfpArray = array();
// this is the array in which we will keep the final build
$fullArray = array(); 
// $z is used to make sure we get consecutive numbers through $fullArray
$z = 0; 
//#############################################

// debug variables (enables 3 stage of visual data debug)

// $the_debug = true;
$the_debug = false;

// $db_array_debug = true;
$db_array_debug = false;

$array_debug = false;
// $array_debug = true;

if ($db_array_debug){
	view_array($rfpReport);
	print("<br>");
}

// #############################################
for ($i = 0;$i < sizeof($rfpReport);$i++) {
    $rfpFound = false;
    $UserIDFound = false;
    $rep = $rfpReport[$i]; // put $rfpReport[$i] into $rep for ez coding and readibility
    if (empty($fullArray)) { // if we have not begun to fill $fullArray, don't bother running the nested loop
        $info = getRfpStatInfo($rep['URI']);
        $fullArray[$z]['uri'] = $rep['URI']; //store uri
        $fullArray[$z]['id'] = $info['id']; // store id
        $fullArray[$z]['module'] = $info['module']; // store module
        $fullArray[$z]['stats'] = array();
        $fullArray[$z]['stats'][0]['UserID'] = $rep['UserID'];
        $fullArray[$z]['stats'][0]['count'] = 1;
        $fullArray[$z]['maxcount']++;
        $z++; //get ready for the next entry iee
    } else { // we run the loop checking to see if the URI has been catalouged
        for ($j = 0;$j < sizeof($fullArray);$j++) {
			 $info = getRfpStatInfo($rep['URI']);
            if ($info['id'] == $fullArray[$j]['id']) {
                $rfpFound = true; // now we check to see if the user id is found
                for ($d = 0;$d < sizeof($fullArray[$j]['stats']);$d++) {
                    // now we check to see if the user id is found. $d will be for checking stats entries
                    if ($fullArray[$j]['stats'][$d]['UserID'] == $rep['UserID']) { // if the id has already been listed...
                        $fullArray[$j]['stats'][$d]['count']++; // add one to the count
                        $fullArray[$j]['maxcount']++;
                        $UserIDFound = true; // toggle the user id found;
                    } 
                } 
                if (!$UserIDFound) { // then we will add a new listing to the stats array and start a new UserId and count
                    $e = count($fullArray[$j]['stats']); // sets $e as the next stats array listing;
                    $fullArray[$j]['stats'][$e] = array();
                    $fullArray[$j]['stats'][$e]['UserID'] = $rep['UserID'];
                    $fullArray[$j]['stats'][$e]['count'] = 1;
                    $fullArray[$j]['maxcount']++;
                } 
            } 
        } 
        if (!$rfpFound) { // then we will add a new uri listing
            $info = getRfpStatInfo($rep['URI']);
            $fullArray[$z]['uri'] = $rep['URI']; //store uri
            $fullArray[$z]['id'] = $info['id']; // store id
            $fullArray[$z]['module'] = $info['module']; // store module
            $fullArray[$z]['stats'] = array();
            $fullArray[$z]['stats'][0]['UserID'] = $rep['UserID'];
            $fullArray[$z]['stats'][0]['count'] = 1;
            $fullArray[$z]['maxcount'] = 1;
            $z++;
            $rfpFound = false;
        } 
    } 
} 

//##############################
// View Entire Sql Result Set (As Array)
if ($array_debug){
	view_array($fullArray);
	// exit();
}
$z = 0; // $z is used to make sure we get consecutive numbers through $fullArray

if (count($fullArray) != 0) {
    for ($i = 0;$i < sizeof($fullArray);$i++) {
        $statRfpID = $fullArray[$i]['id'];
        $statModule = $fullArray[$i]['module'];

        $statUri = $fullArray[$i]['uri'];
	$statUriDecoded = rawurldecode($statUri);
        $statUriEncoded = rawurlencode($statUri);

        $statMaxCount = $fullArray[$i]['maxcount'];

        if ($statModule == "rfp") {
            $statRfp = new eZRfp();
            $statRfp = new eZRfp($statRfpID);
            $statName = $statRfp->name();
        } elseif ($statModule == "filemanager") {
            include_once("ezfilemanager/classes/ezvirtualfile.php");
            $tmpfile = new eZVirtualFile($statRfpID);
            $statName = $tmpfile->name();
        } 

        $statUserList = sizeof($fullArray[$i]['stats']);

        $rfpexpol = explode("/" , $statUri);

        $rfpsurName = $statUri;
        $rfpsurNameName = $rfpexpol[4];
        $tntStatName = $rfpsurNameName;
        $statDuplicate = false;
        if ($statName == $tntStatName) {
        } 
        $statDuplicate = true;
        if ($statDuplicate == true) {
            $t->set_var("viewed_by_user", ""); 
            // size of  user array
            $UserListCount = count($statUserList);
            for ($ei = 0;$ei < $statUserList;$ei++) {
                $statUserID = $fullArray[$i]['stats'][$ei]['UserID'];
                $statUser = new eZUser();
                
                if ($array_debug){
 		  print('this is: '.$statUserID.'<br>');
		}
                
                if ($statUserID != 0) {
                    $statUser = new eZUser($statUserID); // $statUser->get($statUserID);
                } else {
                    // flag here: should change this to do some error handling?
                    $statUser = new eZUser(10);
                } 
                $statUserName = $statUser->name();
                $statUserListz = sizeof($fullArray[$i]['stats']);
                for ($na = 0;$na < $statUserListz;$na++) {
                    if ($statUserID == $fullArray[$i]['stats'][$na]['UserID']) {
                        $statUserCount = $fullArray[$i]['stats'][$na]['count'];
                    } 
                } 
                $totalCounts[$statUserName] = getUserTotalCount($statUserID, $fullArray);
                $t->set_var("user_bg_color", "bgdark");
                $t->set_var("item_view_count", '' . $statMaxCount); // dylan note - this was/is putting out the correct data
                $t->set_var("user_view_count", '' . $statUserCount);

               
               	if ($the_debug){
		  print('UserID:'.$statUserID.' - Username: '.$statUserName.'<br>');
//		  print('UserID:'.$statUserID.'<br>');

		}
                
                $t->set_var("rfp_download_user_name", $statUserName);
                $t->set_var("rfp_downloaded_user_id", $statUserID);
                $t->parse("viewed_by_user", "viewed_by_user_tpl", true);
            } 
            $t->set_var("rfp_name", $statName);
            $t->set_var("rfp_uri", $statUriDecoded);
	    $t->set_var("rfp_uri_encoded", $statUriDecoded);

            $t->set_var("bg_color", "bglight");
            $t->set_var("view_count", '' . $statMaxCount);
            $t->parse("most_viewed_rfp", "most_viewed_rfp_tpl", true);
        } 
        $t->set_var("empty_rfp_header", '');
    } 
} else {
    $t->parse("empty_rfp_header", "empty_rfp_header_tpl", true);
    $t->set_var("most_viewed_rfp_header", '');
    $t->set_var("most_viewed_rfp", '');
} 
foreach ($rfpReport as $rfp) {
    $idx = $regArray[1];
    $rfp_url_array = explode("/", $rfp["URI"]);
    $rfpArray[$idx]["URLID"] = $rfp_url_array[3];
    $count = $rfpArray[$idx]["Count"];
    $rfpArray[$idx]["Count"] = $rfp["Count"];
    $rfpArray[$idx]["ID"] = $regArray[1];
    $rfpArray[$idx]["UserID"] = $rfp["UserID"];
    $rfpArray[$idx]["URI"] = $rfp["URI"];
} 
$i = 0;
$id = 0;
$loopID = 0;
$bgcolor = 0;
$item_count = 0;
$user_count = 0;
$loopUserID = 0;

foreach ($rfpReport as $rfpItem) {
    $rfp_url_array = explode("/", $rfpItem["URI"]);
    $rfp_url_id = $rfp_url_array[3];
    $module_action = $rfp_url_array[2];
    $module_name = $rfp_url_array[1];
    $theRfpID = $rfpItem->id;
    if ($loopID == $rfpItem["ID"]) {
        $blank = 0;
    } elseif ($loopID != $rfpItem["ID"]) {
        $loopID = $rfpItem["ID"];
        if ($module_name == "rfp") {
            $rfpItemIDE = $rfp_url_id;
            $rfpItemIDE = $tmpRfp->get($rfpItemIDE);
            $rfpItemIDE = $tmpRfp->name();
        } else {
            include_once("ezfilemanager/classes/ezvirtualfile.php");
            $tmpRfp = new eZVirtualFile($rfp_url_id);
        } 
        $rfpUserID = $rfpItem["UserID"];

        if ($rfpUserID != 0) {
            $rfpUser = new eZUser($rfpUserID);
        } else {
            $rfpUser = new eZUser(10);
        } 
        $rfpUserName = $rfpUser->name();
        $rfpQuery = new eZPageViewQuery();
        $user_count = $user_count + 1;
        if ($rfpItem["UserID"] == "0") {
            $rfpItemUserIDD = "10";
        } else {
            $rfpItemUserIDD = $rfpItem["UserID"];
        } 
        if ($loopUserID == $rfpItem["UserID"]) {
        } elseif ($loopUserID != $rfpItem["UserID"]) {
            $blank = 0;
        } 
        $loopUserID = $rfpItem["UserID"];
        ++$i;
        ++$i;
    } 
} 
$t->set_var("month", "");
$t->pparse("output", "rfp_report_tpl");
?>