<?

include_once( "../eztrade/classes/ezproductcategory.php" );
include_once( "../eztrade/classes/ezoption.php" );
include_once( "../eztrade/classes/ezoptionvalue.php" );
include_once( "../eztrade/classes/ezproduct.php" );

print "<h1>Testbenk</h1>";
$category = new eZProductCategory();

//  $category->setName( "category test" );
//  $category->setDescription( "Bla bla bla bla bla" );

//  $category->store();

//  $category2 = new eZProductCategory();

//  $category2->setParent( $category );
//  $category2->setName( "category test2" );
//  $category2->setDescription( "Bla bla bla bla bla" );

//  $category2->store();

$category->get( 1 );
$categoryArray = $category->getByParent( $category );

foreach ( $categoryArray as $catItem )
{
    print( $catItem->name()  . "<br>" . $catItem->description() . "..<br>" );
}

$option = new eZOption();


$product = new eZProduct();
$product->setName( "Tannbørste" );
$product->setBrief( "En liten og hendig tannbørste" );
$product->setDescription( "Denne tannbørsten er utrolig kraftig. Samtidig som den er liten og miljøvennelig." );
$product->setProductNumber( "Jordan-A101" );
$product->setKeywords( "tenner flour hull" );
$product->setPrice( 21.50 );

//  $product->store();

//  $category->addProduct( $product );

//  $option->setName( "color" );
//  $option->setDescription( "Bla bla bla" );

//  $option->store();


//  $option->get( 2 );

//  $category->get( 3 );

// $category->addOption( $option );

$optionArray = $category->products();

foreach ( $optionArray as $option )
{
    print( "Option: " . $option->name() . "<br>" );
}


//  $value = new eZOptionValue();

//  $value->setName( "SuperRed" );

//  $option->addValue( $value );
//  $option->addValue( $value );
//  $option->addValue( $value );

//  $values = $option->values();

//  if ( $values != 0 )
//  {
//      foreach ( $values as $value )
//      {
//          print( "value: " . $value->name() . "<br>" );
//      }
//  }
//  else
//  {
//      print( "no values found." );
//  }

?>

