<form action="{www_dir}{index}/form/report/setup/store/{report_id}/{table_id}" method="post">

<h1>{intl-form_setup}</h1>

<hr noshade="noshade" size="4" />

<table width="100%" cellpadding="4" cellspacing="0" border="0">
        <th>{intl-element_name}:</th>
	<th>&nbsp;</th>
	<th>{intl-element_type}:</th>
	<th>&nbsp;</th>
        <th>{intl-statistics_type}:</th>
	<th>&nbsp;</th>
<!-- BEGIN form_element_tpl -->
<tr>
   <td class="{td_class}">{element_name}</td>
   <td class="{td_class}">&nbsp;</td>
   <td class="{td_class}">{element_type}</td>
   <td class="{td_class}">&nbsp;</td>
   <td class="{td_class}">
   <select name="StatisticsType{element_id}">
<!-- BEGIN statistics_type_tpl -->
   <option value="{statistics_id}" {selected}>{statistics_name}</option>
<!-- END statistics_type_tpl -->
   </select>
   </td>
   <td class="{td_class}">
<!-- BEGIN table_item_tpl -->
   <a href="{www_dir}{index}/form/report/setup/{report_id}/{element_id}/">{intl-edit_table}</a>
<!-- END table_item_tpl -->
   </td>
</tr>
<!-- END form_element_tpl -->
</table>
<hr noshade="noshade" size="4" />
<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
</form>