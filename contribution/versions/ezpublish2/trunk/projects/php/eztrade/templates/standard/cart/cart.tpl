<!-- cart.tpl --> 
<!-- $Id: cart.tpl,v 1.2 2000/09/28 13:15:45 bf-cvs Exp $ -->

<!-- BEGIN cart_header_tpl -->
<h1>{intl-cart}</h1>
<!-- END cart_header_tpl -->

<!-- BEGIN wishlist_header_tpl -->
<h1>{intl-wishlist}</h1>
<!-- END wishlist_header_tpl -->

<!-- BEGIN cart_item_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="3" border="0">
<tr>
	<th>
	Bilde:	
	</th>
	<th>
	Varenavn:
	</th>
	<th>
	Opsjoner:
	</th>
</tr>
<!-- BEGIN cart_item_tpl -->
<tr>
	<td class="{td_class}">
	<img src="{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/>
	</td>
	<td class="{td_class}">
	{product_name}
	</td>
	<td class="{td_class}">
        <!-- BEGIN cart_item_option_tpl -->
	{option_name}-
	{option_value}<br>
        <!-- END cart_item_option_tpl -->
	</td>
</tr>
<!-- END cart_item_tpl -->
</table>
<!-- END cart_item_list_tpl -->

