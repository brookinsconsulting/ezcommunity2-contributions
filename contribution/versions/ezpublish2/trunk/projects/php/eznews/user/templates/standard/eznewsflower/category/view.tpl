<!-- BEGIN this_item_template -->
<h1>{this_name}</h1>

<hr noshade size="4" />

<!-- END this_item_template -->

<p>{this_public_description}</p>

<!-- BEGIN go_to_parent_template -->
<a href="/{this_path}/{this_canonical_parent_id}">{intl-go_to_parent} {this_canonical_parent_name}</a>
<!-- END go_to_parent_template -->


<!-- BEGIN no_articles_template -->
<h2>{intl-no_articles_in_category}</h2>
<!-- END no_articles_template -->

<!-- BEGIN articles_template -->
{article_items}
<!-- END articles_template -->

<!-- BEGIN article_item_template -->
{article}
<!-- END article_item_template -->

