<form method="post" action="/link/linkedit/{action_value}/{link_id}/">

<h1>{intl-headline}</h1>

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td bgcolor="#3c3c3c">
	<p class="white"><b>{intl-title}</b></p>
	</td>
</tr>

<tr>
	<td bgcolor="#f0f0f0">
	<br>
	{intl-titleedit}<br>
	<input type="text" name="title" value="{title}">
	<br><br>
	</td>
</tr>
</table>

<img src="/ezlink/images/1x1.gif" width="1" height="4" border="0"><br>

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td bgcolor="#3c3c3c">
	<p class="white"><b>{intl-linkgroup}</b></p>
	</td>
</tr>

<tr>
	<td bgcolor="#f0f0f0">
	<br>
	{intl-choosegroup}<br>
	<select name="linkgroup">
	<option value="0">{intl-topcat}</option>
	<!-- BEGIN link_group_tpl -->
	<option {is_selected} value="{link_id}">{link_title}</option>
	<!-- END link_group_tpl -->
	</select>
	<br><br>
	</td>
</tr>
</table>

<img src="/ezlink/images/1x1.gif" width="1" height="4" border="0"><br>

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td bgcolor="#3c3c3c">
	<p class="white"><b>{intl-url}</b></p>
	</td>
</tr>

<tr>
	<td bgcolor="#f0f0f0">
	<br>
	{intl-urledit}<br>
	http://<input type="text" name="url" value="{url}">
	<br><br>
	</td>
</tr>
</table>

<img src="/ezlink/images/1x1.gif" width="1" height="4" border="0"><br>

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td bgcolor="#3c3c3c">
	<p class="white"><b>{intl-key}</b></p>
	</td>
</tr>

<tr>
	<td bgcolor="#f0f0f0">
	<br>
	{intl-search}<br>
	<input type="text" name="keywords" value="{keywords}">
	<br><br>
	</td>
</tr>
</table>

<img src="/ezlink/images/1x1.gif" width="1" height="4" border="0"><br>

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td bgcolor="#3c3c3c">
	<p class="white"><b>{intl-desc}</b></p>
	</td>
</tr>

<tr>
	<td bgcolor="#f0f0f0">
	<br>
	{intl-discedit}<br>
	<textarea rows="5" name="description">{description}</textarea>
	<br><br>
	</td>
</tr>
</table>

<img src="/ezlink/images/1x1.gif" width="1" height="8" border="0"><br>

<table width="250" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td width="100">
	<p><b>{intl-accepted}</b></p>
	</td>
	<td>
	<select name="accepted">
	<option {no_selected} value="N">Nei</option>
	<option {yes_selected} value="Y">Ja</option>
	</select>
	</td>
</tr>
</table>
<br>

<input type="hidden" name="Action" value="{action_value}">
<input type="hidden" name="LID" value="{link_id}">
<input type="submit" value="{submit_text}">
</form>
