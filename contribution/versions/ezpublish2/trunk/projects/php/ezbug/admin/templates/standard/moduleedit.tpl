<form method="post" action="/bug/module/{action_value}/{module_id}/">
<h1>{intl-headline}</h1>

<hr noshade size="4"/>

<p class="boxtext">{intl-name}:</p>
<input type="text" name="Name" value="{module_name}">

<p class="boxtext">{intl-module}:</p>
<select name="ParentID">
<option value="0">{intl-topcat}</option>
<!-- BEGIN module_item_tpl -->
<option value="{module_parent_id}" {is_selected}>{module_parent_name}</option>
<!-- END module_item_tpl -->
</select>

<p class="boxtext">{intl-owner}</p>
<select name="OwnerID">
<option value="0">{intl-none}</option>
<!-- BEGIN module_owner_tpl -->
<option value="{module_owner_id}" {is_selected}>{module_owner_name}</option>
<!-- END module_owner_tpl -->
</select>

<br /><br />

<hr noshade size="4"/>

<input class="okbutton" type="submit" value="{intl-ok}">
</form>