<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{headline}</h1>
     </td>
     <td align="right">
	 <form action="/link/search/" method="post">
	       <input type="text" name="QueryString">
	       <input type="submit" name="search" value="{intl-search}">
         </form>
     </td>
</tr>
</table>

<hr noshade size="4">

<form method="post" action="/link/linkedit/{action_value}/{link_id}/">

<p class="error">{error_msg}</p>

<p class="boxtext">{intl-title}:</p>
<!-- {intl-titleedit} -->
<input type="text" name="Title" size="40" value="{title}">

<p class="boxtext">{intl-linkgroup}:</p>
<!-- {intl-choosegroup} -->
<select name="LinkGroupID">
	<option value="0">{intl-topcat}</option>
	<!-- BEGIN link_group_tpl -->
	<option {is_selected} value="{link_group_id}">{link_group_title}</option>
	<!-- END link_group_tpl -->
</select>

<p class="boxtext">{intl-url}:</p>
<!-- {intl-urledit} -->
http://<input type="text" name="Url" size="40" value="{url}">

<br />

<input class="stdbutton" type="submit" value="{intl-meta}" name="GetSite" />

<p class="boxtext">{intl-key}:</p>
<!-- {intl-search} -->
<textarea rows="5" cols="40" name="Keywords">{keywords}</textarea>

<br />

<p class="boxtext">{intl-desc}:</p>
<!-- {intl-discedit} -->
<textarea rows="5" cols="40" name="Description">{description}</textarea>
<br />

<p class="boxtext">{intl-accepted}</p>
<select name="Accepted">
	<option {no_selected} value="N">{intl-no}</option>
	<option	{yes_selected} value="Y">{intl-yes}</option>
</select>

<br /><br />

<hr noshade size="4"/>

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" value="{intl-ok}">
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form method="post" action="/link/group/">
    <input class="okbutton" type="submit" name="Back" value="{intl-back}">
	</form>
	</td>
</tr>
</table>

