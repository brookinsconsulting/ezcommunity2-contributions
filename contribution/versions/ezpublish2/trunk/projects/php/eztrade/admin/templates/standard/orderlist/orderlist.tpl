<!-- orderlist.tpl --> 
<!-- $Id: orderlist.tpl,v 1.1 2000/10/03 09:46:16 bf-cvs Exp $ -->

<h1>{intl-head_line}</h1>

<!-- BEGIN order_item_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="3" border="0">
<tr>
	<th>
	Nr:
	</th>
	<th>
	Opprettet:
	</th>
	<th>
	Sist Endret Status:
	</th>
	<th>
	Status:
	</th>
	<th>
	Pris:
	</th>
	<th>
	Rediger:
	</th>
	<th>
	Slett:
	</th>
</tr>
<!-- BEGIN order_item_tpl -->
<tr>
	<td class="{td_class}">
	{order_id}
	</td>
	<td class="{td_class}">
	{order_date}
	</td>
	<td class="{td_class}">
	{altered_date}
	</td>
	<td class="{td_class}">
	{order_status}
	</td>
	<td class="{td_class}" align="right">
	{order_price}
	</td>
	<td class="{td_class}" align="right">
	<a href="/trade/orderedit/{order_id}/">[ rediger ]</a>
	</td>
	<td class="{td_class}" align="right">
	[ slett ]
	</td>
</tr>
<!-- END order_item_tpl -->

</table>
<!-- END order_item_list_tpl -->

