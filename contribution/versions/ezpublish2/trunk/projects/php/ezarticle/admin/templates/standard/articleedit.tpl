<form method="post" action="/article/articleedit/{action_value}/{article_id}/" >

<h1>{intl-head_line}</h1>

<!-- BEGIN error_message_tpl -->
<h3 class="error">{intl-error_parsing_xml}</h3>
<!-- END error_message_tpl -->

<hr noshade="noshade" size="4" />


<p class="boxtext">{intl-article_name}:</p>
<input class="box" type="text" name="Name" size="40" value="{article_name}" />
<br /><br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<p class="boxtext">{intl-article_author}:</p>

	<select name="ContentsWriterID">
	
	<!-- BEGIN author_item_tpl -->
	<option value="{author_id}" {selected}>{author_name}</option>
	<!-- END author_item_tpl -->
	
	</select>

	</td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td valign="top">

	<p class="boxtext">{intl-category}:</p>
	
	<select name="CategoryID">

	<!-- BEGIN value_tpl -->
	<option value="{option_value}" {selected}>{option_name}</option>
	<!-- END value_tpl -->
	
	</select>

	<p class="boxtext">{intl-article_topic}:</p>

	<select name="TopicID">

	<option value="0">{intl-no_topic}</option>
	
	<!-- BEGIN topic_item_tpl -->
	<option value="{topic_id}" {selected}>{topic_name}</option>
	<!-- END topic_item_tpl -->
	
	</select>

	</td>	
	<td>&nbsp;</td>
	<td valign="top">
	<p class="boxtext">{intl-additional_category}:</p>
	
	<select multiple size="{num_select_categories}" name="CategoryArray[]">
	
	<!-- BEGIN multiple_value_tpl -->
	<option value="{option_value}" {multiple_selected}>{option_level}{option_name}</option>
	<!-- END multiple_value_tpl -->
	
	</select>
	</td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td align="top">
	<p class="boxtext">{intl-groups}:</p>
	<select name="GroupArray[]" size="3" multiple>
	<option value="0" {all_selected}>{intl-all}</option>
	<!-- BEGIN group_item_tpl -->
	<option value="{group_id}" {selected}>{group_name}</option>
	<!-- END group_item_tpl -->
	</select>
	</td>	
	<td>&nbsp;</td>
	<td>
	<p class="boxtext">{intl-groups_write}:</p>
	<select name="WriteGroupArray[]" size="3" multiple>
	<option value="0" {all_write_selected}>{intl-all}</option>
	<!-- BEGIN category_owner_tpl -->
	<option value="{module_owner_id}" {is_selected}>{module_owner_name}</option>
	<!-- END category_owner_tpl -->
	</select>
	</td>
</tr>
</table>

<p class="boxtext">{intl-keywords}:</p>
<input class="box" type="text" name="Keywords" size="40" value="{article_keywords}" />
<br /><br />

<p class="boxtext">{intl-intro}:</p>
<textarea class="box" name="Contents[]" cols="40" rows="5" wrap="soft">{article_contents_0}</textarea>
<br /><br />

<p class="boxtext">{intl-contents}:</p>
<textarea class="box" name="Contents[]" cols="40" rows="20" wrap="soft">{article_contents_1}</textarea>
<br /><br />

<p class="boxtext">{intl-log_message}:</p>
<textarea class="box" name="LogMessage" cols="40" rows="2" wrap="soft"></textarea>

<p class="boxtext">{intl-link_text}:</p>
<input class="box" type="text" name="LinkText" size="20" value="{link_text}" />
<br /><br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td width="50%" colspan="5">
	<p class="boxtext">{intl-start_date}:</p>
	</td>
	<td width="50%" colspan="5">
	<p class="boxtext">{intl-stop_date}:</p>
	</td>
</tr>
<tr>
	<td >
	<span class="small">{intl-day}:</span>
	</td>
	<td >
	<span class="small">{intl-month}:</span>
	</td>
	<td >
	<span class="small">{intl-year}:</span>
	</td>

	<td >
	<span class="small">{intl-hour}:</span>
	</td>
	<td >
	<span class="small">{intl-minute}:</span>
	</td>

	<td>
	<span class="small">{intl-day}:</span>
	</td>
	<td>
	<span class="small">{intl-month}:</span>
	</td>
	<td>
	<span class="small">{intl-year}:</span>
	</td>

	<td>
	<span class="small">{intl-hour}:</span>
	</td>
	<td>
	<span class="small">{intl-minute}:</span>
	</td>
</tr>
<tr>
	<td>
	<input type="text" size="2" name="StartDay" value="{start_day}" />&nbsp;
	</td>
	<td>
	<input type="text" size="2" name="StartMonth" value="{start_month}" />&nbsp;
	</td>
	<td>
	<input type="text" size="4" name="StartYear" value="{start_year}" />
	</td>
	<td>
	<input type="text" size="2" name="StartHour" value="{start_hour}" />
	</td>
	<td>
	<input type="text" size="2" name="StartMinute" value="{start_minute}" />
	</td>

	<td>
	<input type="text" size="2" name="StopDay" value="{stop_day}" />&nbsp;
	</td>
	<td>
	<input type="text" size="2" name="StopMonth" value="{stop_month}" />&nbsp;
	</td>
	<td>
	<input type="text" size="4" name="StopYear" value="{stop_year}" />
	</td>
	<td>
	<input type="text" size="2" name="StopHour" value="{start_hour}" />
	</td>
	<td>
	<input type="text" size="2" name="StopMinute" value="{start_minute}" />
	</td>
</tr>
</table>
<br /><br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td width="50%">
	<input type="checkbox" name="IsPublished" {article_is_published} />
	<span class="boxtext">{intl-article_is_published}</span><br />
	</td>
	<td width="50%">
	<input type="checkbox" name="Discuss" {discuss_article} />
	<span class="boxtext">{intl-discuss_article}</span><br />
	</td>
</tr>
</table>
<br />
<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="Image" value="{intl-pictures}" />
<input class="stdbutton" type="submit" name="File" value="{intl-files}" />
<input class="stdbutton" type="submit" name="Attribute" value="{intl-attributes}" />
<input class="stdbutton" type="submit" name="Preview" value="{intl-preview}" />
&nbsp;
<input class="stdbutton" type="submit" name="Log" value="{intl-log_history}" />

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" value="{intl-ok}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form method="post" action="/article/articleedit/cancel/{article_id}/">
	<input class="okbutton" type="submit" value="{intl-cancel}" />	
	</form>
	</td>
</tr>
</table>


