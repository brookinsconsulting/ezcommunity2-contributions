<!-- orderlist.tpl --> 
<!-- $Id: orderlist.tpl,v 1.1.2.3 2001/10/22 13:40:19 ce Exp $ -->

<table width="100%" border="0">
<tr>
	<td>
	<h1>{intl-head_line} {full_name}</h1>
	</td>
</tr>
</table>

<hr noshade="noshade" size="1" />

<!-- BEGIN no_items_tpl -->
<p class="error">{intl-no_items_found}</p>
<!-- END no_items_tpl -->

<!-- BEGIN order_item_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="2" border="0">
<tr align="left">
	<th>{intl-nr}:</th>
	<th>{intl-created}:</th>
	<!-- BEGIN order_status_header_tpl -->
	<th>{intl-status}:</th>
	<!-- END order_status_header_tpl -->
	<th align="right">{intl-price}:</th>
	<th>&nbsp;</th>	
	<th>{intl-view}:</th>
</tr>

<!-- BEGIN order_item_tpl -->
<tr>
	<td class="{td_class}">{order_id}</td>
	<td class="{td_class}"><span class="small">{order_date}</span></td>
	<!-- BEGIN order_status_tpl -->
	<td class="{td_class}">{order_status}</td>
	<!-- END order_status_tpl -->
	<td class="{td_class}" align="right">{order_price}</td>
	<td class="{td_class}">&nbsp;</td>	
	<td class="{td_class}">
          <a href="{www_dir}{index}/trade/orderview/{order_id}/">{intl-view}</a>
	</td>
</tr>
<!-- END order_item_tpl -->
</table>
<!-- END order_item_list_tpl -->

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/trade/orderlist/{item_previous_index}/">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
	</td>
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	<td>
	|&nbsp;<a class="path" href="{www_dir}{index}/trade/orderlist/{item_index}/">{type_item_name}</a>&nbsp;
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>
	|&nbsp;&lt;&nbsp;{type_item_name}&nbsp;&gt;&nbsp;
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	|&nbsp;<a class="path" href="{www_dir}{index}/trade/orderlist/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	|&nbsp;
	</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->

<hr noshade="noshade" size="1" />


