<h1>Bekreft bestilling</h1>

<h2>Dette er bestilt</h2>

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
	<th>
	Pris:
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
	<td class="{td_class}" align="right">
	{product_price}
	</td>
</tr>
<!-- END cart_item_tpl -->
<tr>
	<td>
	</td>
	<td>
	</td>
	<td>
	Frakt:
	</td>
	<td align="right">
	{shipping_cost}
	</td>
</tr>
<tr>
	<td>
	</td>
	<td>
	</td>
	<td>
	Totalt:
	</td>
	<td align="right">
	{cart_sum}
	</td>
</tr>
</table>
<!-- END cart_item_list_tpl -->

<h2>Varene sendes til</h2>

{customer_first_name} {customer_last_name} 
<br>
<!-- BEGIN address_tpl -->
{street1} <br>
{street2}<br>
{zip} {place}<br>
<!-- END address_tpl -->

<form action="/trade/checkout/" method="post">
<input type="hidden" name="SendOrder" value="true" />
<input type="submit" value="Send ordre" />
</form>



