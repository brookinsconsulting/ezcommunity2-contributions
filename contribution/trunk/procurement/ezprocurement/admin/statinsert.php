<?php
include_once( "classes/ezdb.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/ezhttptool.php");
$db =& eZDB::globalDatabase();
if (!isset($Num)) $Num = 10;
for ($i=0; $i<$Num; $i++)
{
 $UserIDArr= array(0, 3, 4, 18, 22, 21);
 shuffle($UserIDArr);
 $BrowserTypeArray= array(11, 28);
 shuffle($BrowserTypeArray);
 $RefererArr = array(82, 92, 94, 93, 73, 1);
 shuffle($RefererArr);
 $RequestArr = array(5, 4, 2, 1, 7, 14, 15, 16, 43, 45, 47, 56, 61);
 shuffle($RequestArr);
  $nextID = $db->nextID( "eZStats_PageView", "ID" );
$RemoteHostID = 1;
$UserID = $UserIDArr[0];
$BrowserTypeID = $BrowserTypeArray[0];
$RefererURLID = $RefererArr[0];
$RequestPageID = $RequestArr[0];
$db->query( "INSERT INTO eZStats_PageView
                                ( ID, UserID, BrowserTypeID, RemoteHostID, RefererURLID, RequestPageID )
                                VALUES ( '$nextID',
                                         '$UserID',
                                         '$BrowserTypeID',
                                         '$RemoteHostID',
                                         '$RefererURLID',
                                         '$RequestPageID' )
                                " );
}
eZHTTPTool::header( "Location: /procurement/report" );
php?>
