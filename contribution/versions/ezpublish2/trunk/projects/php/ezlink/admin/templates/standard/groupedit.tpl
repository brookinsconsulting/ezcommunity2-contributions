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

<form method="post" action="/link/groupedit/{action_value}/{linkgroup_id}/">

<p class="error">{error_msg}</p>

<p class="boxtext">{intl-name}:</p>
<input type="text" name="Title" size="40" value="{title}">

<p class="boxtext">{intl-where}:</p>
<select name="ParentCategory">
<option value="0">{intl-topcat}</option>
<!-- BEGIN parent_category_tpl -->
<option {is_selected} value="{grouplink_id}">{grouplink_title}</option>
<!-- END parent_category_tpl -->
</select>

<br /><br />

<hr noshade size="4"/>

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" value="{intl-submit_text}">
	</td>
	<td>&nbsp;</td>
	</form>
	<td>
	<form method="post" action="/link/group/">
	<input class="okbutton" type="submit" name="Back" value="{intl-back}">
	</form>
	</td>
</tr>

</table>
	
