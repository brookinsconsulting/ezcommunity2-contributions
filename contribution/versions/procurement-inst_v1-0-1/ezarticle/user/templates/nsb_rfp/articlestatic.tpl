<!-- BEGIN article_header_tpl -->
	<p class="byline">{intl-article_author}: <a class="byline" href="{www_dir}{index}/article/author/view/{author_id}">{author_text}</a></p>
	<p class="byline">{intl-article_date}: {article_created}</p>
<!-- END article_header_tpl -->

<!-- BEGIN article_topic_tpl -->
<a class="path" href="{www_dir}{index}/article/topiclist/{topic_id}">{topic_name}</a>
<!-- END article_topic_tpl -->

<!-- BEGIN article_intro_tpl -->
<p>{article_intro}</p>
<br />
<!-- END article_intro_tpl -->
{article_body}
<!-- BEGIN image_list_tpl -->
<!-- BEGIN image_tpl -->
	<img src="{www_dir}{image_url}" width="{image_width}" height="{image_height}" border="2" />
	<p>{image_caption}</p>
<!-- END image_tpl -->
<!-- END image_list_tpl -->


<!-- BEGIN attribute_list_tpl -->
<!-- BEGIN type_item_tpl -->
<h2>{type_name}</h2>
<!-- BEGIN attribute_item_tpl -->
<p class="boxtext">{attribute_name}:</p>
{attribute_value}
<!-- END attribute_item_tpl -->
<!-- END type_item_tpl -->
<!-- END attribute_list_tpl -->


<!-- BEGIN attached_file_list_tpl -->
<p class="boxtext">{intl-attached_files}:</p>
<!-- BEGIN attached_file_tpl -->
<div class="p"><a href="{www_dir}{index}/filemanager/download/{file_id}/{original_file_name}/">{file_name}</a></div>
<!-- END attached_file_tpl -->

<!-- END attached_file_list_tpl -->
