<h1>{intl-head_line}</h1>

<hr noshade="noshade" size="4" />

<form method="post" action="/article/articleedit/{action_value}/{article_id}/" >

{intl-article_name}:<br />
<input type="text" name="Name" size="20" value="{article_name}" /><br />

{intl-article_author}:<br />
<input type="text" name="AuthorText" size="20" value="{author_text}" /><br />

{intl-link_text}:<br />
<input type="text" name="LinkText" size="20" value="{link_text}" /><br />

<select name="CategoryID">
<!-- BEGIN value_tpl -->
<option value="{option_value}" {selected}>{option_name}</option>
<!-- END value_tpl -->
</select> <br />

{intl-intro}:<br />
<textarea name="Contents[]" cols="40" rows="5">{article_contents_0}</textarea><br />

{intl-contents}:<br />
<textarea name="Contents[]" cols="40" rows="10">{article_contents_1}</textarea>

<hr noshade="noshade" size="4" />
<input class="stdbutton" type="submit" name="Image" value="{intl-pictures}" />
<input class="stdbutton" type="submit" name="Preview" value="{intl-preview}" />
<hr noshade="noshade" size="4" />
<input  class="okbutton" type="submit" value="OK" />

</form>