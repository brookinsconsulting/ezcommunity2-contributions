<h1>Bekreft bestilling</h1>

<hr noshade="noshade" size="4" />

<h2>Dette er bestilt:</h2>

<!-- BEGIN cart_item_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>Bilde:</th>
	<th>Varenavn:</th>
	<th>Opsjoner:</th>
	<th>Antall:</th>
	<td class="path" align="right">Pris:</td>
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
	{option_name}:
	{option_value}<br>
        <!-- END cart_item_option_tpl -->
	&nbsp;
	</td>
	<td class="{td_class}">
	{cart_item_count}
	</td>
	<td class="{td_class}" align="right">
	{product_price}
	</td>
</tr>
<!-- END cart_item_tpl -->

<tr>
	<td colspan="2">&nbsp;</td>
	<th>Frakt:</th>
	<td align="right">
	{shipping_cost}
	</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
	<th>Totalt:</th>
	<td align="right">
	{cart_sum}
	</td>
</tr>
</table>
<!-- END cart_item_list_tpl -->

<h2>Varene sendes til:</h2>

{customer_first_name} {customer_last_name} 
<br />

<!-- BEGIN address_tpl -->
{street1} <br />
{street2}<br />
{zip} {place}<br />
{country}<br />

<!-- END address_tpl -->

<form action="/trade/checkout/" method="post">

<hr noshade="noshade" size="4" />

<input type="hidden" name="SendOrder" value="true" />
<input class="okbutton" type="submit" value="Send ordre" />
</form>



