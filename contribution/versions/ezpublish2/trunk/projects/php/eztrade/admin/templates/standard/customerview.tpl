<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<h1>{intl-customerview}</h1>
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<table width="100%" cellspacing="0" cellpadding="0" baddress="0">
<tr>
	<td>
	<h2>{intl-first_name}</h2>
	</td>
	<td>
	<h2>{intl-last_name}</h2>
	</td>	
</tr>
<tr>
	<td>
	{customer_first_name} 
	</td>
	<td>
	{customer_last_name}
	</td>
</tr>
<tr>
	<td>
	<h2>{intl-email}</h2>
	</td>
</tr>
<tr>
	<td>
	{customer_email}
	</td>
</tr>
</table>

<h2>{intl-address_list}</h2>

<!-- BEGIN address_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" baddress="0">
<!-- BEGIN address_item_tpl -->
<tr>
	<th>
	{intl-street1}:
	</th>
	<th>
	{intl-street2}:
	</th>
</tr>
<tr>
	<td>
	{street1}
	</td>
	<td>
	{street2}
	</td>
</tr>
<tr>
	<th>
	{intl-zip}:
	</th>
	<th>
	{intl-place}:
	</th>
</tr>
<tr>
	<td>
	{zip}
	</td>
	<td>
	{place}
	</td>
</tr>
<tr>
	<th>
	{intl-country}
	</th>
</tr>
<tr>
	<td>
	{country}
	</td>
</tr>
<!-- END address_item_tpl -->
</table>
<!-- END address_list_tpl -->


<h2>{intl-orders} ( {order_count} )</h2>

<!-- BEGIN order_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN order_item_tpl -->
<tr>
	<td>
	<a href="/trade/orderedit/{order_id}">{order_id} {order_date} {order_status} {order_price}</a>
	</td>
</tr>

<!-- END order_item_tpl -->
</table>
<!-- END order_list_tpl -->

<hr noshade="noshade" size="4" />
