
<!-- BEGIN article_image_template -->
<img src="{this_image}" height="{this_image_height}" alt="{this_image_caption}" width="{this_image_width}">
<!-- END article_image_template -->



<!-- BEGIN article_here_template -->

<br />

<hr noshade size="4" />

<br />

<!-- END article_here_template -->

<!-- BEGIN article_item_template -->

<h2>{this_name}</h2>
<p>
{this_description}
{this_picture}
</p>
<p class="pris">
{this_price}
</p>

<br />

<hr noshade size="4" />

<br />

<a href="/{this_path}/{this_canonical_parent_id}">{intl-go_to_parent} {this_canonical_parent_name}</a><br />
<a href="/{this_path}/{this_id}?edit+this">{intl-edit_this_article}</a><br />
<a href="/{this_path}/{this_id}?delete+this">{intl-delete_this_article}</a><br />

<!-- END article_item_template -->








<!-- These need to be here, even though they are empty! -->

<!-- BEGIN upload_picture_template -->

<!-- END upload_picture_template -->

<!-- BEGIN picture_uploaded_template -->

<!-- END picture_uploaded_template -->

<!-- BEGIN go_to_parent_template -->

<!-- END go_to_parent_template -->

<!-- BEGIN go_to_self_template -->

<!-- END go_to_self_template -->
