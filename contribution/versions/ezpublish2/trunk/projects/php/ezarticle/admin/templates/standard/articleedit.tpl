<form method="post" action="/article/articleedit/{action_value}/{article_id}/" >

<h1>{intl-head_line}</h1>

<hr noshade="noshade" size="4" />

<br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">{intl-article_name}</p>
	<input type="text" name="Name" size="20" value="{article_name}" />
	</td>
	<td>
	<p class="boxtext">{intl-article_author}</p>
	<input type="text" name="AuthorText" size="20" value="{author_text}" />
	</td>
</tr>
</table>

<p class="boxtext">{intl-category}</p>
<select name="CategoryID">

<!-- BEGIN value_tpl -->
<option value="{option_value}" {selected}>{option_name}</option>
<!-- END value_tpl -->

</select>

<p class="boxtext">{intl-intro}</p>
<textarea name="Contents[]" cols="60" rows="5">{article_contents_0}</textarea>
<br /><br />

<p class="boxtext">{intl-contents}</p>
<textarea name="Contents[]" cols="60" rows="10">{article_contents_1}</textarea>
<br /><br />

<p class="boxtext">{intl-link_text}</p>
<input type="text" name="LinkText" size="20" value="{link_text}" />
<br /><br />

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="Image" value="{intl-pictures}" />
<input class="stdbutton" type="submit" name="Preview" value="{intl-preview}" />

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input  class="okbutton" type="submit" value="OK" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	Avbrytknapp
	</td>
</tr>
</table>
	
