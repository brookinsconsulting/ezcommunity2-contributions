<?
// 
// $Id: sectionedit.php,v 1.2 2001/06/25 14:40:09 bf Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <10-May-2001 16:17:29 ce>
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

include_once( "ezsitemanager/classes/ezsection.php" );

if ( isSet ( $OK ) )
{
    $Action = "Insert";
}
if ( isSet ( $Delete ) )
{
    $Action = "Delete";
}
if ( isSet ( $Cancel ) )
{
    eZHTTPTool::header( "Location: /sitemanager/section/list/" );
    exit();
}

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZSiteManagerMain", "Language" );

$t = new eZTemplate( "ezsitemanager/admin/" . $ini->read_var( "eZSiteManagerMain", "AdminTemplateDir" ),
                     "ezsitemanager/admin/" . "/intl", $Language, "sectionedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "section_edit_page" => "sectionedit.tpl"
      ) );

$t->set_var( "section_name", "$Name" );
$t->set_var( "section_sitedesign", "$SiteDesign" );
$t->set_var( "section_description", "$Description" );

$warning = true;

if ( ( $Action == "Insert" ) || ( $Action == "Update" ) && ( $user ) )
{
    $Name = strtolower( $Name );
    if ( $warning )
    {
        if ( is_dir( "sitedesign/" . $Name ) == false );
        {
            $session =& eZSession::globalSession();            
            $session->setVariable( "DirNotExists", "true" );
        }
    }
    
    if ( is_numeric( $SectionID ) )
        $section = new eZSection( $SectionID);
    else
        $section = new eZSection();
    $section->setName( $Name );
    $section->setSiteDesign( $SiteDesign );
    $section->setDescription( $Description );
    $section->store();

    eZHTTPTool::header( "Location: /sitemanager/section/list/" );
    exit();
}

if ( $Action == "Delete" )
{
    print( "her" );
    if ( count ( $SectionArrayID ) > 0 )
    {
        foreach( $SectionArrayID as $SectionID )
        {
            $section = new eZSection( $SectionID );
            $section->delete();
        }
    }
    eZHTTPTool::header( "Location: /sitemanager/section/list/" );
    exit();
}

if ( is_numeric( $SectionID ) )
{
    $section = new eZSection( $SectionID );
    $t->set_var( "section_id", $section->id() );
    $t->set_var( "section_name", $section->name() );
    $t->set_var( "section_description", $section->description() );
    $t->set_var( "section_sitedesign", $section->siteDesign() );
}

$t->pparse( "output", "section_edit_page" );
?>
