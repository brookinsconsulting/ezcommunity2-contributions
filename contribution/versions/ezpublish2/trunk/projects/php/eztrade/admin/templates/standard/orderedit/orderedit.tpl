<!-- orderlist.tpl --> 
<!-- $Id: orderedit.tpl,v 1.1 2000/10/03 09:45:18 bf-cvs Exp $ -->

<h1>{intl-head_line}</h1>

<!-- BEGIN order_item_list_tpl -->
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
<!-- BEGIN order_item_tpl -->
<tr>
	<td class="{td_class}">
	<img src="{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/>
	</td>
	<td class="{td_class}">
	{product_name}
	</td>
	<td class="{td_class}">
        <!-- BEGIN order_item_option_tpl -->
	{option_name}-
	{option_value}<br>
        <!-- END order_item_option_tpl -->
	</td>
	<td class="{td_class}" align="right">
	{product_price}
	</td>
</tr>
<!-- END order_item_tpl -->
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
	{order_sum}
	</td>
</tr>
</table>
<!-- END order_item_list_tpl -->

<table width="100%">
<tr>
	<td  width="50%" valign="top">
<form action="/trade/orderedit/{order_id}/newstatus/">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>
	Ordrestatus:	
	</th>
</tr>
<tr>
	<td>	
	Velg status:
	</td>
</tr>
<tr>
	<td>	
	<select name="StatusID">
	<!-- BEGIN order_status_option_tpl -->	
	<option value="{option_id}">
	{option_name}
	</option>
	<!-- END order_status_option_tpl -->	
	</select>
	</td>
</tr>
<tr>
	<td>
	Kommentar til status endring.
	</td>
</tr>	
<tr>
	<td>
	<textarea cols="10" rows="5" name="StatusComment" wrap="soft"></textarea>
	</td>
</tr>
<tr>
	<td>
	<input type="submit" value="endre status"/>
	</td>
</tr>
</table>
</form>
	</td>
	<td width="50%" valign="top">
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<th colspan="2">
		Ordrestatus historie:
		</th>
	</tr>
	<!-- BEGIN order_status_history_tpl -->	
	<tr>
		<td class="{td_class}">
		{status_date}
		</td>
		<td class="{td_class}">
		{status_name}
		</td>
		<td class="{td_class}">
		{status_comment}
		</td>
	</tr>
	<!-- END order_status_history_tpl -->	
	</table>	
	</td>
</tr>
</table>
