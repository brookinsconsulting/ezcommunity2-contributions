<?
// 
// $Id: menubox.php,v 1.3 2001/01/23 17:45:43 jb Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <23-Oct-2000 17:53:46 bf>
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

// Supply $menuItems to get a menubox

$menuItems = array(
    array( "/contact/company/list/", "{intl-companytypelist}" ),
    array( "/contact/person/list/", "{intl-personlist}" ),
    array( "/contact/consultation/list/", "{intl-consultationlist}" ),
    array( "/contact/company/new/", "{intl-companyadd}" ),
    array( "/contact/person/new/", "{intl-personadd}" ),
    array( "/contact/companycategory/new/", "{intl-companytypeadd}" ),
    array( "/contact/consultation/new/", "{intl-newconsultation}" ),
    "break",
    array( "/contact/phonetype/list/", "{intl-phonetypelist}" ),
    array( "/contact/addresstype/list/", "{intl-addresstypelist}" ),
    array( "/contact/onlinetype/list/", "{intl-onlinetypelist}" ),
    array( "/contact/consultationtype/list/", "{intl-consultationtypelist}" ),
    array( "/contact/projecttype/list/", "{intl-projecttypelist}" ),
    array( "/contact/country/list/", "{intl-countrylist}" ),
    array( "/contact/phonetype/new/", "{intl-phonetypeadd}" ),
    array( "/contact/addresstype/new/", "{intl-addresstypeadd}" ),
    array( "/contact/onlinetype/new/", "{intl-onlinetypeadd}" ),
    array( "/contact/consultationtype/new/", "{intl-newconsultationtype}" ),
    array( "/contact/projecttype/new/", "{intl-newprojecttype}" ),
    array( "/contact/country/new/", "{intl-newcountry}" )
    );

?>
