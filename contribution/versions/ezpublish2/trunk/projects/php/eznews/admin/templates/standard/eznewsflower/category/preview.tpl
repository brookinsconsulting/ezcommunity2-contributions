<form method="post" action="/{this_path}/{this_id}?edit+this">

<h1>{intl-preview_category} {this_name}</h1>

<hr noshade size="4" />

<p>{this_public_description}</p>

<hr noshade size="4" />

<input class="okbutton" name="form_preview" type="submit" value="{intl-edit}">

<input class="okbutton" name="form_submit" type="submit" value="{intl-submit}">


</form>



<!-- BEGIN go_to_parent_template -->

<form action="/{this_path}/{this_canonical_parent_id}">
<input class="okbutton" type="submit" name="form_abort" value="{intl-abort}">
</form>

<!-- END go_to_parent_template -->





<!-- BEGIN go_to_self_template -->

<form method="post" action="/{this_path}/{this_id}">
<input class="okbutton" type="submit" name="form_abort" value="{intl-abort}">
</form>

<!-- END go_to_self_template -->
