<h1>{intl-head_line}</h1>
<hr noshade="noshade" size="1" />

<!-- BEGIN error_max_search_for_products_tpl -->
<p class="error">{intl-max_search}</p>
<!-- END error_max_search_for_products_tpl -->

<!-- BEGIN product_search_form_tpl -->
<form action="{www_dir}{index}/trade/extendedsearch/" method="post">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td>
	    <b>{intl-text}:</b>
	</td>
    </tr>
    </tr>
    <tr>
        <td>
	    <input type="text" name="Text" value="{text}" />
	</td>
    </tr>
    <tr>
	<td>
	    &nbsp;
	</td>
    </tr>
    <tr>
        <td>
	    <b>{intl-price_range}:</b>
	</td>
    </tr>
    <tr>
        <td>
	    <select name="PriceRange">
		<option value="-">{intl-all_range}</option>
		<option value="0-50">0-50 DM</option>
		<option value="50-100">50-100 DM</option>
		<option value="100-150">100-150 DM</option>
		<option value="150-200">150-200 DM</option>		
		<option value="200-300">200-300 DM</option>
		<option value="300-500">300-500 DM</option>
		<option value="500-1000">500-1000 DM</option>
		<option value="1000-100000">&gt; 1000 DM</option>		
	    </select>
	</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>
	    <b>{intl-search_in_category}:</b>
	</td>
    </tr>
    <tr>
	<!-- BEGIN category_list_tpl -->
	<input type="hidden" name="MainCategories[]" value="{category_main_id}">
        <td width="1%">
	<p class="boxtext">{category_main_name}:</p>
	    <select name="CategoryArrayID[{category_main_id}][]">
		<option value="0" {is_all_selected}>{intl-all_category}</option>
		<!-- BEGIN category_item_tpl -->
		<option value="{category_id}" {is_selected}>{option_level}{category_name}</option>
		<!-- END category_item_tpl -->
	    </select>
	</td>
	<!-- END category_list_tpl -->
	<td width="*">&nbsp;</td> 
    </tr>
</table>
<hr noshade="noshade" size="1" />
<input class="okbutton" type="submit" name="SearchButton" value="{intl-search}" />
</form>
<!-- END product_search_form_tpl -->

<br /><br />
<!-- BEGIN empty_search_tpl -->        
<h2>{intl-empty_search}</h2>
<!-- END empty_search_tpl -->        


<!-- BEGIN product_search_list_tpl -->        
<br />
<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <!-- BEGIN product_tpl -->
    <tr>
	<td>
	    <a href="{www_dir}{index}/trade/productview/{product_id}/{category_id}/"><h2>{product_name}</h2></a><br>
	    <!-- BEGIN image_tpl -->
	    <table align="right">
		<tr>
    		    <td>
    			<img src="{www_dir}{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" />
    		    </td>
		</tr>
		<tr>
    		    <td>
    			{thumbnail_image_caption}
    		    </td>
		</tr>
	    </table>
	    <!-- END image_tpl -->
	    {product_intro_text}<br />
	    <!-- BEGIN price_tpl -->
	    {product_price}<br />
	    <!-- END price_tpl -->
	</td>
    </tr>
    <tr>
	<td>
	    <hr noshade="noshade" size="1" />
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
			&lt;&lt;&nbsp;<a class="path" href="{www_dir}{index}/trade/extendedsearch/move/{url_text}/{url_range}/{url_main_categories}/{url_category}/{item_previous_index}">{intl-previous}</a>&nbsp;|
		    </td>
		    <!-- END type_list_previous_tpl -->
		    
		    <!-- BEGIN type_list_previous_inactive_tpl -->
		    <td class="inactive">
			{intl-previous}&nbsp;
		    </td>
		    <!-- END type_list_previous_inactive_tpl -->

		    <!-- BEGIN type_list_item_list_tpl -->

		    <!-- BEGIN type_list_item_tpl -->
		    <td>
			&nbsp;<a class="path" href="{www_dir}{index}/trade/extendedsearch/move/{url_text}/{url_range}/{url_main_categories}/{url_category}/{item_index}">{type_item_name}</a>&nbsp;|
		    </td>
		    <!-- END type_list_item_tpl -->

		    <!-- BEGIN type_list_inactive_item_tpl -->
		    <td class="inactive">
			&nbsp;{type_item_name}&nbsp;|
		    </td>
		    <!-- END type_list_inactive_item_tpl -->

		    <!-- END type_list_item_list_tpl -->

		    <!-- BEGIN type_list_next_tpl -->
		    <td>
			&nbsp;<a class="path" href="{www_dir}{index}/trade/extendedsearch/move/{url_text}/{url_range}/{url_main_categories}/{url_category}/{item_next_index}">{intl-next}</a>&nbsp;&gt;&gt;
		    </td>
		    <!-- END type_list_next_tpl -->

		    <!-- BEGIN type_list_next_inactive_tpl -->
		    <td class="inactive">
			{intl-next}&nbsp;&gt;&gt;
		    </td>
		    <!-- END type_list_next_inactive_tpl -->
		</tr>
	    </table>
	    <!-- END type_list_tpl -->
	</td>
    </tr>
</table>
