<!-- orderlist.tpl --> 
<!-- $Id: orderedit.tpl,v 1.3 2000/10/19 13:49:10 th-cvs Exp $ -->

<h1>{intl-head_line}</h1>

<hr noshade="noshade" size="4" />

<h2>Kundeinformasjon</h2>

{customer_first_name} {customer_last_name} 

<br />

<!-- BEGIN address_tpl -->
{street1}<br />
{street2}<br />
{zip} {place}<br />
<!-- END address_tpl -->

<br />

<h2>Vareliste</h2>

<!-- BEGIN order_item_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="3" border="0">
<tr>
	<th>Bilde:</th>
	<th>Varenavn:</th>
	<th>Opsjoner:</th>
	<td align="right"><b>Pris:</b></td>
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
	{option_name}:
	{option_value}<br>
        <!-- END order_item_option_tpl -->
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

<h2>Ordrestatus</h2>

<table width="100%">
<tr>
	<td  width="50%" valign="top">
<form action="/trade/orderedit/{order_id}/newstatus/">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">Velg status:</p>
	<select name="StatusID">
	<!-- BEGIN order_status_option_tpl -->	
	<option value="{option_id}">
	{option_name}
	</option>
	<!-- END order_status_option_tpl -->	
	</select>
	<br /><br />
	</td>
</tr>
<tr>
	<td>
	<p class="boxtext">Kommentar til statusendring:</p>
	<textarea cols="40" rows="5" name="StatusComment" wrap="soft"></textarea>
	</td>
</tr>
<tr>
	<td>
	</td>
</tr>
</table>
	</td>
	<td width="50%" valign="top">
	<table width="100%" cellspacing="0" cellpadding="4" border="0">
	<tr>
		<th colspan="3">Statushistorie:</th>
	</tr>
	<!-- BEGIN order_status_history_tpl -->	
	<tr>
		<td class="{td_class}">
		<span class="small">{status_date}</span>
		</td>
		<td class="{td_class}">
		{status_name}&nbsp;&nbsp;
		</td>
		<td class="{td_class}">
		<span class="small">{status_comment}</span>
		</td>
	</tr>
	<!-- END order_status_history_tpl -->	
	</table>	
	</td>
</tr>
</table>
<br />

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" value="endre status"/>
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	Avbrytknapp!
	</td>
</tr>
</table>

