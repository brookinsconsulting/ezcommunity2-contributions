<form method="post" action="/quiz/game/edit/{game_id}/">

<h1>{intl-headline}</h1>

<hr noshade="noshade" size="4" />
<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-name}:</p>
	<input type="text" size="30" name="Name" value="{game_name}" />
	</td>	
</tr>
<tr>
	<td>
	<p class="boxtext">{intl-start_date}:</p>
	<input type="text" size="2" name="StartMonth" value="{start_month}" />&nbsp;
	<input type="text" size="2" name="StartDay" value="{start_day}" />
	</td>
</tr>
<tr>
	<td>
	<p class="boxtext">{intl-stop_date}:</p>
	<input type="text" size="2" name="StopMonth" value="{stop_month}" />&nbsp;
	<input type="text" size="2" name="StopDay" value="{stop_day}" />
	</td>
</tr>
<tr>
	<td>
	<br />
	<p class="boxtext">{intl-description}:</p>
	<textarea name="Description" wrap="soft" cols="30" rows="10">{game_description}</textarea>
	</td>	
</tr>
</table>

<br />

<!-- BEGIN question_list_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
         <th>{intl-questions}</th>
</tr>
<!-- BEGIN question_item_tpl -->
<tr>
         <td>
	<a href="/quiz/game/questionedit/{question_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezquiz{game_id}-red','','/admin/images/{site_style}/redigerminimrk.gif',1)"><img name="ezquiz{game_id}-red" border="0" src="/admin/images/{site_style}/redigermini.gif" width="16" height="16" align="top" alt="Edit" /></a>
	 {question_name}
	 </td>
</tr>
<!-- END question_item_tpl -->

</table>
<!-- END question_list_tpl -->

<hr noshade="noshade" size="4" />

<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />&nbsp;
<input class="okbutton" type="submit" name="Update" value="{intl-update}" />&nbsp;
<input class="okbutton" type="submit" name="NewQuestion" value="{intl-new_question}" />&nbsp;
<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />

</form>
	
