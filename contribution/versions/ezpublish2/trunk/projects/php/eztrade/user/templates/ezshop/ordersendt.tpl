<h1>{intl-confirming-order}</h1>

<hr noshade="noshade" size="4" />

<h2>{intl-thanks_for_shopping}</h2>

<p>{intl-email_notice}</p>


<!-- BEGIN visa_tpl -->
<pre>

Order number: {order_id}

Card holders name:

--------------------------------

Card holders address:

--------------------------------

Card number:

---------------------------------

Expire:

---------------------------------

Signature:

---------------------------------

CVC 2:

---------------------------------
</pre>
<!-- END visa_tpl -->

<!-- BEGIN mastercard_tpl -->
<pre>

Order number: {order_id}

Card holders name:

--------------------------------

Card holders address:

--------------------------------

Card number:

---------------------------------

Expire:

---------------------------------

Signature:

---------------------------------

CVC 2:

---------------------------------
</pre>
<!-- END mastercard_tpl -->

<!-- BEGIN cod_tpl -->

<!-- END cod_tpl -->

<!-- BEGIN invoice_tpl -->
invoice
<!-- END invoice_tpl -->



<h2>Kundeinformasjon</h2>

{customer_first_name} {customer_last_name} 

<br />

<!-- BEGIN address_tpl -->
{street1}<br />
{street2}<br />
{zip} {place}<br />
{country}<br />
<!-- END address_tpl -->

<br />

<h2>Vareliste</h2>

<!-- BEGIN order_item_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="3" border="0">
<tr>
	<th>Bilde:</th>
	<th>Varenavn:</th>
	<th>Opsjoner:</th>
	<th>Antall:</th>
	<td align="right"><b>Pris:</b></td>
</tr>
<!-- BEGIN order_item_tpl -->

<tr>
	<td class="{td_class}">
	<!-- BEGIN order_image_tpl -->
	<img src="{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/>
	<!-- END order_image_tpl -->
	</td>
	<td class="{td_class}">
	{product_name}&nbsp;
	</td>
	<td class="{td_class}">
        <!-- BEGIN order_item_option_tpl -->
	{option_name}:
	{option_value}<br>
        <!-- END order_item_option_tpl -->
	&nbsp;
	</td>
	<td class="{td_class}">
	{order_item_count}
	</td>
	<td class="{td_class}" align="right">
	{product_price}
	</td>
</tr>
<!-- END order_item_tpl -->
<tr>
	<td colspan="2">&nbsp;</td>
	<td class="boxtext">Frakt:</td>
	<td align="right">{shipping_cost}</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
	<td class="boxtext">Totalt:</td>
	<td align="right">{order_sum}</td>
</tr>
</table>
<!-- END order_item_list_tpl -->

