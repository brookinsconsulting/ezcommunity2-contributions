<form method="post" action="/link/groupedit/{action_value}/{linkgroup_id}/">

<h1>{message}</h1>

<hr noshade size="4"/>

<p class="boxtext">{intl-name}</p>
<input type="text" name="title" size="40" value="{title}">

<p class="boxtext">{intl-where}</p>
<select name="ParentCategory">
<option value="0">{intl-topcat}</option>
<!-- BEGIN parent_category_tpl -->
<option {is_selected} value="{grouplink_id}">{grouplink_title}</option>
<!-- END parent_category_tpl -->
</select>

<br />

<hr noshade size="4"/>

<input type="hidden" name="Action" value="{action_value}">
<input type="hidden" name="LGID" value="{linkgroup_id}">
<input class="okbutton" type="submit" value="{intl-submit_text}">
<form method="post" action="/link/grouplist/"><input class="okbutton" type="submit" name="Back" value="{intl-back}"></form>

</form>
