<h2>{article_name}</h2><br />
{intl-article_author}: {author_text} <br />

<p>
{article_body}
</p>



<!-- BEGIN prev_page_link_tpl -->
<a href="/article/articleview/{article_id}/{prev_page_number}/">{intl-prev_page}</a>
<!-- END prev_page_link_tpl -->

<!-- BEGIN page_link_tpl -->
| <a href="/article/articleview/{article_id}/{page_number}/">{page_number}</a>	
<!-- END page_link_tpl -->


<!-- BEGIN next_page_link_tpl -->
| <a href="/article/articleview/{article_id}/{next_page_number}/">{intl-next_page}</a>
<!-- END next_page_link_tpl -->