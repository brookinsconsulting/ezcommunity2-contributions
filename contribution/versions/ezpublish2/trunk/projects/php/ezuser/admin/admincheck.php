<?
$user = eZUser::currentUser();
if ( !$user )
{
    Header( "Location: /user/login" );
    exit();
}

if ( !eZPermission::checkPermission( $user, "eZUser", "AdminLogin" ) )
{
    eZUser::logout( $user );
    Header( "Location: /user/login" );
    exit();
}
?>
