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
	<b>{intl-first_name}</b>
	</td>
	<td>
	<b>{intl-last_name}</b>
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
	<b>{intl-email}</b>
	</td>
</tr>
<tr>
	<td>
	{customer_email}
	</td>
</tr>
</table>

<h2>{intl-address_list}</h2>x

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

<hr noshade="noshade" size="4" />


<!-- BEGIN order_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th><a href="/trade/orderlist/?OrderBy=No">{intl-nr}:</a></th>
	<th><a href="/trade/orderlist/?OrderBy=Created">{intl-created}:</a></th>
	<!-- BEGIN order_status_header_tpl -->
	<th><a href="/trade/orderlist/?OrderBy=Status">{intl-status}:</a></th>
	<!-- END order_status_header_tpl -->
	<td align="right" ><b>{intl-price}:</b></td>
</tr>

<!-- BEGIN order_item_tpl -->
<tr>
	<td class="{td_class}" >
	<a href="/trade/orderedit/{order_id}">{order_id}</a>
	</td>
	<td class="{td_class}" >
	 {order_date}
	</td>
	<td class="{td_class}" >
	 {order_status}
	</td>
	<td align="right" class="{td_class}" >
	{order_price}
	</td>
</tr>

<!-- END order_item_tpl -->
</table>
<!-- END order_list_tpl -->

<h2>{intl-wishlist} ( {wish_count} )</h2>

<hr noshade="noshade" size="4" />

<!-- BEGIN wish_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr> 
      <th>&nbsp;</th>
      <th>{intl-product_name}:</th>
      <th>{intl-is_product_bought}:</th>
      <th>{intl-product_options}:</th>
      <td align="right"><b>{intl-product_price}:</b></td>
</tr>
<!-- BEGIN wishlist_item_tpl --> 
<tr>
      <td class="{td_class}">
      <!-- BEGIN wishlist_image_tpl -->
      <img src="{www_dir}{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/> 
      <!-- END wishlist_image_tpl --> 
      </td>
      <td class="{td_class}"> 
      <a href="{www_dir}{index}/trade/productedit/productpreview/{product_id}/">{product_name}</a> 
      </td>
      <td class="{td_class}">
      <!-- BEGIN is_bought_tpl -->
      {intl-is_bought}
      <!-- END is_bought_tpl -->
      <!-- BEGIN is_not_bought_tpl -->
      {intl-is_not_bought}
      <!-- END is_not_bought_tpl -->
      </td>

      <td class="{td_class}"> 
      <!-- BEGIN wishlist_item_option_tpl --> 
      <div class="small">{option_name}: {option_value}
      <!-- END wishlist_item_option_tpl --> 
      &nbsp;</td>

      <td class="{td_class}" align="right">
      <nobr>{product_price}</nobr>
      </td>
</tr>
<!-- END wishlist_item_tpl --> 
<!-- END wish_list_tpl -->
</table>

<!-- BEGIN voucher_list_tpl -->

<h2>{intl-vouchers} ( {voucher_count} )</h2>

<hr noshade="noshade" size="4" />

<form method="post" action="{www_dir}{index}/trade/voucherlist/">
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<tr>
	<th>{intl-used}:</th>
	<th>{intl-order}:</th>
	<th>{intl-voucher}:</th>
	<td align="right" ><b>{intl-price}:</b></td>
</tr>

<!-- BEGIN used_item_tpl -->
<tr>
       <td class="{td_class}">
       {used_used}       
       </td>
       <td class="{td_class}">
       <a href="/trade/orderedit/{order_id}/">{order_id}</a>
       </td>
       <td class="{td_class}">
       <a href="/trade/voucheredit/{voucher_id}/">{voucher_id}</a>       
       </td>
       <td align="right" class="{td_class}">
       {used_price}       
       </td>
</tr>
<!-- END used_item_tpl -->
</table>
<!-- END voucher_list_tpl -->
<hr noshade="noshade" size="4" />
