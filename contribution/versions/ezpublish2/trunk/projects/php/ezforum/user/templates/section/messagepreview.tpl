<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f08c00">
<tr>
    <td class="tdmini"><img src="/images/1x1.gif" width="1" height="38"></td>
</tr>
<tr>
	<td class="toppathbottom"><img src="/images/1x1.gif" width="1" height="2"><br /></td>
</tr>	
</table>

<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td align="left" valign="bottom">
        <h1>{intl-preview_headline}</h1>
    </td>
    <td align="right">
    <td align="right">
	 <form action="/forum/search/" method="post">
	       <input type="text" name="QueryString" size="12" />
	       <input class="stdbutton" type="submit" name="search" value="{intl-search}" />
         </form>
     </td>
</tr>
</table>

{message_path_file}

<br />

{message_body_file}

<form method="post" action="/forum/messageedit/{action_value}/{message_id}">
{message_hidden_form_file}

    <input class="stdbutton" type="submit" name="EditButton" value="{intl-edit}" />

	<hr noshade="noshade" size="4" />
    
	<input class="okbutton" type="submit" name="PostButton" value="{intl-post}" />
    &nbsp;
	<input class="okbutton" type="submit" name="CancelButton" value="{intl-cancel}" />
</form>
