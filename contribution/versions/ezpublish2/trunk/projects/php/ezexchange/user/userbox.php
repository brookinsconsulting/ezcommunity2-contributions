<?php
// 
// $Id: userbox.php,v 1.1 2001/02/06 13:26:39 jb Exp $
//
// Jan Borsodi <jb@ez.no>
// Created on: <06-Feb-2001 12:52:18 amos>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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
$ini =& $GLOBALS["GlobalSiteIni"];

$type_list = array( array( "url" => "exchange/customer/new", "text" => "{intl-new_customer}" ),
                    array( "url" => "exchange/supplier/new", "text" => "{intl-new_supplier}" ) );
$IntlDir = array( "ezexchange/user/intl" );
$IniFile = array( "userbox.php" );

$template_array = array( "exchange_user_tpl" =>
                         array( "ezexchange/user/" . $ini->read_var( "eZExchangeMain", "TemplateDir" ),
                                "userbox.tpl" ) );
$block_array = array( "extra_userbox" => "exchange_user_tpl" );

include( "ezuser/user/userbox.php" );

?>
