<?php

include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );

$db =& eZDB::globalDatabase();

$db->begin();

$timestamp = eZDateTime::timeStamp( true );


// BrowserType archive

$newelements = array();
$oldelements = array();
$res = array();

$db->array_query( $newelements, "SELECT COUNT(*), BrowserTypeID FROM eZStats_PageView WHERE Date < " . $timestamp . " GROUP BY BrowserTypeID" );
foreach ( $newelements as $element )
{
    $browser = array();
    $db->array_query( $browser, "SELECT BrowserType FROM eZStats_BrowserType WHERE ID='" . $element[$db->fieldName("BrowserTypeID")] . "'" );
    $browsername = $browser[0][$db->fieldName("BrowserType")];
    $db->array_query( $oldelements, "SELECT * FROM eZStats_Archive_BrowserType WHERE Browser='" . $browsername . "'");

    if ( count( $oldelements ) == 0 )
    {
        $db->lock( "eZStats_Archive_BrowserType" );
        $nextid = $db->nextID( "eZStats_Archive_BrowserType", "ID" );
        $res[] = $db->query( "INSERT INTO eZStats_Archive_BrowserType (ID, Browser, Count) VALUES ('$nextid', '$browsername', '$element[0]')" );
        $db->unlock();
    }
    else
    {
        $count = $oldelements[0][$db->fieldName("Count")] + $element[0];
        $res[] = $db->query( "UPDATE eZStats_Archive_BrowserType SET Count='$count' WHERE BrowserType='$browsername'" );
    }
}

// RequestPage archive

$db->array_query( $newelements, "SELECT Date, RequestPageID FROM eZStats_PageView WHERE Date < " . $timestamp . " ORDER BY Date" );

foreach ( $newelements as $element )
{
    $request = array();
    $db->array_query( $request, "SELECT URI FROM eZStats_RequestPage WHERE ID=" . $element[$db->fieldName("RequestPageID")] );
    $requestname = $request[0][$db->fieldName("URI")];
    $date = new eZDateTime();
    $date->setTimeStamp( $element[$db->fieldName("Date")] );
    $month = new eZDateTime( $date->year(), $date->month() );
    $db->array_query( $oldelements, "SELECT * FROM eZStats_Archive_RequestedPage WHERE URI='$requestname' AND Month='" . $month->timeStamp() . "'" );
    
    if ( count( $oldelements ) == 0 )
    {
        $db->lock( "eZStats_Archive_RequestedPage" );
        $nextid = $db->nextID( "eZStats_Archive_RequestedPage", "ID" );
        $res[] = $db->query( "INSERT INTO eZStats_Archive_RequestedPage (ID, URI, Month, Count) VALUES ('$nextid', '$requestname', '" . $month->timeStamp() . "', '1')" );
        $db->unlock();
    }
    else
    {
        $count = $oldelements[0][$db->fieldName("Count")] + 1;
        $res[] = $db->query( "UPDATE eZStats_Archive_RequestedPage SET Count='$count' WHERE URI='$requestname' AND Month='" . $month->timeStamp() . "'" );
    }
}

// ReferURL archive

$db->array_query( $newelements, "SELECT Date, RefererURLID FROM eZStats_PageView WHERE Date < " . $timestamp . " ORDER BY Date" );

foreach ( $newelements as $element )
{
    $refer = array();
    $db->array_query( $refer, "SELECT URI, Domain FROM eZStats_RefererURL WHERE ID=" . $element[$db->fieldName("RefererURLID")] );
    $refername = $request[0][$db->fieldName("URI")];
    $domain = $request[0][$db->fieldName("Domain")];
    $date = new eZDateTime();
    $date->setTimeStamp( $element[$db->fieldName("Date")] );
    $month = new eZDateTime( $date->year(), $date->month() );
    $db->array_query( $oldelements, "SELECT * FROM eZStats_Archive_RefererURL WHERE URI='$refername' AND Domain='$domain' AND Month='". $month->timeStamp() . "'" );
    
    if ( count( $oldelements ) == 0 )
    {
        $db->lock( "eZStats_Archive_RefererURL" );
        $nextid = $db->nextID( "eZStats_Archive_RefererURL", "ID" );
        $res[] = $db->query( "INSERT INTO eZStats_Archive_RefererURL (ID, URI, Domain, Month, Count) VALUES ('$nextid', '$requestname', '$domain', '" . $month->timeStamp() . "', '1')" );
        $db->unlock();
    }
    else
    {
        $count = $oldelements[0][$db->fieldName("Count")] + 1;
        $res[] = $db->query( "UPDATE eZStats_Archive_RefererURL SET Count='$count' WHERE URI='$requestname' AND Domain='$domain' AND Month='" . $month->timeStamp() . "'" );
    }
}

// RemoteHost archive

$db->array_query( $newelements, "SELECT RemoteHostID FROM eZStats_PageView WHERE Date < " . $timestamp );

foreach ( $newelements as $element )
{
    $remote = array();
    $db->array_query( $remote, "SELECT IP, HostName FROM eZStats_Archive_RemoteHost WHERE ID=" . $element[$db->fieldName("RemoteHostID")] );
    $remotename = $remote[0][$db->fieldName("IP")];
    $hostname = $remote[0][$db->fieldName("HostName")];
    $db->array_query( $oldelements, "SELECT * FROM eZStats_Archive_RemoteHost WHERE IP='$remotename' AND HostName='$hostname'" );
    
    if ( count( $oldelements ) == 0 )
    {
        $db->lock( "eZStats_Archive_RemoteHost" );
        $nextid = $db->nextID( "eZStats_Archive_RemoteHost", "ID" );
        $res[] = $db->query( "INSERT INTO eZStats_Archive_RemoteHost (ID, IP, HostName, Count) VALUES ('$nextid', '$remotename', '$hostname', '1')" );
        $db->unlock();
    }
    else
    {
        $count = $oldelements[0][$db->fieldName("Count")] + 1;
        $res[] = $db->query( "UPDATE eZStats_Archive_RemoteHost SET Count='$count' WHERE IP='$remotename' AND HostName='$hostname'" );
    }
}

// Users archive

$db->array_query( $newelements, "SELECT Date, UserID FROM eZStats_PageView WHERE Date < " . $timestamp . " ORDER BY Date" );

foreach ( $newelements as $element )
{
    $user = $element[$db->fieldName("UserID")];
    $date = new eZDateTime();
    $date->setTimeStamp( $element[$db->fieldName("Date")] );
    $month = new eZDateTime( $date->year(), $date->month() );
    $db->array_query( $oldelements, "SELECT * FROM eZStats_Archive_Users WHERE UserID='$user' AND Month='". $month->timeStamp() . "'" );
    
    if ( count( $oldelements ) == 0 )
    {
        $db->lock( "eZStats_Archive_Users" );
        $nextid = $db->nextID( "eZStats_Archive_Users", "ID" );
        $res[] = $db->query( "INSERT INTO eZStats_Archive_Users (ID, UserID, Month, Count) VALUES ('$nextid', '$user', '" . $month->timeStamp() . "', '1')" );
        $db->unlock();
    }
    else
    {
        $count = $oldelements[0][$db->fieldName("Count")] + 1;
        $res[] = $db->query( "UPDATE eZStats_Archive_Users SET Count='$count' WHERE UserID='$user' AND Month='" . $month->timeStamp() . "'" );
    }
}

/* UniqueVisits archive

$db->query( $newelements, "SELECT Date, RemoteHostID FROM eZStats_PageView WHERE Date < " . $timestamp . " ORDER BY Date" );


foreach ( $newelements as $element )
{
    $date = new eZDateTime();
    $date->setTimeStamp( $element[$db->fieldName("Date")] );
    $day = new eZDateTime( $date->year(), $date->month(), $date->day() );
    $db->query( $oldelements, "SELECT * FROM eZStats_Archive_UniqueVisits WHERE Day='" . $day->timeStamp() . "'" );
    
    if ( count( $oldelements ) == 0 )
    {
        $db->lock( "eZStats_Archive_UniqueVisits" );
        $nextid = $db->nextID( "eZStats_Archive_UniqueVisits", "ID" );
        $res[] = $db->query( "INSERT INTO eZStats_Archive_UniqueVisits (ID, Day, Count) VALUES ('$nextid', '$day->timeStamp()', '1')" );
        $db->unlock();
    }
    else
    {
        $count = $oldelements[0][$db->fieldName("Count")] + 1;
        $res[] = $db->query( "UPDATE eZStats_Archive_UniqueVisits SET Count='$count' WHERE Day='" . $day->timeStamp() . "'" );
    }
}
*/

// PageView archive

$db->array_query( $newelements, "SELECT Date FROM eZStats_PageView WHERE Date < " . $timestamp . " ORDER BY Date" );

foreach ( $newelements as $element )
{
    $date = new eZDateTime();
    $date->setTimeStamp( $element[$db->fieldName("Date")] );
    $hour = new eZDateTime( $date->year(), $date->month(), $date->day(), $date->hour() );
    $db->array_query( $oldelements, "SELECT * FROM eZStats_Archive_PageView WHERE Hour='" . $hour->timeStamp() . "'" );
    
    if ( count( $oldelements ) == 0 )
    {
        $db->lock( "eZStats_Archive_PageView" );
        $nextid = $db->nextID( "eZStats_Archive_PageView", "ID" );
        $res[] = $db->query( "INSERT INTO eZStats_Archive_PageView (ID, Hour, Count) VALUES ('$nextid', '" . $hour->timeStamp() . "', '1')" );
        $db->unlock();
    }
    else
    {
        $count = $oldelements[0][$db->fieldName("Count")] + 1;
        $res[] = $db->query( "UPDATE eZStats_Archive_PageView SET Count='$count' WHERE Hour='" . $hour->timeStamp() . "'" );
    }
}

$res[] = $db->query( "DELETE FROM eZStats_PageView WHERE Date < " . $timestamp );

if ( in_array( false, $res ) )
    $db->rollback();
else
    $db->commit();


?>
