<h1>{intl-unaccepted_links}</h1>

<hr noshade="noshade" size="4" />

<form metdod="post" action="/link/unacceptededit/">

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
        <div class="boxtext">{intl-url}:</div>
        <input type="text" size="30" name="Url[]" value="{link_url}" />
    </td>
</tr>
<tr>
    <td>
        <div class="boxtext">{intl-description}:</div>
        <textarea cols="40" rows="4" name="Description[]">{link_description}</textarea>
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
    <td colspan="2">
        <div class="boxtext">{intl-keywords}:</div>
        <input type="text" name="Keywords[]" value="{link_keywords}" />
    </td>
</tr>

<tr>
    <td colspan="2">
        <div class="boxtext">{intl-action}:</div>
        {intl-defer}: <input value="Defer" type="radio" name="ActionValueArray[{i}]" checked/>
        {intl-accept}: <input value="Accept" type="radio" name="ActionValueArray[{i}]" />
        {intl-update_not_accept}: <input value="Update" type="radio" name="ActionValueArray[{i}]" />
        {intl-delete}: <input value="Delete" type="radio" name="ActionValueArray[{i}]" />
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

