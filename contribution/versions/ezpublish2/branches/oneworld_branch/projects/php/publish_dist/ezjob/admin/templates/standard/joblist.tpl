<h1>{intl-headline}</h1>

<hr noshade size="4" />

<br />
<form method="post" action="{www_dir}{index}/job/joblist/{offset}">

<!-- BEGIN name_list_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th colspan="3">{intl-name}:</th>
</tr>
<!-- BEGIN name_item_tpl -->
<tr class="{bg_color}">
  <td width="98%">{job_name}</td>
  <td width="1%"><a href="{www_dir}{index}/job/jobedit/{job_id}/{offset}" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezaa{job_id}-red','','{www_dir}/admin/images/redigerminimrk.gif',1)"><img name="ezaa{job_id}-red" border="0" src="{www_dir}/admin/images/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
</td>
  <td width="1%"><input type="checkbox" name="JobArrayID[]" value="{job_id}"></td>
</tr>
<!-- END name_item_tpl -->
</table>
<!-- END name_list_tpl -->
<br />
<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
</form>