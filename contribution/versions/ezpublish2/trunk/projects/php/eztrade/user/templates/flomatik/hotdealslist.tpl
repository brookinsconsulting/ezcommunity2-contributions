<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td class="menuhead" colspan="{hotdeal_columns}">{intl-hot_deals}</td>
</tr>

<!-- BEGIN product_list_tpl -->

<!-- BEGIN product_tpl -->
{begin_tr}
	<td class="menutext">

	<a class="menutext" href="/trade/productview/{product_id}/{category_id}/"><b>{product_name}</b></a>

	<!-- BEGIN product_image_tpl -->
	<a href="/trade/productview/{product_id}/{category_id}/"><img src="{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/></a>
	<!-- END product_image_tpl -->&nbsp;

	<br />
	<span class="menutext">{product_intro_text}</span><br />

<!-- BEGIN price_tpl -->
	<span class="pris">{product_price}</span>
<!-- END price_tpl -->

	</td>
{end_tr}

<!-- END product_tpl -->

<tr>
	<td class="menuspacer">&nbsp;</td>
</tr>
</table>

<!-- END product_list_tpl -->

