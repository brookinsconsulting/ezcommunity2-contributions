<script language="Javascript">
<!-- hide JavaScript

function IsEmailValid( element )
{
    var EmailOk  = true;
    var Temp     = element;
    var AtSym    = Temp.value.indexOf('@');
    var Period   = Temp.value.lastIndexOf('.');
    var Space    = Temp.value.indexOf(' ');
    var Length   = Temp.value.length - 1;   // Array is from 0 to length-1

    if ((AtSym < 1) ||                     // '@' cannot be in first position
        (Period <= AtSym+1) ||             // Must be atleast one valid char btwn '@' and '.'
        (Period == Length ) ||             // Must be atleast one valid char after '.'
        (Space  != -1))                    // No empty spaces permitted
    {  
        EmailOk = false
        Temp.focus()
    }
    return EmailOk
}

function validate_form( form )
{
    var missing_fields = new Array();
    var error_count = 0;
    var error_message = "";

    if ( form.FirstName.value == '' )
    {
        error_message += "Firstname is empty\n";
        error_count = error_count + 1;
    }

    if ( form.LastName.value == '' )
    {
        error_message += "Lastname is empty\n";        
        error_count = error_count + 1;
    }

    if ( form.UserName.value == '' )
    {
        error_message += "Username is empty\n";        
        error_count = error_count + 1;
    }

    if ( form.Password1.value == '' || form.Password2.value == '' )
    {
        error_message += "Both passwords must be entered\n";        
        error_count = error_count + 1;
    }

    if ( form.Password1.value != form.Password2.value )
    {
        error_message += "Password must be identical\n";
        error_count = error_count + 1;
    }

    if ( !IsEmailValid( form.EMail ) )
    {
        error_message +=  "Please enter a valid e-mail address!";
    }    

    if ( error_count > 0 )
    {
        alert( "Error(s):\n" + error_message );

        return ( false );
    }
    else
    {
        return ( true );
    }
}    

-->

</script>
