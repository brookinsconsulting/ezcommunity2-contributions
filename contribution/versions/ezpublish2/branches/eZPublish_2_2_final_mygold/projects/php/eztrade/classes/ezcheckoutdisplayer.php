<?php
//
// $Id: ezcheckoutdisplayer.php,v 1.1.2.1 2002/06/10 08:43:42 ce Exp $
//
// Definition of ||| class
//
// <real-name> <<mail-name>>
// Created on: <10-Dec-2001 12:36:41 ce>
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

//!!
//! The class ||| does
/*!

*/

include_once( "eztrade/classes/ezcheckout.php" );
include_once( "eztrade/classes/ezvoucher.php" );
include_once( "eztrade/classes/ezpackingtype.php" );

class eZCheckoutDisplayer
{
    function eZCheckoutDisplayer( &$template, &$cart )
    {
        $ini =& INIFile::globalINI();
        $this->PricesIncludeVAT_ = $ini->read_var( "eZTradeMain", "PricesIncludeVAT" ) == "enabled" ? true : false;
        $this->ShowExTaxColumn_ = $ini->read_var( "eZTradeMain", "ShowExTaxColumn" ) == "enabled" ? true : false;
        $this->ShowIncTaxColumn_ = $ini->read_var( "eZTradeMain", "ShowIncTaxColumn" ) == "enabled" ? true : false;
        $this->ShowExTaxTotal_ = $ini->read_var( "eZTradeMain", "ShowExTaxTotal" ) == "enabled" ? true : false;
        $this->ColSpanSizeTotals = $ini->read_var( "eZTradeMain", "ColSpanSizeTotals" );
        $this->ShowCart_ = false;

        $this->Template =& $template;
        $this->Cart =& $cart;
    }

    /*!
Display the addresses.
    */
    function displayAddresses(  )
    {
        $ini =& INIFile::globalINI();
        $user =& eZUser::currentUser();

        if ( $this->Cart->personID() == 0 && $this->Cart->companyID() == 0 )
        {
            $this->Template->set_var( "customer_first_name", $user->firstName() );
            $this->Template->set_var( "customer_last_name", $user->lastName() );

            $addressArray = $user->addresses();
        }
        else
        {
            if ( $this->Cart->personID() > 0 )
            {
                $customer = new eZPerson( $this->Cart->personID() );
                $this->Template->set_var( "customer_first_name", $customer->firstName() );
                $this->Template->set_var( "customer_last_name", $customer->lastName() );
            }
            else
            {
                $customer = new eZCompany( $this->Cart->companyID() );
                $this->Template->set_var( "customer_first_name", $customer->name() );
                $this->Template->set_var( "customer_last_name", "" );
            }

            $addressArray = $customer->addresses();
        }

        foreach ( $addressArray as $address )
        {
            $this->Template->set_var( "address_id", $address->id() );
            $this->Template->set_var( "street1", $address->street1() );
            $this->Template->set_var( "street2", $address->street2() );
            $this->Template->set_var( "zip", $address->zip() );
            $this->Template->set_var( "place", $address->place() );

            $country = $address->country();

            if ( $country )
            {
                $country = ", " . $country->name();
            }

            if ( $ini->read_var( "eZUserMain", "SelectCountry" ) == "enabled" )
                $this->Template->set_var( "country", $country );
            else
                $this->Template->set_var( "country", "" );

            unset( $mainAddress );
            $this->Template->set_var( "is_selected", "" );
            $mainAddress = $address->mainAddress( $user );

            if ( get_class( $mainAddress ) == "ezaddress" )
            {
                if ( $mainAddress->id() == $address->id() )
                {
                    $this->Template->set_var( "is_selected", "selected" );
                }
            }

            if ( $ini->read_var( "eZTradeMain", "ShowBillingAddress" ) == "enabled" )
                $this->Template->parse( "billing_option", "billing_option_tpl", true );
            else
                $this->Template->set_var( "billing_option" );

            $this->Template->parse( "shipping_address", "shipping_address_tpl", true );
        }

        $this->Template->set_var( "wish_user", "" );

        if ( $ini->read_var( "eZTradeMain", "ShowBillingAddress" ) == "enabled" )
            $this->Template->parse( "billing_address", "billing_address_tpl", true );
        else
            $this->Template->set_var( "billing_address" );
    }

    function displayPaymentMethods( &$total )
    {
        if ( $total["inctax"] )
        {
            $checkout = new eZCheckout();
            $instance =& $checkout->instance();
            $paymentMethods =& $instance->paymentMethods( $this->UseVoucher_ );

            foreach ( $paymentMethods as $paymentMethod )
            {
                $this->Template->set_var( "payment_method_id", $paymentMethod["ID"] );
                $this->Template->set_var( "payment_method_text", $paymentMethod["Text"] );

                $this->Template->parse( "payment_method", "payment_method_tpl", true );
            }
            $this->Template->parse( "show_payment", "show_payment_tpl" );
        }
        else
        {
            $this->Template->set_var( "show_paymeny", "" );
        }
        $this->Template->set_var( "sendorder_item", "" );
    }

    function displayPacking( )
    {
        $packingList =& eZPackingType::getAll();

        if ( count ( $packingList ) > 0 )
        {
            $i = 0;
            foreach ( $packingList as $packing )
            {
                $this->Template->set_var( "td_class", ( $i % 2 ) == 0 ? "bglight" : "bgdark" );
                $this->Template->set_var( "id", $packing->id() );
                $this->Template->set_var( "packing_name", $packing->name() );
                $this->Template->set_var( "packing_description", $packing->description() );
                $this->Template->set_var( "packing_price", $packing->price() );

                $image = $packing->image();
                if ( $image )
                {
                    $variation =& $image->requestImageVariation( 100, 100 );
                    $this->Template->set_var( "thumbnail_image_uri", "/" . $variation->imagePath() );
                    $this->Template->set_var( "thumbnail_image_width", $variation->width() );
                    $this->Template->set_var( "thumbnail_image_height", $variation->height() );
                    $this->Template->set_var( "thumbnail_image_caption", $image->caption() );

                    $this->Template->set_var( "no_image", "" );
                    $this->Template->parse( "image", "image_tpl" );
                }
                else
                {
                    $this->Template->set_var( "image", "" );
                    $this->Template->parse( "no_image", "no_image_tpl" );
                }

                $i++;
                $this->Template->parse( "packing_item", "packing_item_tpl", true );
            }
            $this->Template->parse( "packing_list", "packing_list_tpl" );
        }
        else
        {
            $this->Template->set_var( "packing_list", "" );
        }

    }

    function displayShipping()
    {
        $type = new eZShippingType();
        $types =& $type->getAll();

        $currentTypeID = eZHTTPTool::getVar( "ShippingTypeID" );

        $currentShippingType = false;
        foreach ( $types as $type )
        {
            $this->Template->set_var( "shipping_type_id", $type->id() );
            $this->Template->set_var( "shipping_type_name", $type->name() );

            if ( is_numeric( $currentTypeID ) )
            {
                if ( $currentTypeID == $type->id() )
                {
                    $currentShippingType = $type;
                    $this->Template->set_var( "type_selected", "selected" );
                }
                else
                    $this->Template->set_var( "type_selected", "" );
            }
            else
            {
                if ( $type->isDefault() )
                {
                    $currentShippingType = $type;
                    $this->Template->set_var( "type_selected", "selected" );
                }
                else
                    $this->Template->set_var( "type_selected", "" );
            }

            $this->Template->parse( "shipping_type", "shipping_type_tpl", true );
        }

    }

    function displayItems()
    {
        $this->SavingsColumn_ = false;
        $ini =& INIFile::globalINI();

        $items =& $this->Cart->items( );

        foreach ( $items as $item )
        {
            if ( $item->correctSavings( false, true, $this->PricesIncludeVAT_ ) > 0 )
            {
                $this->ShowSavingsColumn_ = true;
            }
        }

        $numberOfItems = 0;
        foreach ( $items as $item )
        {
            $this->Template->set_var( "td_class", ( $i % 2 ) == 0 ? "bglight" : "bgdark" );
            $i++;
            $this->Template->set_var( "cart_item_id", $item->id() );
            $product =& $item->product();

            $this->Template->set_var( "product_id", $product->id() );
            $this->Template->set_var( "product_name", $product->name() );
            $this->Template->set_var( "product_number", $product->productNumber() );
            $this->Template->set_var( "product_price", $item->localePrice( false, true, $this->PricesIncludeVAT_ ) );
            $this->Template->set_var( "product_count", $item->count() );
            $this->Template->set_var( "product_total_ex_tax", $item->localePrice( true, true, false ) );
            $this->Template->set_var( "product_total_inc_tax", $item->localePrice( true, true, true ) );

            $numberOfItems++;

            $numberOfOptions = 0;

            $optionValues =& $item->optionValues();

            $this->Template->set_var( "cart_item_option", "" );
            $this->Template->set_var( "cart_item_basis", "" );

            if ( $product->productType() == 2 )
                $this->UseVoucher_ = true;
            else
                $this->UseVoucher_ = false;

            foreach ( $optionValues as $optionValue )
            {
                $this->turnColumnsOnOff( "option" );

                $option =& $optionValue->option();
                $value =& $optionValue->optionValue();
                $value_quantity = $value->totalQuantity();
                $descriptions = $value->descriptions();

                $this->Template->set_var( "option_id", $option->id() );
                $this->Template->set_var( "option_name", $option->name() );
                $this->Template->set_var( "option_value", $descriptions[0] );
                $this->Template->set_var( "option_price", $value->localePrice( $this->PricesIncludeVAT_, $product ) );
                $this->Template->parse( "cart_item_option", "cart_item_option_tpl", true );

                $numberOfOptions++;
            }
            $this->turnColumnsOnOff( "cart" );
            $this->turnColumnsOnOff( "basis" );

            if ( $this->ShowSavingsColumn_ == true )
            {
                if ( $item->correctSavings( true, true, $this->PricesIncludeVAT_ ) > 0 )
                {
                    $this->Template->set_var( "product_savings", $item->localeSavings( true, true, $this->PricesIncludeVAT_ ) );
                }
                else
                {
                    $this->Template->set_var( "product_savings", "&nbsp;" );
                }
                $this->Template->parse( "cart_savings_item", "cart_savings_item_tpl" );
            }
            else
            {
                $this->Template->set_var( "cart_savings_item", "" );
            }

            if ( $numberOfOptions ==  0 )
            {
                $this->Template->set_var( "cart_item_option", "" );
                $this->Template->set_var( "cart_item_basis", "" );
            }
            else
            {
                if( $product->price() > 0 )
                {
                    $this->Template->set_var( "basis_price", $item->localePrice( false, false, $this->PricesIncludeVAT_ ) );
                    $this->Template->parse( "cart_item_basis", "cart_item_basis_tpl", true );
                }
                else
                {
                    $this->Template->set_var( "cart_item_basis", "" );
                }
            }
            $this->Template->parse( "cart_item", "cart_item_tpl", true );
        }

        if ( $numberOfItems > 0 )
        {
            $this->ShowCart_ = true;
        }

        $this->Template->setAllStrings();

        $this->turnColumnsOnOff( "header" );
    }

    function displayCart( &$total )
    {
        if ( $this->ShowCart_ == true )
        {
            $session =& eZSession::globalSession();
            $locale = new eZLocale( $Language );
            $currency = new eZCurrency();

            $checkout = new eZCheckout();
            $instance =& $checkout->instance();

            $this->Template->set_var( "empty_cart", "" );
            $this->Template->set_var( "voucher_item", "" );

            $currency->setValue( $total["subinctax"] );
            $this->Template->set_var( "subtotal_inc_tax", $locale->format( $currency ) );

            $currency->setValue( $total["subextax"] );
            $this->Template->set_var( "subtotal_ex_tax", $locale->format( $currency ) );

            $currency->setValue( $total["inctax"] );
            $this->Template->set_var( "total_inc_tax", $locale->format( $currency ) );

            $currency->setValue( $total["extax"] );
            $this->Template->set_var( "total_ex_tax", $locale->format( $currency ) );

            $currency->setValue( $total["shipinctax"] );
            $this->Template->set_var( "shipping_inc_tax", $locale->format( $currency ) );

            $currency->setValue( $total["shipextax"] );
            $this->Template->set_var( "shipping_ex_tax", $locale->format( $currency ) );

            if ( $this->ShowSavingsColumn_ == false )
            {
                $this->ColSpanSizeTotals--;
            }

            $SubTotalsColumns = $this->ColSpanSizeTotals;

            if ( $this->ShowExTaxColumn_ == true )
            {
                if ( $this->ShowExTaxTotal_ == true or $this->ShowIncTaxColumn_ == false )
                {
                    $this->Template->parse( "total_ex_tax_item", "total_ex_tax_item_tpl" );
                    $this->Template->parse( "subtotal_ex_tax_item", "subtotal_ex_tax_item_tpl" );
                    $this->Template->parse( "shipping_ex_tax_item", "shipping_ex_tax_item_tpl" );
                }
                else
                {
                    $this->Template->set_var( "total_ex_tax_item", "" );
                    $this->Template->set_var( "subtotal_ex_tax_item", "" );
                    $this->Template->set_var( "shipping_ex_tax_item", "" );
                }
            }
            else
            {
                $this->ColSpanSizeTotals--;
                $this->Template->set_var( "total_ex_tax_item", "" );
                $this->Template->set_var( "subtotal_ex_tax_item", "" );
                $this->Template->set_var( "shipping_ex_tax_item", "" );
            }

            if ( $this->ShowIncTaxColumn_ == true  )
            {
                $this->Template->parse( "total_inc_tax_item", "total_inc_tax_item_tpl" );
                $this->Template->parse( "subtotal_inc_tax_item", "subtotal_inc_tax_item_tpl" );
                $this->Template->parse( "shipping_inc_tax_item", "shipping_inc_tax_item_tpl" );
            }
            else
            {
                $this->ColSpanSizeTotals--;
                $this->Template->set_var( "total_inc_tax_item", "" );
                $this->Template->set_var( "subtotal_inc_tax_item", "" );
                $this->Template->set_var( "shipping_inc_tax_item", "" );
            }

            if ( $this->ShowIncTaxColumn_ and $this->ShowExTaxColumn_ and $this->ShowExTaxTotal_ )
            {
                $this->Template->set_var( "subtotals_span_size", $SubTotalsColumns - 1 );
            }
            else
            {
                $this->Template->set_var( "subtotals_span_size", $this->ColSpanSizeTotals  );
            }


            $vouchers = $session->arrayValue( "PayWithVoucher" );
            if ( count ( $vouchers ) > 0 )
            {
                $this->Template->set_var( "vouchers", "" );
                $this->Template->set_var( "voucher_item", "" );
                $i=1;
                $continue = true;
                $number = 1;

                foreach( $vouchers as $voucherID )
                {
                    if ( $continue )
                    {
                        $voucher = new eZVoucher( $voucherID );

                        $voucherID = $voucher->id();

                        $voucherPrice = $voucher->price();

                        $this->Cart->cartTotals( $tax, $voucherPrice, $voucher );

                        if ( $voucherPrice["inctax"] > $total["inctax"] )
                        {
                            $subtractIncVAT["inctax"] = $total["inctax"];
                            $currency->setValue( $total["inctax"] );
                            $this->Template->set_var( "voucher_price_inc_vat", $locale->format( $currency ) );

                            $subtractExTax["extax"] = $total["extax"];
                            $currency->setValue( $total["extax"] );
                            $this->Template->set_var( "voucher_price_ex_vat", $locale->format( $currency ) );
                            $continue = false;
                        }
                        else
                        {
                            $subtractIncVAT["inctax"] = $voucherPrice["inctax"];
                            $currency->setValue( $voucherPrice["inctax"] );
                            $this->Template->set_var( "voucher_price_inc_vat", $locale->format( $currency ) );

                            $subtractExTax["extax"] = $voucherPrice["extax"];
                            $currency->setValue( $voucherPrice["extax"] );
                            $this->Template->set_var( "voucher_price_ex_vat", $locale->format( $currency ) );
                        }

                        $voucherSession[$voucherID] = $subtractIncVAT["inctax"];
                        $this->Template->set_var( "number", $i );
                        $this->Template->set_var( "voucher_id", $voucher->id() );

                        $this->Template->set_var( "voucher_key", $voucher->keyNumber() );
                        $this->Template->set_var( "pay_with_voucher", "true" );

                        $this->Template->parse( "voucher_item", "voucher_item_tpl", true );

                        $total["extax"] -= $subtractExTax["extax"];
                        $total["inctax"] -= $subtractIncVAT["inctax"];

                        $i++;
                    }
                }
                $this->turnColumnsOnOff( "voucher" );
            }

            if ( is_array ( $voucherSession ) )
            {
                $this->Template->parse( "vouchers", "vouchers_tpl" );
                $session->setArray( "PayedWith", $voucherSession );
            }

            $VerifyData = $session->arrayValue( "VerifyData" );

            if ( $VerifyData )
            {
                $name = $instance->paymentName( $verifyData["PaymentMethod"] );
                $this->Template->set_var( "payment_method_name", $name );
                $currency->setValue( $total["inctax"] );
                $this->Template->set_var( "payment_inc_tax", $locale->format( $currency ) );
                $total["inctax"] = 0;
                $this->turnColumnsOnOff( "payment" );
                $this->Template->parse( "payment_item", "payment_item_tpl" );
            }

            $this->Template->set_var( "totals_span_size", $this->ColSpanSizeTotals );
            $this->Template->parse( "cart_item_list", "cart_item_list_tpl" );
            $this->Template->parse( "full_cart", "full_cart_tpl" );

            if( $vat == true )
            {
                $currency->setValue( $total["tax"] );
                $this->Template->set_var( "tax", $locale->format( $currency ) );

                foreach( $tax as $taxGroup )
                {
                    $currency->setValue( $taxGroup["basis"] );
                    $this->Template->set_var( "sub_tax_basis", $locale->format( $currency ) );

                    $currency->setValue( $taxGroup["tax"] );
                    $this->Template->set_var( "sub_tax", $locale->format( $currency ) );

                    $this->Template->set_var( "sub_tax_percentage", $taxGroup["percentage"] );
                    $this->Template->parse( "tax_item", "tax_item_tpl", true );
                }
                $this->Template->parse( "tax_specification", "tax_specification_tpl" );
            }
            else
            {
                $this->Template->set_var( "tax_specification", "" );
                $this->Template->set_var( "tax_item", "" );
            }
        }
        else
        {
            $this->Template->parse( "empty_cart", "empty_cart_tpl" );
//            $this->Template->parse( "cart_checkout", "cart_checkout_tpl" );
            $this->Template->set_var( "cart_checkout_button", "" );
            $this->Template->set_var( "cart_item_list", "" );
            $this->Template->set_var( "full_cart", "" );
            $this->Template->set_var( "tax_specification", "" );
            $this->Template->set_var( "tax_item", "" );
        }

    }

    function turnColumnsOnOff( $rowName )
    {
        if ( $this->ShowSavingsColumn_ == true )
        {
            $this->Template->parse( $rowName . "_savings_item", $rowName . "_savings_item_tpl" );
        }
        else
        {
            $this->Template->set_var( $rowName . "_savings_item", "" );
        }

        if ( $this->ShowExTaxColumn_ == true )
        {
            $this->Template->parse( $rowName . "_ex_tax_item", $rowName . "_ex_tax_item_tpl" );
        }
        else
        {
            $this->Template->set_var( $rowName . "_ex_tax_item", "" );
        }

        if ( $this->ShowIncTaxColumn_ == true )
        {
            $this->Template->parse( $rowName . "_inc_tax_item", $rowName . "_inc_tax_item_tpl" );
        }
        else
        {
            $this->Template->set_var( $rowName . "_inc_tax_item", "" );
        }
    }

    function path( $parent )
    {
        $session =& eZSession::globalSession();
        $this->Template->set_block( $parent, "address_path_tpl", "address_path" );
        $this->Template->set_block( $parent, "shipping_path_tpl", "shipping_path" );
        $this->Template->set_block( $parent, "packing_path_tpl", "packing_path" );
        $this->Template->set_block( $parent, "payment_method_path_tpl", "payment_method_path" );
        $this->Template->set_block( $parent, "overview_path_tpl", "overview_path" );
        $this->Template->set_block( $parent, "payment_path_tpl", "payment_path" );
        $this->Template->set_block( $parent, "ordersent_path_tpl", "ordersent_path" );

        $this->Template->set_block( $parent, "address_dummy_path_tpl", "address_dummy_path" );
        $this->Template->set_block( $parent, "shipping_dummy_path_tpl", "shipping_dummy_path" );
        $this->Template->set_block( $parent, "packing_dummy_path_tpl", "packing_dummy_path" );
        $this->Template->set_block( $parent, "payment_method_dummy_path_tpl", "payment_method_dummy_path" );
        $this->Template->set_block( $parent, "overview_dummy_path_tpl", "overview_dummy_path" );
        $this->Template->set_block( $parent, "payment_dummy_path_tpl", "payment_dummy_path" );
        $this->Template->set_block( $parent, "ordersent_dummy_path_tpl", "ordersent_dummy_path" );

        if ( is_numeric ( $session->variable( "CurrentAddress" ) ) )
        {
            $this->Template->set_var( "address_dummy_path", "" );
            $this->Template->parse( "address_path", "address_path_tpl" );
        }
        else
        {
            $this->Template->parse( "address_dummy_path", "address_dummy_path_tpl" );
            $this->Template->set_var( "address_path", "" );
        }
        if ( is_numeric ( $session->variable( "CurrentShippingTypeID" ) ) )
        {
            $this->Template->set_var( "shipping_dummy_path", "" );
            $this->Template->parse( "shipping_path", "shipping_path_tpl" );
        }
        else
        {
            $this->Template->parse( "shipping_dummy_path", "shipping_dummy_path_tpl" );
            $this->Template->set_var( "shipping_path", "" );
        }
        if ( is_numeric ( $session->variable( "CurrentPackingID" ) ) )
        {
            $this->Template->set_var( "packing_dummy_path", "" );
            $this->Template->parse( "packing_path", "packing_path_tpl" );
        }
        else
        {
            $this->Template->parse( "packing_dummy_path", "packing_dummy_path_tpl" );
            $this->Template->set_var( "packing_path", "" );
        }
        if ( is_numeric ( $session->variable( "CurrentPaymentMethodID" ) ) )
        {
            $this->Template->set_var( "payment_method_dummy_path", "" );
            $this->Template->parse( "payment_method_path", "payment_method_path_tpl" );
        }
        else
        {
            $this->Template->parse( "payment_method_dummy_path", "payment_method_dummy_path_tpl" );
            $this->Template->set_var( "payment_method_path", "" );
        }
        if ( is_numeric ( $session->variable( "CurrentOverview" ) ) )
        {
            $this->Template->set_var( "overview_dummy_path", "" );
            $this->Template->parse( "overview_path", "overview_path_tpl" );
        }
        else
        {
            $this->Template->parse( "overview_dummy_path", "overview_dummy_path_tpl" );
            $this->Template->set_var( "overview_path", "" );
        }
        if ( is_numeric ( $session->variable( "CurrentPaymentID" ) ) )
        {
            $this->Template->set_var( "payment_dummy_path", "" );
            $this->Template->parse( "payment_path", "payment_path_tpl" );
        }
        else
        {
            $this->Template->parse( "payment_dummy_path", "payment_dummy_path_tpl" );
            $this->Template->set_var( "payment_path", "" );
        }
        if ( is_numeric ( $session->variable( "CurrentOrdersentID" ) ) )
        {
            $this->Template->set_var( "ordersent_dummy_path", "" );
            $this->Template->parse( "ordersent_path", "ordersent_path_tpl" );
        }
        else
        {
            $this->Template->parse( "ordersent_dummy_path", "ordersent_dummy_path_tpl" );
            $this->Template->set_var( "ordersent_path", "" );
        }
    }

    function displayPayments( &$total, $verifyData )
    {
        $session =& eZSession::globalSession();
        $locale = new eZLocale( $Language );
        $currency = new eZCurrency();
        $checkout = new eZCheckout();
        $instance =& $checkout->instance();

        $i = 1;
        $continue = true;
        $payedWithVoucher = $session->arrayValue( "PayWithVoucher" );
        $subtractIncVAT["inctax"] = 0;

        $currency->setValue( $total["inctax"] );
        $this->Template->set_var( "total_inc_tax", $locale->format( $currency ) );

        $currency->setValue( $total["extax"] );
        $this->Template->set_var( "total_ex_tax", $locale->format( $currency ) );

        $this->turnColumnsOnOff( "total" );
        $this->turnColumnsOnOff( "header" );

        foreach( $payedWithVoucher as $voucherID )
        {
            if ( $continue )
            {
                $deletePayments = true;
                $voucher = new eZVoucher( $voucherID );
                $voucherID = $voucher->id();
                $voucherPrice = $voucher->price();
                $this->Cart->cartTotals( $tax, $voucherPrice, $voucher );

                if ( $voucherPrice["inctax"] > $total["inctax"] )
                {
                    $subtractIncVAT["inctax"] = $total["inctax"];
                    $currency->setValue( $total["inctax"] );
                    $this->Template->set_var( "voucher_inc_tax", $locale->format( $currency ) );
                    $subtractExTax["extax"] = $total["extax"];
                    $currency->setValue( $total["extax"] );
                    $this->Template->set_var( "voucher_ex_tax", $locale->format( $currency ) );
                    $continue = false;
                }
                else
                {
                    $subtractIncVAT["inctax"] = $voucherPrice["inctax"];
                    $subtractExTax["extax"] = $voucherPrice["extax"];
                    $currency->setValue( $voucherPrice["inctax"] );
                    $this->Template->set_var( "voucher_inc_tax", $locale->format( $currency ) );
                    $currency->setValue( $voucherPrice["extax"] );
                    $this->Template->set_var( "voucher_ex_tax", $locale->format( $currency ) );
                }

                $total["extax"] -= $subtractExTax["extax"];
                $total["inctax"] -= $subtractIncVAT["inctax"];

                $this->turnColumnsOnOff( "voucher" );
                $this->Template->set_var( "number", $i );
                $this->Template->set_var( "voucher_id", $voucher->id() );
                $this->Template->set_var( "voucher_key", $voucher->keyNumber() );
                $this->Template->parse( "delete_voucher", "delete_voucher_tpl" );
                $this->Template->parse( "voucher_item", "voucher_item_tpl", true );
                $i++;
            }
        }
        if ( count( $payedWithVoucher ) > 0 )
        {
            $this->turnColumnsOnOff( "header" );
        }

        if ( count ( $verifyData ) > 0 )
        {
            $deletePayments = true;
            $name = $instance->paymentName( $verifyData["PaymentMethod"] );
            $this->Template->set_var( "payment_method_name", $name );
            $currency->setValue( $total["inctax"] );
            $this->Template->set_var( "payment_inc_tax", $locale->format( $currency ) );
            $total["inctax"] = 0;
            $this->turnColumnsOnOff( "payment" );
            $this->Template->parse( "payment_item", "payment_item_tpl" );
        }

        if ( $deletePayments )
        {
            $this->Template->parse( "delete_payments", "delete_payments_tpl" );
        }
        else
        {
            $this->Template->set_var( "delete_payments", "" );
        }

        if ( $total["inctax"] <= 0 )
        {
            $this->Template->parse( "next", "next_tpl" );
        }
        else
        {
            $this->Template->set_var( "next", "" );
        }

        $currency->setValue( $total["inctax"] );
        $this->Template->set_var( "rest_price_inc_tax", $locale->format( $currency ) );
        $currency->setValue( $total["extax"] );
        $this->Template->set_var( "rest_price_ex_tax", $locale->format( $currency ) );

        $this->turnColumnsOnOff( "rest" );

        $this->Template->parse( "rest_list", "rest_list_tpl" );
    }

    var $Template;
    var $Cart;
    /// If this setting is set to true prices shown to anonymous users will include VAT.
    var $PricesIncludeVAT_;
    var $ShowExTaxColumn_;
    var $ShowIncTaxColumn_;
    var $ShowExTaxTotal_;
    var $ColSpanSizeTotals;
    var $ShowCart_;
}

?>
