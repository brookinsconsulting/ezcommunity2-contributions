<form method="post" action="/link/linkedit/{action_value}/{link_id}/">

<h1>{intl-headline}</h1>

<hr noshade size="4"/>

<p class="boxtext">{intl-title}</p>
<!-- {intl-titleedit} -->
<input type="text" name="title" size="40" value="{title}">

<p class="boxtext">{intl-linkgroup}</p>
<!-- {intl-choosegroup} -->
<select name="linkgroup">
<option value="0">{intl-topcat}</option>
<!-- BEGIN link_group_tpl -->
<option {is_selected} value="{link_id}">{link_title}</option>
<!-- END link_group_tpl -->
</select>

<p class="boxtext">{intl-url}</p>
<!-- {intl-urledit} -->
http://<input type="text" name="url" size="40" value="{url}">

<p class="boxtext">{intl-key}</p>
<!-- {intl-search} -->
<textarea rows="5" cols="40" name="keywords">{keywords}</textarea>

<br></br>

<p class="boxtext">{intl-desc}</p>
<!-- {intl-discedit} -->
<textarea rows="5" cols="40" name="description">{description}</textarea>
<br></br>

<p class="boxtext">{intl-accepted}</p>
<select name="accepted">
<option {no_selected} value="N">Nei</option>
<option {yes_selected} value="Y">Ja</option>
</select>

<br></br>

<hr noshade size="4"/>

<input type="hidden" name="Action" value="{action_value}">
<input type="hidden" name="LID" value="{link_id}">
<input class="okbutton" type="submit" value="OK">

<form method="post" action="/link/grouplist/"><input class="okbutton" type="submit" name="Back" value="{intl-back}"></form>

</form>
