<h1>{intl-head_line} - {current_letter}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN index_item_tpl -->
	<div class="spacer"><div class="path">{index_name}:</div></div>
	<!-- BEGIN article_item_tpl -->
	<!-- BEGIN comma_item_tpl -->
	<!-- END comma_item_tpl -->
	<a href="{www_dir}{index}/article/articleview/{article_id}/{article_page}/{article_category}">{article_name}</a>
	<!-- END article_item_tpl -->
<!-- END index_item_tpl -->
<hr noshade="noshade" size="4" />

<!-- BEGIN letter_item_tpl -->
	<a href="{www_dir}{index}/article/index/{letter}/">{letter}</a>
	<!-- END letter_item_tpl -->