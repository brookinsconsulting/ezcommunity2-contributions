<form method="post" action="{www_dir}{index}/user/useredit/{action_value}/{user_id}/">

<h1>{head_line}</h1>

<hr noshade size="4" />

<h3 class="error">{error}</h3>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
        <td width="50%">
        <p class="boxtext">{intl-firstname}:</p>
        <input type="text" class="halfbox" size="20" name="FirstName" value="{first_name_value}"/>
        <br /><br />
        </td>
        <td>
        <p class="boxtext">{intl-lastname}:</p>
        <input type="text" class="halfbox" size="20" name="LastName" value="{last_name_value}"/>
        <br /><br />
        </td>
</tr>
<tr>
        <td colspan="2">
        <p class="boxtext">{intl-email}:</p>
        <input type="text" class="box" size="40" name="Email" value="{email_value}"/>
        <br /><br />
        </td>
</tr>
<tr>
        <td colspan="2">
        <p class="boxtext">{intl-login}:</p>
        <input type="text" class="halfbox" {read_only} size="20" name="Login" value="{login_value}"/>
        <br /><br />
        </td>
</tr>
<tr>
        <td colspan="2">
        <p class="boxtext">{intl-signature}:</p>
        <textarea name="Signature" class="box" cols="40" rows="9" wrap="soft">{signature}</textarea>
        <br /><br />
        </td>
</tr>

<tr>
<td>
<!-- BEGIN additional_text_item_tpl -->
<tr>
	<td>
	<p class="boxtext">{additional_name}:</p>
	<input type="text" size="20" name="AdditionalValue[{index}]" value="{additional_value}"/>
	<input type="hidden"name="AdditionalArrayID[{index}]" value="{additional_id}"/>
	</td>
</tr>
<tr>
        <td>&nbsp;</td>
</tr>
<!-- END additional_text_item_tpl -->
<!-- BEGIN additional_radio_item_tpl -->
<tr>
	<td>
	<p class="boxtext">{additional_name}:</p>
	<!-- BEGIN fixed_values_tpl -->
	{value}: <input {radio_checked} type="radio" size="20" name="AdditionalValue[{index}]" value="{value_id}"/>&nbsp;
	<!-- END fixed_values_tpl -->
	<input type="hidden"name="AdditionalArrayID[{index}]" value="{additional_id}"/>
	</td>
</tr>
<tr>
        <td>&nbsp;</td>
</tr>
<!-- END additional_radio_item_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN additional_item_tpl -->

<!-- END additional_item_tpl -->
</table>

</td>
</tr>
<tr>
        <td valign="top">
        <p class="boxtext">{intl-main_group}:</p>
        <select name="MainGroup">
        <!-- BEGIN main_group_item_tpl -->
        <option value="{group_id}" {main_selected}>{group_name}</option>
        <!-- END main_group_item_tpl -->
        </select>
        </td>

        <td>
        <p class="boxtext">{intl-groups}:</p>
        <select name="GroupArray[]" multiple size="5">
        <!-- BEGIN group_item_tpl -->
        <option value="{group_id}" {selected}>{group_name}</option>
        <!-- END group_item_tpl -->
        </select>
        <br /><br />
        </td>
</tr>
<tr>
        <td colspan="2">
        <input {info_subscription} type="checkbox" name="InfoSubscription" />
        <span class="p">{intl-infosubscription}</span>
        </td>
</tr>
<tr>
	<td colspan="2"><br /></td>
</tr>
<tr>
        <td>
        <p class="boxtext">{intl-password}:</p>
        <input type="password" class="halfbox" size="20" name="Password" value="{password_value}"/>
        <br /><br />
        </td>
        <td>
        <p class="boxtext">{intl-verifypassword}:</p>
        <input type="password" class="halfbox" size="20" name="VerifyPassword" value="{verify_password_value}"/>
        <br /><br />
        </td>
</tr>
<tr>
        <td valign="bottom">
        <p class="boxtext">{intl-expiry_date}:</p>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td>
        <div class="small">{intl-day}:</div>
        <select name="Day">
<!-- BEGIN day_item_tpl -->
        <option value="{day}" {day_selected}>{day}
<!-- END day_item_tpl -->
        </select>
			</td>
			<td>
        <div class="small">{intl-month}:</div>
        <select name="Month">
<!-- BEGIN month_item_tpl -->
        <option value="{month_id}" {month_selected}>{month_name}
<!-- END month_item_tpl -->
        </select>
			</td>
			<td>
        <div class="small">{intl-year}:</div>
        <input type="text" size="4" name="Year" value="{year}" />
			</td>
		</tr>
		</table>
        </td>
        <td valign="bottom">
        <input {no_expiry} type="checkbox" name="NoExpiryDate" />
        <span class="p">{intl-no_expiry_date}</span>
        </td>
</tr>
<tr>
	<td colspan="2"><br /></td>
</tr>
<tr>
        <td>
		<p class="boxtext">{intl-disabled_account}</p>
        <input {disabled_account} type="checkbox" name="DisabledAccount" />
        </td>
        <td>
        <p class="boxtext">{intl-simultaneouslogins}:</p>
        <input type="text" size="5" name="SimultaneousLogins" value="{simultaneouslogins_value}"/>
        </td>
</tr>
</table>
        
<br />

<hr noshade size="4"/>

<table cellspacing="0" cellpadding="0" border="0">
<tr>
        <td valign="top">
        <input type="hidden" name="UserID" value="{user_id}" />
        <input class="okbutton" type="submit" value="OK" />
        </td>
        <td>&nbsp;</td>
        <td>
        <input class="okbutton" type="submit" name="Back" value="{intl-back}"></form>
        </td>
</table>
</form>

