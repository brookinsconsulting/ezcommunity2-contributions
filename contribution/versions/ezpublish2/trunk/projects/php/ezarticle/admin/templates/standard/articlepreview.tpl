<h1>{intl-head_line}</h1>

<hr noshade="noshade" size="4" />

<h2>{article_name}</h2><br />
{intl-article_author}: {author_text} <br />


<p>
{article_body}
</p>

{intl-link_text}: {link_text}

<hr noshade="noshade" size="4" />

<form action="/article/articleedit/edit/{article_id}/" method="post">
<input class="okbutton" type="submit" value="{intl-edit}" />
</form>

<form action="/article/articleedit/delete/{article_id}/" method="post">
<input class="okbutton" type="submit" value="{intl-delete}" />
</form>


