<?
// 
// $Id: customerlogin.php,v 1.13 2001/03/26 17:00:49 ce Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <03-Oct-2000 16:45:30 bf>
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
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezuser/classes/ezuser.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZTradeMain", "Language" );

include_once( "ezuser/classes/ezuser.php" );


$user = eZUser::currentUser();
if ( $user  )
{
    if ( isset( $RedirectURL ) && ( $RedirectURL != "" ) )
    {
        eZHTTPTool::header( "Location: $RedirectURL" );
        exit();
    }
        
    if ( count( $user->addresses() ) == 0 )
    {
        $userID = $user->id();
        eZHTTPTool::header( "Location: /user/userwithaddress/edit/$userID/MissingAddress" );
//        Header( "Location: /user/address/new/?RedirectURL=/trade/customerlogin/" );
        exit();

    }
    elseif ( count ( $user->addresses() ) > 0 )
    {
        $addresses =& $user->addresses();

        $countryError = false;
        foreach ( $addresses as $address )
        {
            $country =& $address->country();
            if ( ( get_class( $country ) == "ezcountry" ) && ( $country->id() == 0 ) )
                $countryError = true;
        }

        if ( $countryError )
        {
            $userID = $user->id();
            eZHTTPTool::header( "Location: /user/userwithaddress/edit/$userID/MissingCountry" );
            exit();
        }
    }

    eZHTTPTool::header( "Location: /trade/checkout/" );
    exit();
}
else
{
    $t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                         "eztrade/user/intl/", $Language, "customerlogin.php" );

    $t->setAllStrings();

    $t->set_file( array(        
        "customer_login_tpl" => "customerlogin.tpl"
        ) );

    if ( isset( $RedirectURL ) && ( $RedirectURL != "" ) )
    {         
        $t->set_var( "redirect_url", $RedirectURL );
    }
    else
    {
        $t->set_var( "redirect_url", "/trade/customerlogin/" );
    }
    
    $t->pparse( "output", "customer_login_tpl" );
}

?>
