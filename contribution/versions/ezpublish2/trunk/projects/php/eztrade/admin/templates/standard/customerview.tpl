<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<h1>{intl-customerview}</h1>
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<h2>{intl-first_name}</h2>
{customer_first_name} 
<h2>{intl-last_name}</h2>
{customer_last_name}
<h2>{intl-email}</h2>
{customer_email}

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
