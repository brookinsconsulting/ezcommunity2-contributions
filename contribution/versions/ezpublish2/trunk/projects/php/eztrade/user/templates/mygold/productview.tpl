<h1>{intl-head_line}</h1>

<hr noshade="noshade" size="1"/>
<img src="/images/path-arrow.gif" height="10" width="12" border="0" alt="" />
<a class="path" href="/{module}/{module_list}/0/">{intl-top}</a>

<!-- BEGIN path_tpl -->
<img src="/images/path-slash.gif" height="10" width="16" border="0" alt="" />
<a class="path" href="/{module}/{module_list}/{category_id}/">{category_name}</a>
<!-- END path_tpl -->

<hr noshade="noshade" size="1"/>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr>	
	<td>
	    <h2>{title_text}</h2>
	</td>
	<td align="right">
	    <br />
	    
	    <!-- BEGIN product_number_item_tpl -->
	    <span class="boxtext">{intl-nr}:</span> {product_number}
	    <!-- END product_number_item_tpl -->
	    
	</td>
    </tr>
    <tr>
	<td colspan="2">
	    <br />
	    
	    <!-- BEGIN main_image_tpl -->
	    <table align="right" cellspacing="0" cellpadding="0" border="0">
		<tr>
		    <td>
			<a href="/imagecatalogue/imageview/{main_image_id}/?RefererURL=/{module}/{module_view}/{product_id}/">
			    <img src="{main_image_uri}" border="0" width="{main_image_width}" height="{main_image_height}" /></a>
		    </td>
		</tr>
		<tr>
		    <td class="pictext">
			{main_image_caption}
		    </td>
		</tr>
	    </table>
	    <!-- END main_image_tpl -->

	    <p>{intro_text}</p>
	    <div class="p">{description_text}</div>
	    <br clear="all" />

	    <!-- BEGIN image_list_tpl -->
	    <table width="100%" cellspacing="0" cellpadding="7">
		<tr>
		
		    <!-- BEGIN image_tpl -->
		    <td class="bglight">
			<table cellspacing="0" cellpadding="0" border="0">
			    <tr>
				<td valign="top">
				    <a href="/imagecatalogue/imageview/{image_id}/?RefererURL=/{module}/{module_view}/{product_id}/">
				    <img src="{image_url}" border="0" alt="{image_caption}" width="{image_width}" height="{image_height}"/></a>
				</td>
			    </tr>
			    <tr>
				<td valign="top">
				    <p class="pictext">
				    {image_caption}
				    </p>
				</td>
			    </tr>
			</table>
			&nbsp;

		    </td>
		    <!-- END image_tpl -->
	    	</tr>
	    </table>
	    <br />
	    <!-- END image_list_tpl -->
        </td>
   </tr>
</table>
<form action="/{module}/cart/add/{product_id}/" method="post">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
	<td>
	    <!-- BEGIN attribute_list_tpl -->
	    <table cellspacing="2" cellpadding="2" border="0">
		<!-- BEGIN attribute_tpl -->
		<tr bgcolor="#DDDDDD">
		    <td class="attribute">
			{attribute_name}:&nbsp; 
		    </td>
		    <td align="right">
    			{attribute_value}
		    </td>
		</tr>
		<!-- END attribute_tpl -->
	    </table>
	    <!-- END attribute_list_tpl -->
	</td>
	<td align="right" valign="bottom">
	    

		<!-- BEGIN option_tpl -->
		Gr&ouml;&szlig;e:

	<input type="hidden" name="OptionIDArray[]" value="{option_id}" />
	<!-- BEGIN value_price_header_tpl -->

	<!-- BEGIN value_description_header_tpl -->

	<!-- END value_description_header_tpl -->

	<!-- BEGIN value_price_header_item_tpl -->

	<!-- END value_price_header_item_tpl -->

	<!-- BEGIN value_currency_header_item_tpl -->

	<!-- END value_currency_header_item_tpl -->

	<!-- END value_price_header_tpl -->
	<select name="OptionValueArray[]">

	<!-- BEGIN value_tpl -->
	<!-- BEGIN value_description_tpl -->
	<option value="{value_id}">{value_name}
	<!-- END value_description_tpl -->
	<!-- BEGIN value_price_item_tpl -->
	{value_price}
	<!-- END value_price_item_tpl -->
	<!-- BEGIN value_availability_item_tpl -->
	({value_availability})
	<!-- END value_availability_item_tpl -->
	 </option>

	<!-- BEGIN value_price_currency_list_tpl -->

	<!-- BEGIN value_price_currency_item_tpl -->

	<!-- END value_price_currency_item_tpl -->

	<!-- END value_price_currency_list_tpl -->

	<!-- END value_tpl -->
	</select>

		{option_description}
		<!-- END option_tpl -->

		<br /><br />

	<!-- BEGIN price_tpl -->
	{product_price}
        <br />
	<!-- BEGIN alternative_currency_list_tpl -->
	<!-- BEGIN alternative_currency_tpl -->
	<span style="font-style: italic">{alt_price}</span>
	<!-- END alternative_currency_tpl -->

	<!-- END alternative_currency_list_tpl -->

	<!-- END price_tpl -->	

	<!-- BEGIN external_link_tpl -->
	{intl-external_link}:
	<a href="{external_link_url}" target="_blank">{external_link_url}</a>
	<!-- END external_link_tpl -->
	    {extra_product_info}
	</td>
    </tr>
</table>

<br />

<!-- BEGIN quantity_item_tpl -->
<p class="boxtext">{intl-availability}:</p>
{product_quantity}
<!-- END quantity_item_tpl -->

<hr noshade="noshade" size="1"/>

<!-- BEGIN add_to_cart_tpl -->
<input class="okbutton" type="submit" name="Cart" value="{intl-add_to_cart}" />

<input class="okbutton" type="submit" name="WishList" value="{intl-wishlist}" />
<!-- END add_to_cart_tpl -->

<br /><br />

<!-- BEGIN numbered_page_link_tpl -->
<div align="center"><a class="path" href="/{module}/{module_view}/{product_id}/0/">| {intl-numbered_page} |</a></div>
<!-- END numbered_page_link_tpl -->

<!-- BEGIN print_page_link_tpl -->

<!-- END print_page_link_tpl -->

</form>
