<form method="post" action="{www_dir}{index}/trade/voucherlist/">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td><h1>{intl-voucher_list}</h1></td>
    <td align="right">
      <input type="text" name="QueryText" value="{search}" />
      <input class="stdbutton" type="submit" value="{intl-search}" />
    </td>
  </tr>
</table>
				 
<hr noshade="noshade" size="4" />

<!-- BEGIN voucher_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<tr>
	<th>{intl-key}:</th>
	<th>{intl-receiver}:</th>
	<th>{intl-price}:</th>
	<th>{intl-available}:</th>
	<th>{intl-created}:</th>	
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>

<!-- BEGIN voucher_item_tpl -->
<tr>
	<td class="{td_class}">
	<a href="{www_dir}{index}/trade/voucheredit/edit/{voucher_id}/">{voucher_key}&nbsp;</a>
	</td>
	<td class="{td_class}">
	{voucher_receiver}&nbsp;
	</td>
	<td class="{td_class}">
	{voucher_price}&nbsp;
	</td>
	<td class="{td_class}">
	<!-- BEGIN voucher_is_available_tpl -->
	{intl-is_available}&nbsp;
	<!-- END voucher_is_available_tpl -->
	<!-- BEGIN voucher_is_not_available_tpl -->
	{intl-is_not_available}&nbsp;
	<!-- END voucher_is_not_available_tpl -->
	</td>
	<td class="{td_class}">
	{voucher_created}
	</td>
	<td width="1%" class="{td_class}">
	<a href="{www_dir}{index}/trade/voucheredit/edit/{voucher_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztc{voucher_id}-red','','/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="eztc{voucher_id}-red" border="0" src="{www_dir}/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top"></a>
	</td>
	<td width="1%" class="{td_class}">
	<input type="checkbox" name="DeleteArrayID[]" value="{voucher_id}" />
	</td>
</tr>
<!-- END voucher_item_tpl -->
</table>
<!-- END voucher_list_tpl -->

<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
        <!-- BEGIN type_list_previous_tpl -->
        <td>
        <a class="path" href="{www_dir}{index}/trade/voucherlist/{item_previous_index}/?URLQueryString={url_query_string}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
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
        |&nbsp;<a class="path" href="{www_dir}{index}/trade/voucherlist/{item_index}/?URLQueryString={url_query_string}">{type_item_name}</a>&nbsp;
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
        |&nbsp;<a class="path" href="{www_dir}{index}/trade/voucherlist/{item_next_index}/?URLQueryString={url_query_string}">{intl-next}&nbsp;&gt;&gt;</a>
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


<hr noshade="noshade" size="4" />
<input class="stdbutton" type="submit" name="Delete" value="{intl-delete_selected}" />
</form>







