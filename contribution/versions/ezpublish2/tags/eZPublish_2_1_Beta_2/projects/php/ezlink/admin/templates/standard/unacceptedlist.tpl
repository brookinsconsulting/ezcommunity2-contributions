<h1>{intl-unaccepted_links} - ({link_count})</h1>

<hr noshade="noshade" size="4" />

<form method="post" action="/link/unacceptededit/">

<table cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN link_item_tpl -->
<tr>
    <td>
        <div class="boxtext">{intl-name}:</div>
        <input type="text" size="30" name="Name[]" value="{link_name}" />
    </td>
</tr>

<tr>
    <td>
        <div class="boxtext">{intl-category}:</div>
	<select name="LinkGroupID[]">
	<!-- BEGIN category_item_tpl -->
	<option {is_selected} value="{link_group_id}">{option_level}{link_group_title}</option>
	<!-- END category_item_tpl -->
	</select>
    </td>
</tr>

<tr>
    <td>
        <a href="http://{link_url}" target="_blank"><div class="boxtext">{intl-url}:</div></a>
        <input type="text" size="30" name="Url[]" value="{link_url}" />
    </td>
</tr>

<tr>
    <td colspan="2">
        <div class="boxtext">{intl-keywords}:</div>
        <textarea cols="40" rows="4" name="Keywords[]">{link_keywords}</textarea>
    </td>
</tr>

<tr>
    <td>
        <div class="boxtext">{intl-description}:</div>
        <textarea cols="40" rows="4" name="Description[]">{link_description}</textarea>
    </td>
</tr>


<tr>
    <td colspan="2">
        <div class="boxtext">{intl-action}:</div>
	<select name="ActionValueArray[{i}]">
        <option value="Defer" selected/>{intl-defer}</option>
        <option value="Accept">{intl-accept}</option>
        <option value="Update">{intl-update_not_accept}</option>
        <option value="Delete">{intl-delete}</option>
	</select>
    </td>
</tr>
<tr>
    <td colspan="2">
    &nbsp;
    </td>
</tr>

<input type="hidden" name="LinkArrayID[]" value="{link_id}">

<hr noshade="noshade" size="4" />

<br />

<!-- END link_item_tpl -->
</table>

<input class="stdbutton" type="submit" value="{intl-update}">

</form>

