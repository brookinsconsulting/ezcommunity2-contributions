<h1>{intl-headline_view}: {current_name}</h1>
<!-- BEGIN path_tpl -->
<hr noshade="noshade" size="4" />

<img src="/images/path-arrow.gif" height="10" width="15" border="0">

<a class="path" href="/cv/certificatecategory/list/0">{intl-root_category}</a>

<!-- BEGIN path_item_tpl -->
<img src="/images/path-slash.gif" height="10" width="20" border="0">
<a class="path" href="/cv/certificatecategory/list/{parent_id}">{parent_name}</a>
<!-- END path_item_tpl -->

<!-- BEGIN current_path_item_tpl -->
<img src="/images/path-slash.gif" height="10" width="20" border="0">
<a class="path" href="/cv/certificatetype/view/{parent_id}">{intl-current_edit}</a>
<!-- END current_path_item_tpl -->

<hr noshade="noshade" size="4" />
<!-- END path_tpl -->

<!-- BEGIN current_type_tpl -->

<!-- BEGIN parent_item_tpl -->

<!-- END parent_item_tpl -->

<p class="boxtext">{intl-th_certificate_description}:</p>
<div class="text">{current_description}</div>

<!-- END current_type_tpl -->
