<!-- orderlist.tpl --> 
<!-- $Id: orderedit.tpl,v 1.10 2000/12/08 11:22:05 pkej-cvs Exp $ -->

<h1>{intl-head_line}</h1>

<hr noshade="noshade" size="4" />

<h2>{intl-customerinfo}</h2>

{customer_first_name} {customer_last_name} 

<br />


<!-- BEGIN address_tpl -->
{street1}<br />
{street2}<br />
{zip} {place}<br />
{country}
<!-- END address_tpl -->

<br />

<br />
{customer_email}


<h2>{intl-payment_method}:</h2>

<!-- BEGIN visa_tpl -->
{intl-visa_text}
<!-- END visa_tpl -->

<!-- BEGIN mastercard_tpl -->
{intl-mastercard_text}
<!-- END mastercard_tpl -->

<!-- BEGIN cod_tpl -->
{intl-cod_text}
<!-- END cod_tpl -->

<!-- BEGIN invoice_tpl -->
{intl-invoice_text}
<!-- END invoice_tpl -->

<h2>{intl-productlist}</h2>

<!-- BEGIN order_item_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="3" border="0">
<tr>
	<th>{intl-image}:</th>
	<th>{intl-productname}:</th>
	<th>{intl-option}:</th>
	<th>{intl-count}:</th>
	<td align="right"><b>{intl-price}:</b></td>
</tr>
<!-- BEGIN order_item_tpl -->

<tr>
	<td class="{td_class}">
	<img src="{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/>
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
	<td class="boxtext">{intl-shipping}</td>
	<td align="right">{shipping_cost}</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
	<td class="boxtext">{intl-total}:</td>
	<td align="right">{order_sum}</td>
</tr>
</table>
<!-- END order_item_list_tpl -->

<h2>{intl-order_status}Ordrestatus</h2>

<table width="100%">
<tr>
	<td  width="50%" valign="top">
<form action="/trade/orderedit/{order_id}/newstatus/">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-choose_status}</p>
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
	<p class="boxtext">{intl-comments_for_status_chage}:</p>
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
		<th colspan="3">{intl-status_history}:</th>
	</tr>
	<!-- BEGIN order_status_history_tpl -->	
	<tr>
		<td class="{td_class}">
		<span class="small">{status_date}</span>&nbsp;
		</td>
		<td class="{td_class}">
		{status_name}&nbsp;&nbsp;
		</td>
		<td class="{td_class}">
		<span class="small">{status_comment}</span>&nbsp;
		</td>
		<td class="{td_class}">
		<span class="small">{admin_login}</span>&nbsp;
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
	<input class="okbutton" type="submit" value="{intl-button_change_status}"/>
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" name="Cancel" value="{intl-button_cancel}"/>
	</td>
</tr>
</table>

</form>
