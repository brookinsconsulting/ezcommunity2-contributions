<h1>{name}</h1>

<hr noshade size="4"/>

<br />

<p class="boxtext">{intl-logo}:</p>
<!-- BEGIN no_logo_tpl -->
<p>{intl-no_logo}</p>
<!-- END no_logo_tpl -->
<!-- BEGIN logo_view_tpl -->
       <p class="boxtext">{logo_name}</p>
       <img src="{logo_image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" />
<!-- END logo_view_tpl -->


<p class="boxtext">{intl-description}:</p>
<p>
{description}
</p>

<!-- BEGIN address_item_tpl -->
<p class="boxtext">{intl-address}:</p>
{street1}
{street2}

<p class="boxtext">{intl-zip}:</p>
{zip}

<p class="boxtext">{intl-place}:</p>
{place}
<!-- END address_item_tpl -->


<!-- BEGIN phone_item_tpl -->
<p class="boxtext">{intl-telephone}:</p>
{telephone}
<!-- END phone_item_tpl -->

<!-- BEGIN fax_item_tpl -->
<p class="boxtext">{intl-fax}:</p>
{fax}
<!-- END fax_item_tpl -->

<!-- BEGIN web_item_tpl -->
<p class="boxtext">{intl-web}:</p>
{web}
<!-- END web_item_tpl -->

<!-- BEGIN email_item_tpl -->
<p class="boxtext">{intl-email}:</p>
{email}
<!-- END email_item_tpl -->


<!-- BEGIN image_view_tpl -->
     <p class="boxtext">{intl-company_image}:</p>
       <img src="{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" />
<!-- END image_view_tpl -->
