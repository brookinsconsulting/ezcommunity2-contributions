<form method="post" action="{www_dir}{index}/bug/support/category/delete/">

<h1>{intl-name}</h1>

<hr noshade="noshade" size="4" />
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN support_block_tpl -->
<tr>
    <td class={td_class}>
	<a href="{www_dir}{index}/bug/support/category/edit/{support_id}">{support_name}</a>
    </td>
    <td width="1%" class={td_class}>
	<a href="{www_dir}{index}/bug/support/category/edit/{support_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eff{support_id}-red','','/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="eff{support_id}-red" border="0" src="{www_dir}/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
    </td>
    <td class="{td_class}" width="1%" align="center">
	<input type="checkbox" name="SupportArrayID[]" value="{support_id}">
    </td>
</tr>
<!-- END support_block_tpl -->
</table>

<!-- BEGIN type_list_tpl -->
<table>
<tr>
        <!-- BEGIN type_list_previous_tpl -->
        <td>
        <a class="path" href="{www_dir}{index}/bug/support/category/list/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;|
        </td>
        <!-- END type_list_previous_tpl -->

        <!-- BEGIN type_list_previous_inactive_tpl -->
        <td>
        |
        </td>
        <!-- END type_list_previous_inactive_tpl -->

        <!-- BEGIN type_list_item_list_tpl -->

        <!-- BEGIN type_list_item_tpl -->
        <td>
        &nbsp;<a class="path" href="{www_dir}{index}/bug/support/category/list/{item_index}">{type_item_name}</a>&nbsp;|
        </td>
        <!-- END type_list_item_tpl -->

        <!-- BEGIN type_list_inactive_item_tpl -->
        <td>
        &nbsp;&lt;&nbsp;{type_item_name}&nbsp;&gt;&nbsp;|
        </td>
        <!-- END type_list_inactive_item_tpl -->

        <!-- END type_list_item_list_tpl -->

        <!-- BEGIN type_list_next_tpl -->
        <td>
        &nbsp;<a class="path" href="{www_dir}{index}/bug/support/category/list/{item_next_index}">{intl-next}&nbsp;&gt;&gt;</a>
        </td>
        <!-- END type_list_next_tpl -->

        <!-- BEGIN type_list_next_inactive_tpl -->
        <td>
        &nbsp;
        </td>
        <!-- END type_list_next_inactive_tpl -->
</tr>
</table>
<!-- END type_list_tpl -->

<hr noshade="noshade" size="4" />
<input class="stdbutton" type="submit" Name="Delete" value="{intl-delete}">
</form>
