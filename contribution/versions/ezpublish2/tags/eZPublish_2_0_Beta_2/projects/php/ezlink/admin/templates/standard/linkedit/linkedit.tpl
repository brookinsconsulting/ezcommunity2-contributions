<form method="post" action="/link/linkedit/{action_value}/{link_id}/">

<h1>{intl-headline}</h1>

<hr noshade size="4"/>

<p class="error">{error_msg}</p>

<p class="boxtext">{intl-title}</p>
<!-- {intl-titleedit} -->
<input type="text" name="Title" size="40" value="{title}">

<p class="boxtext">{intl-linkgroup}</p>
<!-- {intl-choosegroup} -->
<select name="LinkGroupID">
	<option value="0">{intl-topcat}</option>
	<!-- BEGIN link_group_tpl -->
	<option {is_selected} value="{link_group_id}">{link_group_title}</option>
	<!-- END link_group_tpl -->
</select>

<p class="boxtext">{intl-url}</p>
<!-- {intl-urledit} -->
http://<input type="text" name="Url" size="40" value="{url}">

<p class="boxtext">{intl-key}</p>
<!-- {intl-search} -->
<textarea rows="5" cols="40" name="Keywords">
	  {keywords}
</textarea>

<br />

<p class="boxtext">{intl-desc}</p>
<!-- {intl-discedit} -->
<textarea rows="5" cols="40" name="Description">
	  {description}
</textarea>
<br />

<p class="boxtext">{intl-accepted}</p>
<select name="Accepted">
	<option {no_selected} value="N">Nei</option>
	<option	{yes_selected} value="Y">Ja</option>
</select>

<br /><br />

<hr noshade size="4"/>

<input class="okbutton" type="submit" value="{intl-ok}">

<form method="post" action="/link/group/">
      <input class="okbutton" type="submit" name="Back" value="{intl-back}">
</form>

</form>
