

<h1>cart</h1>

<?php

include_once( "eztrade/classes/ezcart.php" );
include_once( "ezsession/classes/ezsession.php" );

$cart = new eZCart();
$session = new eZSession();

// if no session exist create one.
if ( !$session->fetch() )
{
    $session->store();
}

echo( "id: " . $session->id() );

$cart = $cart->getBySession( $session );
if ( !$cart )
{
    print( "creating a cart" );
    $cart = new eZCart();
    $cart->setSession( $session );

    $cart->store();
}
else
{
    print( "Cart: " . $cart->id() );
}

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezuser/classes/ezmodule.php" );

print( "<h1>User test:</h1>" );

$user = new eZUser();
$user->setLogin( "bf" );
$user->setPassword( "secret" );
$user->setEmail( "bf@ez.no" );
$user->setFirstName( "Bård" );
$user->setLastName( "Farstad" );

if ( !$user->exists( $user->login() ) )
{
    echo "Username is not used, creating user.<br>";
    $user->store();        
}

$user = $user->validateUser( "bf", "secret" );

if ( $user )
{
    print( "Password and username are ok!" );
}

$user = new eZUser();
$user->get( 1 );

$group = new eZUserGroup();
//  $group->setName( "Administrator" );
//  $group->setDescription( "Has root access" );

//  $group->store();

$group->get( 1 );

if ( $group->adduser( $user ) )
{
    print( "User added to group" );    
}
else
{
    print( "Error: count not add user." );
} 

$module = new eZModule();

$module->setName( "eZTrade" );

if ( !$module->exists( $module->name() ) )
{
    print( "Creating module<br>" );
    $module->store();
}
else
{
    print( "Error: count not create module, a module with that name already exists.<br>" );
}

$module->get( 1 );
$permission = new eZPermission();

if ( $permission->get( 1 ) )
{
    print( "Permission successfully fetched<br>" );
}

$permission->setEnabled( $group, true );

if ( $permission->isEnabled( $group ) )
{
    print( "Access granted.<br>" );
}
else
{
    print( "Access denied.<br>" );
}


//  $permission->setName( "Add new products" );
//  $permission->setModule( $module );

//  if ( $permission->store() )
//  {
//      print( "Permission stored successfully<br>" );
//  }
//  else
//  {
//      print( "Error: could not store permission." );
//  }




?>
