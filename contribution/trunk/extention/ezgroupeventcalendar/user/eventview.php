<?
// 
// $Id: eventview.php,v 1.10 2001/02/01 11:25:55 gl Exp $
//
// Adam Fallert <FallertA@umsystem.edu>
// Created on: <3-Oct-2001 14:36:00>
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
include_once( "classes/ezlog.php" );
include_once( "classes/ezdatetime.php" );
include_once( "classes/ezdate.php" );
include_once( "classes/eztime.php" );
include_once( "classes/ezlocale.php" );

include_once( "ezuser/classes/ezpermission.php" );

include_once( "ezgroupeventcalendar/classes/ezgroupevent.php" );
include_once( "ezgroupeventcalendar/classes/ezgroupeventtype.php" );
include_once( "ezgroupeventcalendar/classes/ezgroupeditor.php" );

include_once( "ezgroupeventcalendar/classes/ezgroupeventcategory.php" );

include_once( "ezforum/classes/ezforum.php" );
include_once( "ezforum/classes/ezforumcategory.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$SiteDesign = $ini->read_var( "site", "SiteDesign" );
$Language = $ini->read_var( "eZGroupEventCalendarMain", "Language" );
$locale = new eZLocale( $Language );

$t = new eZTemplate( "ezgroupeventcalendar/user/" . $ini->read_var( "eZGroupEventCalendarMain", "TemplateDir" ),
                     "ezgroupeventcalendar/user/intl/", $Language, "eventview.php" );

$t->set_file( "event_view_tpl", "eventview.tpl" );

$t->setAllStrings();

$t->set_block( "event_view_tpl", "error_tpl", "error" );
$t->set_block( "event_view_tpl", "view_tpl", "view" );
$t->set_block( "event_view_tpl", "group_name_print_tpl", "group_name_print" );
$t->set_block( "event_view_tpl", "group_event_print_tpl", "group_event_print" );
$t->set_block( "view_tpl", "public_tpl", "public" );
$t->set_block( "view_tpl", "private_tpl", "private" );

$t->set_block( "view_tpl", "lowest_tpl", "lowest" );
$t->set_block( "view_tpl", "low_tpl", "low" );
$t->set_block( "view_tpl", "normal_tpl", "normal" );
$t->set_block( "view_tpl", "medium_tpl", "medium" );
$t->set_block( "view_tpl", "high_tpl", "high" );
$t->set_block( "view_tpl", "highest_tpl", "highest" );

$t->set_block( "view_tpl", "tentative_tpl", "tentative" );
$t->set_block( "view_tpl", "confirmed_tpl", "confirmed" );
$t->set_block( "view_tpl", "cancelled_tpl", "cancelled" );

$t->set_block( "view_tpl", "valid_editor_tpl", "valid_editor" );

$t->set_block( "view_tpl", "attached_file_list_tpl", "attached_file_list" );
$t->set_block( "attached_file_list_tpl", "attached_file_tpl", "attached_file" );

$t->set_var( "sitedesign", $SiteDesign );

$user = eZUser::currentUser();
$session =& eZSession::globalSession();
$session->fetch();

$tmpGroup = new eZUserGroup( $session->variable( "ShowOtherCalenderGroups" ) );

// k: die ("test g:" . $session->variable( "ShowOtherCalenderGroups") );

$groupsList   = "-1";
$showPrivate  = false;
$editor       = false;

// Determin the users group membership and access
if( $user )
{
	// if the user has root access, then the user has access to all groups
	if( $user->hasRootAccess() == true || eZPermission::checkPermission( $user, "eZGroupEventCalendar", "WriteToRoot" ) )
	{
		$groups = new eZUserGroup();
		$groupsList = $groups->getAll( true );
		$rootAccess = true;
	}
	else
	{
		$groupsList = $user->groups();
	}
}


$t->set_var( "group_event_print", "" );

// find the event and determin the permissions
$event = new eZGroupEvent($EventID);

if( $event )
{
	$editor      = false;
	$groupMember = false;

	// if a groupsList exists then it is assumed that a user is loged in
	if( $groupsList != "-1" )
	{
		$permission = new eZGroupEditor();

		// Determin if has edit permissions, if so, then they are a group member
		if( $permission->hasEditPermission( $user->id(), $event->groupID() ) == true || $rootAccess == true )
		{
			$editor      = true;
			$groupMember = true;
		}
		else
		{
			foreach( $groupsList as $groups )
			{
				if( $permission->groupHasEditor( $groups->id() ) == false && $groups->id() == $event->groupID() )
				{
					$editor      = true;
					$groupMember = true;
					break;
				}
				elseif( $groups->id() == $event->groupID() )
				{
					$groupMember = true;
					break;
				}
			}
		}
	}

	// does the user have privilleges to view the event or not
    if ( $event->isPrivate() == true && $groupMember != true && !eZPermission::checkPermission( $user, "eZGroupEventCalendar", "Read" ) )
		$showEvent = false;
	elseif( $event->isPrivate() == false || ( $event->isPrivate() == true && eZPermission::checkPermission( $user, "eZGroupEventCalendar", "Read" ) ) )
		$showEvent = true;


	// Determine editing permissions
		
	if( $session->variable( "ShowOtherCalenderGroups" ) != 0 && $tmpGroup->id() == $event->groupID() )
	{
		$t->set_var( "group_print_id", $tmpGroup->id() );
		$t->set_var( "group_print_name", $tmpGroup->name() );
		$t->parse( "group_name_print", "group_name_print_tpl" );
	}
	else
		$t->set_var( "group_name_print", "" );
}


// print the correct display based on the above proccesses
if ( $showEvent == false )
{
	$error = true;
	$t->set_var( "event_title", "" );
	$t->set_var( "view", "" );
      $t->parse( "error", "error_tpl" );
}
else
{
    $event = new eZGroupEvent( $EventID );
    $eventType = $event->type();
    $eventCategory = $event->category();

    $datetime = $event->dateTime();

    $t->set_var( "event_id", $event->id() );
    $t->set_var( "event_title", $event->name() );

    $t->set_var( "event_url", $event->url() );
    $t->set_var( "event_location", $event->location() );
    $t->set_var( "event_category", $eventCategory->name() );

    $t->set_var( "event_type", $eventType->name() );
    $t->set_var( "event_date", $locale->format( $datetime->date() ) );
    $t->set_var( "event_starttime", $locale->format( $event->startTime(), true ) );
    $t->set_var( "event_stoptime", $locale->format( $event->stopTime(), true ) );
    $t->set_var( "event_description", $event->description(false) );

    $groupID = $event->groupID();

    /*
    if ( $groupID == false ) {
    die( " Group ID: " . $groupID );
    }
    */

    // kracker : seems the bellow statement treats groupID = 0 as false instead of a numarical 
    // if ( $groupID != false )

    if ( $groupID != "" )
    {
      // kracker : if event->groupID() == 0 then it's an all groups event. new
      if ( $groupID != 0 ){
        $group = new eZUserGroup( $groupID );
        $t->set_var( "event_owner", $group->name() );
      } else {
	$t->set_var( "event_owner", "All Groups" );
      }

    }
    else
        $t->set_var( "event_owner", "unknown user" );

    if ( $event->isPrivate() == true )
    {
        $t->parse( "private", "private_tpl" );
        $t->set_var( "public", "" );
    }
    else
    {
        $t->parse( "public", "public_tpl" );
        $t->set_var( "private", "" );
    }

    switch( $event->priority() )
    {
        case 0:
        {
            $t->parse( "lowest", "lowest_tpl" );

            $t->set_var( "low", "" );
            $t->set_var( "normal", "" );
            $t->set_var( "medium", "" );
            $t->set_var( "high", "" );
	    $t->set_var( "highest", "" );
        }
        break;
        case 1:
        {
	  $t->parse( "low", "low_tpl" );

	  $t->set_var( "lowest", "" );
	  $t->set_var( "normal", "" );
	  $t->set_var( "medium", "" );
	  $t->set_var( "high", "" );
	  $t->set_var( "highest", "" );

	}
	break;
        case 2:
        {
            $t->parse( "normal", "normal_tpl" );

            $t->set_var( "low", "" );
	    $t->set_var( "lowest", "" );
	    $t->set_var( "medium", "" );
	    $t->set_var( "high", "" );
	    $t->set_var( "highest", "" );

        }
        break;
        case 3:
        {
	  $t->parse( "medium", "medium_tpl" );

	  $t->set_var( "low", "" );
	  $t->set_var( "lowest", "" );
	  $t->set_var( "normal", "" );
	  $t->set_var( "high", "" );
	  $t->set_var( "highest", "" );
	}
        break;
        case 4:
        {
            $t->parse( "high", "high_tpl" );

	    $t->set_var( "low", "" );
	    $t->set_var( "lowest", "" );
	    $t->set_var( "normal", "" );
            $t->set_var( "medium", "" );
	    $t->set_var( "highest", "" );
        }
        break;
        case 5:
        {
            $t->parse( "highest", "high_tpl" );

            $t->set_var( "low", "" );
            $t->set_var( "lowest", "" );
            $t->set_var( "normal", "" );
            $t->set_var( "medium", "" );
            $t->set_var( "high", "" );

        }
        break;
    }

    switch( $event->status() )
    {
      case 0:
      {
	  $t->parse( "tentative", "tentative_tpl" );

	  $t->set_var( "confirmed", "" );
	  $t->set_var( "cancelled", "" );
      }
      break;
      case 1:
      {
          $t->parse( "confirmed", "confirmed_tpl" );

          $t->set_var( "tentative", "" );
          $t->set_var( "cancelled", "" );
      }
      break;
      case 2:
      {
	  $t->parse( "cancelled", "cancelled_tpl" );

	  $t->set_var( "confirmed", "" );
	  $t->set_var( "tentative", "" );
      }
      break;
    }


    // files
    $files = $event->files();

    if ( count( $files ) > 0 )
      {
	$i=0;
	foreach ( $files as $file )
	  {
	    if ( ( $i % 2 ) == 0 )
	      {
		$t->set_var( "td_class", "bglight" );
	      }
	    else
	      {
		$t->set_var( "td_class", "bgdark" );
	      }

	    $t->set_var( "file_id", $file->id() );
	    $t->set_var( "original_file_name", $file->fileName() );
	    $t->set_var( "file_name", $file->name() );
	    $t->set_var( "file_url", $file->name() );
	    $t->set_var( "file_description", $file->description() );

	    $size = $file->siFileSize();
	    $t->set_var( "file_size", $size["size-string"] );
	    $t->set_var( "file_unit", $size["unit"] );

	    $i++;
	    $t->parse( "attached_file", "attached_file_tpl", true );
	  }

	$t->parse( "attached_file_list", "attached_file_list_tpl" );
      }
    else
      $t->set_var( "attached_file_list", "" );



        $t->set_var( "error", "" );
	$t->parse( "group_event_print", "group_event_print_tpl" );

	//print or suppress the edit event button
	if( $editor == true )
		$t->parse( "valid_editor", "valid_editor_tpl", true );
	else
		$t->set_var( "valid_editor", "" );

	// suppress the event view template if error is encountered
	if ( $error == true )
		$t->set_var( "view", "" );
        else
		$t->parse( "view", "view_tpl" );
}

$t->pparse( "output", "event_view_tpl" );

?>
