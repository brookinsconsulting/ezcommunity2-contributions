<h1>{intl-head_line}</h1>
<hr noshade size="1"/>
<img src="/eztrade/images/path-arrow.gif" height="10" width="15" border="0">
<a href="/trade/productlist/0/">{intl-top}</a>
<!-- BEGIN path_tpl -->
<img src="/eztrade/images/path-slash.gif" height="10" width="20" border="0">
<a href="/trade/productlist/{category_id}/">{category_name}</a>
<!-- END path_tpl -->
<hr noshade size="1"/>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
		<td><h2>{title_text}</h2></td>
		<td align="right">{intl-nr}: {product_number}</td>
	</tr>
	<tr>
		<td valign="top"> 
			<p>{intro_text}<br />
			{description_text}</p>
		</td>
		<td align="right">
			<!-- BEGIN main_image_tpl -->
		 	<table cellspacing="0" cellpadding="0" border="0">
				<tr> 
					<td align="center" width="10">&nbsp;</td>
		  			<td align="center">
						<a href="/imagecatalogue/imageview/{main_image_id}/?RefererURL=/trade/productview/{product_id}/"> 
							<img src="{main_image_uri}" border="0" width="{main_image_width}" height="{main_image_height}" /></a> 
					</td>
				</tr>
				<tr> 
				  <td align="center">&nbsp;</td>
				  <td class="pictext" align="center">{main_image_caption}</td>
				</tr>
	  		</table>
			<!-- END main_image_tpl -->
		</td>
	</tr>
</table>
<table width="100%" cellspacing="0" cellpadding="7">
	<tr>
		<!-- BEGIN image_tpl -->
		<td class="bglight">
			<table cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td valign="top">
						<a href="/imagecatalogue/imageview/{image_id}/?RefererURL=/trade/productview/{product_id}/">
						<img src="{image_url}" border="0" alt="{image_caption}" width="{image_width}" height="{image_height}"/></a>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<p class="pictext">{image_caption}</p>
					</td>
				</tr>
			</table>
		</td>
		<!-- END image_tpl -->
	</tr>
</table>
<form action="/trade/cart/add/{product_id}/" method="post">
	<!-- BEGIN option_tpl -->
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>			
			<td colspan="2">{option_name}</td>
		</tr>
		<tr>
			<td width="20%">
				<input type="hidden" name="OptionIDArray[]" value="{option_id}" />
				<select name="OptionValueArray[]">
					<!-- BEGIN value_tpl -->
					<option value="{value_id}">{value_name}</option>
					<!-- END value_tpl -->
				</select>
			</td>
			<td width="80%">
				{option_description}
			</td>
		</tr>
	</table>
	<!-- END option_tpl -->
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td> 
	  			<!-- BEGIN price_tpl -->
				<p>{intl-price}: <br />
				{product_price}</p>
				<!-- END price_tpl -->	
			</td>
			<td align="right">
				<!-- BEGIN external_link_tpl -->
				<p>{intl-external_link}:<br />
				<a href="{external_link_url}" target="_blank">{external_link_url}</a></p>
				<!-- END external_link_tpl -->
			</td>
		</tr>
		<tr>
			<td colspan="2" class="spacer">&nbsp;</td>
		</tr>
	</table>

        <!-- BEGIN attribute_list_tpl -->
        <table width="100%" cellspacing="0" cellpadding="2" border="0">
        <tr>
	        <th>
         	{intl-attribute_name}
		</th>
		<th>
		{intl-attribute_value}
		</th>
	</tr>
	<!-- BEGIN attribute_tpl -->
	<tr>
	        <td>
		{attribute_name} : 
		</td>
		<td>
		{attribute_value}
		</td>
	</tr>

	<!-- END attribute_tpl -->
	</table>
	<!-- END attribute_list_tpl -->

	<hr noshade size="1" />
	<!-- BEGIN add_to_cart_tpl -->
	<input class="okbutton" type="submit" name="Cart" value="{intl-add_to_cart}" />
	<input class="okbutton" type="submit" name="WishList" value="{intl-wishlist}" />
	<!-- END add_to_cart_tpl -->

	<!-- BEGIN numbered_page_link_tpl -->
	<div align="center"><a class="path" href="/{module}/{module_view}/{product_id}/0/">| {intl-numbered_page} |</a></div>
	<!-- END numbered_page_link_tpl -->

	<!-- BEGIN print_page_link_tpl -->
	<div align="center"> <a class="path" href="/{module}/{module_print}/{product_id}/">| {intl-print_page} |</a></div>
	<!-- END print_page_link_tpl -->
</form>