
<!-- BEGIN article_image_template -->
<img src="{this_image}" height="{this_image_height}" alt="this_image_caption" width="{this_image_width}" align="right" border="0">
<!-- END article_image_template -->



<!-- BEGIN article_here_template -->

<h1>{intl-preview_article}</h1>

<hr noshade size="4" />

<br />

<!-- END article_here_template -->

<!-- BEGIN article_item_template -->

{this_picture}
<h2>{this_name}</h2>
<p>
{this_description}
</p>
<p class="pris">
{this_price}
</p>

<hr noshade size="4" />

<br />

<form method="post" action="/{this_path}/{this_id}?edit+this">
<input class="okbutton" type="submit" value="{intl-edit}">

<input class="okbutton" name="form_publish" type="submit" value="{intl-publish}">

<!-- END article_item_template -->




<!-- BEGIN go_to_parent_template -->

<form action="/{this_path}/{this_canonical_parent_id}">
<input class="okbutton" type="submit" name="form_abort" value="{intl-abort}">

</form>
</form>

<!-- END go_to_parent_template -->



<!-- BEGIN go_to_self_template -->

<form method="post" action="/{this_path}/{this_id}">
<input class="okbutton" type="submit" name="form_abort" value="{intl-abort}">
</form>
</form>

<!-- END go_to_self_template -->





<!-- These need to be here, even though they are empty! -->

<!-- BEGIN upload_picture_template -->

<!-- END upload_picture_template -->

<!-- BEGIN picture_uploaded_template -->

<!-- END picture_uploaded_template -->
