<!-- BEGIN error_list_tpl -->
<h2 class="error">{intl-error_elements}</h2>
<hr noshade="noshade" size="4" />
<br />
<!-- END error_list_tpl -->


<!-- BEGIN no_elements_item_tpl -->
<div class="error">{intl-no_elements_exist}</div>
<!-- END no_elements_item_tpl -->

<!-- BEGIN element_list_tpl -->
<table width="100%" class="list" border="0" cellspacing="0" cellpadding="4">
	<th>{intl-element_name}:</th>
	<th>&nbsp;</th>
	<th>{intl-element_type}:</th>
	<th>&nbsp;</th>
	<th>{intl-size}</th>
	<th>{intl-break}</th>
	<th>{intl-element_required}:</th>
	<th colspan="3">&nbsp;</th>

<!-- BEGIN element_item_tpl -->
<tr>
    <td class="{td_class}"><input type="hidden" name="elementID[]" value="{element_id}"><input type="text" class="halfbox" size="20" name="elementName[]" value="{element_name}"></td>
    <td class="{td_class}">&nbsp;</td>
    <td class="{td_class}"><select name="elementTypeID[]">
    <option value="0">{intl-select_type}</option>
<!-- BEGIN typelist_item_tpl -->
    <option value="{element_type_id}" {selected}>{intl-{element_type_name}}</option>
<!-- END typelist_item_tpl -->
    </select>

    <td class="{td_class}">&nbsp;
    <!-- BEGIN fixed_values_tpl -->
    <a href="{www_dir}{index}/form/form/fixedvalues/{form_id}/{page_id}/{element_id}/">{intl-fixed_values}</a>
    <!-- END fixed_values_tpl -->
    <!-- BEGIN table_edit_tpl -->
    <a href="{www_dir}{index}/form/form/tableedit/{form_id}/{page_id}/{element_id}/">{intl-edit_table}</a>
    <!-- END table_edit_tpl -->
    <!-- BEGIN text_block_edit_tpl -->
    <a href="{www_dir}{index}/form/form/textedit/{form_id}/{page_id}/{element_id}/{table_id}?From={this_page}">{intl-edit_text_block}</a>
    <!-- END text_block_edit_tpl -->
    <!-- BEGIN numerical_edit_tpl -->
    <a href="{www_dir}{index}/form/form/numericaledit/{form_id}/{page_id}/{element_id}/{table_id}?From={this_page}">{intl-edit_numerical}</a>
    <!-- END numerical_edit_tpl -->
    </td>

    <td class="{td_class}">&nbsp;
    <!-- BEGIN size_tpl -->
    <input type="text" size="3" name="Size[{element_nr}]" value="{element_size}" />
    <!-- END size_tpl -->
    <!-- BEGIN table_size_tpl -->
    x<input type="text" size="3" name="Rows[{element_nr}]" value="{element_rows}" />
    <!-- END table_size_tpl -->
    &nbsp;
    </td>

    <td class="{td_class}">&nbsp;
    <!-- BEGIN break_tpl -->
    <input type="checkbox" {element_is_breaking} name="ElementBreak[]" value="{element_id}" />
    <!-- END break_tpl -->
    </td>

    <td width="1%" class="{td_class}" align="center">
        <input type="checkbox" {element_required} name="elementRequired[]" value="{element_id}" />
    </td>
    
<!-- BEGIN item_move_down_tpl -->
	<td width="1%" class="{td_class}">
        <a href="{www_dir}{index}/form/form/{element_page}/{form_id}/{page_id}/{table_id}/down/?ElementID={element_id}"><img src="{www_dir}/admin/images/move-down.gif" height="12" width="12" border="0" alt="{intl-move_up}" /></a>
    </td>
<!-- END item_move_down_tpl -->

<!-- BEGIN no_item_move_down_tpl -->
	<td width="1%" class="{td_class}">&nbsp;</td>
<!-- END no_item_move_down_tpl -->

<!-- BEGIN item_separator_tpl -->

<!-- END item_separator_tpl -->
<!-- BEGIN no_item_separator_tpl -->

<!-- END no_item_separator_tpl -->

<!-- BEGIN item_move_up_tpl -->
	<td width="1%" class="{td_class}">
        <a href="{www_dir}{index}/form/form/{element_page}/{form_id}/{page_id}/{table_id}/up/?ElementID={element_id}"><img src="{www_dir}/admin/images/move-up.gif" height="12" width="12" border="0" alt="{intl-move_down}" /></a>
    </td>
<!-- END item_move_up_tpl -->
<!-- BEGIN no_item_move_up_tpl -->
	<td width="1%" class="{td_class}">&nbsp;</td>
<!-- END no_item_move_up_tpl -->
    <td class="{td_class}" >
        <input type="checkbox" name="elementDelete[]" value="{element_id}" />
    </td>
</tr>
<!-- END element_item_tpl -->
</table>

<!-- END element_list_tpl -->

