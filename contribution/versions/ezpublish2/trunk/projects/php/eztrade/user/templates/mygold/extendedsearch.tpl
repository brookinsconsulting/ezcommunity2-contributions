<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-head_line}</h1>
     </td>
</tr>
</table>

<hr noshade="noshade" size="1" />

<!-- BEGIN product_search_form_tpl -->
<form action="/trade/extendedsearch/" method="post">
<table width="50%" cellspacing="0" cellpadding="0" border="0">
<tr>
        <td>{intl-text}</td>
</tr></tr>
<tr>
        <td>
	<input type="text" name="Text" value="{text}" />
	</td>
</tr>
<tr>
        <td>{intl-price_lower}</td>
        <td>{intl-price_higher}</td>
</tr>
<tr>
        <td>
	<input type="text" name="PriceLower" value="{price_lower}" />
	</td>
        <td>
	<input type="text" name="PriceHigher" value="{price_higher}" />
	</td>
</tr>
<tr>
        <td>&nbsp;</td>
</tr>
<tr>
        <td>{intl-search_in_category}</td>
</tr>
<tr>
        <td>
	<select name="CategoryArrayID[]" multiple size="5">
	<!-- BEGIN category_item_tpl -->
	<option value="{category_id}" {is_selected}>{option_level}{category_name}</option>
	<!-- END category_item_tpl -->
	</select>
	<br />
	</td>
</tr>
</table>
	<hr noshade="noshade" size="1" />
	<input class="okbutton" type="submit" name="SearchButton" value="{intl-search}" />
</form>
<!-- END product_search_form_tpl -->


<!-- BEGIN product_search_list_tpl -->        
<br>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN product_tpl -->
<tr>
	<td>
	<a href="/trade/productview/{product_id}/{category_id}/"><h2>{product_name}</h2></a><br>
    <!-- BEGIN image_tpl -->
    <table align="right">
    <tr>
        <td>
        <img src="{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" />
        </td>
    </tr>
    <tr>
        <td>
        {thumbnail_image_caption}
        </td>
    </tr>
    </table>
    <!-- END image_tpl -->

    {product_intro_text}

<br>
	<!-- BEGIN price_tpl -->
	{product_price}<br>
	<!-- END price_tpl -->

	</td>
</tr>
<!-- END product_tpl -->
</table>
<!-- END product_search_list_tpl -->        


<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="/trade/extendedsearch/move/{url_text}/{url_lower}/{url_higher}/{url_category}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;|
	</td>
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	<td>
	&nbsp;<a class="path" href="/trade/extendedsearch/move/{url_text}/{url_lower}/{url_higher}/{url_category}/{item_index}">{type_item_name}</a>&nbsp;|
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>
	&nbsp;&lt;{type_item_name}&gt;&nbsp;|
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	&nbsp;<a class="path" href="/trade/extendedsearch/move/{url_text}/{url_lower}/{url_higher}/{url_category}/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	{intl-next}
	</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->
	</td>
</tr>

</table>
