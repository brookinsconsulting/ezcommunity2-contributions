
<!-- BEGIN article_image_template -->
<img src="{this_image}" height="{this_image_height}" alt="this_image_caption" width="{this_image_width}">
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

<form method="post" action="/{this_path}/{this_id}?edit+this">
<input class="okbutton" name="form_preview" type="submit" value="{intl-edit}">

<input class="okbutton" name="form_submit" type="submit" value="{intl-submit}">

<!-- END article_item_template -->




<!-- BEGIN go_to_parent_template -->

<form action="/{this_path}/{this_canonical_parent_id}">
<input class="okbutton" type="submit" name="form_abort" value="{intl-abort}"></form>

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
