<form method="post" action="/link/groupedit/{action_value}/{linkgroup_id}/">

<h1>{message}</h1>

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td bgcolor="#3c3c3c">
	<p class="white"><b>{intl-where}</b></p>
	</td>
</tr>

<tr>
	<td bgcolor="#f0f0f0">
	<br>
	<select name="ParentCategory">
	<option value="0">{intl-topcat}</option>
	<!-- BEGIN parent_category_tpl -->
	<option {is_selected} value="{grouplink_id}">{grouplink_title}</option>
	<!-- END parent_category_tpl -->
	</select>
	<br>
	</td>
</tr>
<tr>
         <td bgcolor="#f0f0f0">
         <br>
         </td>
 </tr>
 </table>

<img src="/ezlink/images/1x1.gif" width="1" height="4" border="0"><br>

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td bgcolor="#3c3c3c">
	<p class="white"><b>{intl-name}</b></p>
	</td>
</tr>

<tr>
	<td bgcolor="#f0f0f0">
	<br>
	<input type="text" name="title" value="{title}"><br>
	</td>
</tr>
<tr>
         <td bgcolor="#f0f0f0">
         <br>
         </td>
</tr>
</table>

<img src="/ezlink/images/1x1.gif" width="1" height="4" border="0"><br>

<input type="hidden" name="Action" value="{action_value}">
<input type="hidden" name="LGID" value="{linkgroup_id}">
<input type="submit" value="{intl-submit_text}">


</form>
