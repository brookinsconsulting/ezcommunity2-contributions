<!-- wishlist.tpl -->

<form action="{www_dir}{index}/trade/wishlist/" method="post">

<!-- BEGIN full_wishlist_tpl -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td>
    <h1>{intl-wishlist}</h1>
    </td>
    <td align="right">
    <!-- BEGIN public_wishlist_tpl -->
    <span class="boxtext">{intl-non_public_wishlist_text}</span>&nbsp;<input type="submit" class="okbutton" name="IsPublic" value="{intl-non_public_wishlist}" />
    <!-- END public_wishlist_tpl -->
    <!-- BEGIN non_public_wishlist_tpl -->
    <span class="boxtext">{intl-non_public_wishlist_text}</span>&nbsp;<input type="submit" class="okbutton" name="IsPublic"  value="{intl-non_public_wishlist}"/>
    <!-- END non_public_wishlist_tpl -->
	</td>
</tr>
</table>

<hr noshade="noshade" size="1" />
<br />
<table width="100%" border="1" cellspacing="0" cellpadding="4">
<tr>
    <td colspan="2">

    <!-- BEGIN empty_wishlist_tpl -->
    <h2>{intl-empty_wishlist}</h2>
    <!-- END empty_wishlist_tpl --> 

    <!-- BEGIN wishlist_item_list_tpl -->
    <tr> 
        <th>{intl-pic}</th>
	<th>{intl-product_qty}:</th>		
	<th>{intl-product_name}:</th>
	<th>{intl-move_to_cart}:</th>
	<th>{intl-someone_has_bought_this}:</th>
	<!-- BEGIN product_available_header_tpl -->
	<!-- <th>{intl-product_availability}:</th> -->
	<!-- END product_available_header_tpl -->
	<th class="right">{intl-single_price}<br />{intl-inc_tax}</th>
	<!-- BEGIN header_savings_item_tpl -->
	<th class="right">{intl-product_savings}:</th>
	<!-- END header_savings_item_tpl -->
	<!-- BEGIN header_ex_tax_item_tpl -->
	<th class="right">{intl-total_price}<br />{intl-ex_tax}:</th>
	<!-- END header_ex_tax_item_tpl -->
	<!-- BEGIN header_inc_tax_item_tpl -->
	<th class="right">{intl-total_price}<br />{intl-inc_tax}:</th>
	<!-- END header_inc_tax_item_tpl -->
        <th>&nbsp;</th>	
	</tr>
    <!-- BEGIN wishlist_item_tpl --> 
    <tr valign="top"> 
        <td class="{td_class}"> 
        <!-- BEGIN wishlist_image_tpl --> 
	<img src="{www_dir}{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/> 
	<!-- END wishlist_image_tpl --> 
        </td>
	<td class="{td_class}">
	<input type="hidden" name="WishlistIDArray[]" value="{wishlist_item_id}" />
	{wishlist_item_count}
	</td>	
	<td class="{td_class}"><nobr><a href="{www_dir}{index}/trade/productview/{product_id}/">{product_name}</a></nobr>
	<!-- BEGIN wishlist_item_option_tpl --> 
	<div class="small"><br />{option_name}: {option_value}
	<!-- BEGIN wishlist_item_option_availability_tpl -->
	({option_availability})
	<!-- END wishlist_item_option_availability_tpl -->
	</div>
	<!-- END wishlist_item_option_tpl --> &nbsp;	 
	</td>
	<td class="{td_class}">
	<!-- BEGIN move_to_cart_item_tpl -->
	<a href="{www_dir}{index}/trade/wishlist/movetocart/{wishlist_item_id}/">
	{intl-move_to_cart} 
	</a> 
	<!-- END move_to_cart_item_tpl -->
	<!-- BEGIN no_move_to_cart_item_tpl -->
	&nbsp;
	<!-- END no_move_to_cart_item_tpl -->
	</td>
	<td class="{td_class}">
	<!-- BEGIN is_bought_tpl -->
	{intl-is_bought}
	<!-- END is_bought_tpl -->
	<!-- BEGIN is_not_bought_tpl -->
	{intl-is_not_bought}
	<!-- END is_not_bought_tpl -->
	</td>
	<!-- BEGIN product_available_item_tpl -->
	<!--
	<td class="{td_class}">
	{product_availability}
	</td>
	-->
	<!-- END product_available_item_tpl -->

	<td class="{td_class}" align="right"><nobr>{product_price}</nobr></td>

	<!-- BEGIN wishlist_savings_item_tpl -->
	<td class="{td_class}" align="right">&nbsp;</td>
	<!-- END wishlist_savings_item_tpl -->

	<!-- BEGIN wishlist_ex_tax_item_tpl -->
	<td class="{td_class}" align="right"><nobr>{product_total_ex_tax}</nobr></td>
	<!-- END wishlist_ex_tax_item_tpl -->
	<!-- BEGIN wishlist_inc_tax_item_tpl -->
	<td class="{td_class}" align="right"><nobr>{product_total_inc_tax}</nobr></td>
	<!-- END wishlist_inc_tax_item_tpl -->
	<td class="{td_class}" align="right">
        <input type="checkbox" name="DeleteItem[]" value="{wishlist_item_id}" />
	</td>
    </tr>
    <!-- BEGIN wishlist_item_basis_tpl -->
    <tr>
        <td class="{td_class}">&nbsp;</td>
	<td class="{td_class}">&nbsp;</td>
	<td class="{td_class}"><span class="small">{intl-basis_price} <nobr>{basis_price}<nobr/></span></td>
	<td class="{td_class}" align="right">&nbsp;</td>

	<!-- BEGIN basis_savings_item_tpl -->
	<td class="{td_class}" align="right">&nbsp;</td>
	<!-- END basis_savings_item_tpl -->
    
        <td class="{td_class}" align="right">&nbsp;</td>

	<!-- BEGIN basis_inc_tax_item_tpl -->
	<td class="{td_class}" align="right">&nbsp;</td>
	<!-- END basis_inc_tax_item_tpl -->
    
	<!-- BEGIN basis_ex_tax_item_tpl -->
	<td class="{td_class}" align="right">&nbsp;</td>
	<!-- END basis_ex_tax_item_tpl -->

	<td class="{td_class}">&nbsp;</td>
    </tr>
    <!-- END wishlist_item_basis_tpl -->
    <!-- END wishlist_item_tpl --> 
    <!-- END wishlist_item_list_tpl -->
    </td>
</tr>

<tr>
    <td colspan="6" class="right">{intl-total}:</td>

	<!-- BEGIN total_ex_tax_item_tpl -->
    <td align="right"><nobr>{total_ex_tax}</nobr></td>
	<!-- END total_ex_tax_item_tpl -->

	<!-- BEGIN total_inc_tax_item_tpl -->
    <td align="right"><nobr>{total_inc_tax}</nobr></td>
	<!-- END total_inc_tax_item_tpl -->

    <td>&nbsp;</td>
</tr>


</table>
{intl-explain1}<br />
{intl-explain2}<br />
{intl-explain3}

<!-- BEGIN tax_specification_tpl -->
<!-- BEGIN tax_item_tpl -->
&nbsp;
<!-- END tax_item_tpl -->
<!-- END tax_specification_tpl -->


<hr noshade="noshade" size="1" />

<input type="hidden" name="Action" value="Refresh" />
<input class="okbutton" type="submit" name="DeleteItems" value="{intl-delete_slected}" />&nbsp;
<!-- <input class="stdbutton" type="submit" value="{intl-update}" /> -->

<!-- END full_wishlist_tpl -->


</form>

<hr noshade="noshade" size="1" />

<!-- BEGIN wishlist_checkout_tpl -->
<form action="{www_dir}{index}/trade/sendwishlist/" method="post">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
        <td>
        <input class="okbutton" type="submit" value="{intl-send_wishlist}" />
	</td>
</tr>
</table>
<!-- END wishlist_checkout_tpl -->
</form>