<!-- BEGIN no_page_items_tpl -->
<div class="error">{intl-no_pages_exist}</div>
<!-- END no_page_items_tpl -->

<!-- BEGIN page_list_tpl -->
<table width="100%" class="list" border="0" cellspacing="0" cellpadding="4">
<tr>
	<th width="100%" colspan="5">{intl-page_name}:</th>
</tr>
<!-- BEGIN page_item_tpl -->
<tr>
    <td class="{td_class}" width="96%">{page_name}<input type="hidden" name="PageID[]" value="{page_id}"></td>
    <td class="{td_class}" width="1%"><a href="{www_dir}{index}/form/form/{action_value}/pagelist/?MovePageDown={page_id}"><img src="{www_dir}/admin/images/{site_style}/move-down.gif" height="12" width="12" border="0" alt="Down" /></a></td>
    <td class="{td_class}" width="1%"><a href="{www_dir}{index}/form/form/{action_value}/pagelist/?MovePageUp={page_id}"><img src="{www_dir}/admin/images/{site_style}/move-up.gif" height="12" width="12" border="0" alt="Up" /></a></td>
    <td class="{td_class}" width="1%"><a href="{www_dir}{index}/form/form/pageedit/{form_id}/{page_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezac{page_id}-red','','{www_dir}/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezac{page_id}-red" border="0" src="{www_dir}/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top"></a></td>
    <td class="{td_class}" width="1%"><input type="checkbox" name="DeletePageArrayID[]" value="{page_id}"></td>
</tr>
<!-- END page_item_tpl -->
</table>

<!-- END page_list_tpl -->

