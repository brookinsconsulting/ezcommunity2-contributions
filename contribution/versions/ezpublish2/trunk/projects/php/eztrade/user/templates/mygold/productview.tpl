<form action="/{module}/cart/add/{product_id}/" method="post">
	<h1>{intl-head_line}</h1>
	<hr noshade size="1"/>
	<img src="/images/path-arrow.gif" height="10" width="12" border="0" alt="" /> 
	<a class="path" href="/{module}/{module_list}/0/">{intl-top}</a> 
	<!-- BEGIN path_tpl -->
	<img src="/images/path-slash.gif" height="10" width="16" border="0" alt="" /> 
	<a class="path" href="/{module}/{module_list}/{category_id}/">{category_name}</a> 
	<!-- END path_tpl -->
	<hr noshade size="1"/>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr> 
			<td colspan="2" width="99%" valign="top"> 
				<h2>{title_text}</h2>
			</td>
			<td rowspan="3"><img src="/sitedesign/mygold/images/shim.gif" alt="" width="8" height="8" /></td>
			<td align="right" width="1%" valign="bottom"> 
				<!-- BEGIN product_number_item_tpl -->
				{intl-nr}: {product_number}<br /><br /> 
				<!-- END product_number_item_tpl -->
			</td>
		</tr>
		<tr> 
			<td colspan="2" valign="top"> 
				<p>{intro_text}</p>
				<p>{description_text}</p>
			</td>
			<td rowspan="2" align="right" valign="middle"> 
				<!-- BEGIN main_image_tpl -->
				<table cellspacing="0" cellpadding="0" border="0">
					<tr> 
						<td> 
							<div align="right"><a href="/imagecatalogue/imageview/{main_image_id}/?RefererURL=/{module}/{module_view}/{product_id}/"> 
								<img src="{main_image_uri}" border="0" width="{main_image_width}" height="{main_image_height}" alt=""/></a> 
							</div>
						</td>
					</tr>
					<tr> 
						<td class="pictext"> 
							<div align="center">{main_image_caption}</div>
						</td>
					</tr>
				</table>
				<!-- END main_image_tpl -->
			</td>
		</tr>
		<tr> 
			<td colspan="2" valign="bottom"> 
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr> 
						<td colspan="2"> 
							<!-- BEGIN attribute_list_tpl -->
							<table cellspacing="0" cellpadding="0" border="0" width="100%">
								<tr> 
									<td colspan="2"> 
										<hr size="1" noshade />
									</td>
								</tr>
								<!-- BEGIN attribute_tpl -->
								<tr> 
									<td class="attribute" valign="top"> {attribute_name}:&nbsp; 
									</td>
									<td class="attributevalue" align="right" valign="bottom"> 
										{attribute_value} </td>
								</tr>
								<!-- END attribute_tpl -->
								<tr> 
									<td colspan="2"> 
										<hr size="1" noshade />
									</td>
								</tr>
							</table>
							<!-- END attribute_list_tpl -->
						</td>
					</tr>
					<tr> 
						<td valign="bottom"> 
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
						</td>
						<td align="right" valign="bottom"> 
							<!-- BEGIN price_tpl -->
							{product_price}<br />
							<!-- BEGIN alternative_currency_list_tpl -->
							<!-- BEGIN alternative_currency_tpl -->
							<span style="font-style: italic">{alt_price}</span> 
							<!-- END alternative_currency_tpl -->
							<!-- END alternative_currency_list_tpl -->
							<!-- END price_tpl -->
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<!-- BEGIN image_list_tpl -->
	<!-- BEGIN image_tpl -->
	<!-- END image_tpl -->
	<!-- END image_list_tpl -->

	<!-- BEGIN section_item_tpl -->
	<!-- BEGIN link_item_tpl -->
	<!-- END link_item_tpl -->
	<!-- END section_item_tpl -->

	<!-- BEGIN external_link_tpl -->
	<!-- END external_link_tpl -->
	{extra_product_info}

	<!-- BEGIN quantity_item_tpl -->
	<!-- END quantity_item_tpl -->

	<hr noshade size="1"/>

	<!-- BEGIN add_to_cart_tpl -->
	<input class="okbutton" type="submit" name="Cart" value="{intl-add_to_cart}" />
	<input class="okbutton" type="submit" name="WishList" value="{intl-wishlist}" />
	<!-- END add_to_cart_tpl -->
	<br />
	<!-- BEGIN numbered_page_link_tpl -->
	&nbsp;
	<!-- END numbered_page_link_tpl -->

	<!-- BEGIN print_page_link_tpl -->
	&nbsp;
	<!-- END print_page_link_tpl -->
</form>