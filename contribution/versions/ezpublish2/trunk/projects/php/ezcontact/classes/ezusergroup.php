<?

class eZUserGroup
{
  /*!
    Constructor.
  */
  function eZUserGroup( )
  {
    $this->UserAdmin = "N";
    $this->UserGroupAdmin = "N";
    $this->PersonTypeAdmin = "N";
    $this->CompanyTypeAdmin = "N";
    $this->PhoneTypeAdmin = "N";
    $this->AddressTypeAdmin = "N";    
  }

  /*
    Lagrer en ny brukergrupperad databasen. 
  */  
  function store()
  {
    $this->dbInit();
    query( "INSERT INTO Grp set
        Name='$this->Name',
        Description='$this->Description',
	UserAdmin='$this->UserAdmin',
	UserGroupAdmin='$this->UserGroupAdmin',
	PersonTypeAdmin='$this->PersonTypeAdmin',
	CompanyTypeAdmin='$this->CompanyTypeAdmin',
	PhoneTypeAdmin='$this->PhoneTypeAdmin',
	AddressTypeAdmin='$this->AddressTypeAdmin'" );
    return mysql_insert_id();
  }

    /*
      Sletter brukergruppe fra databasen.
     */
    function delete()
    {
        $this->dbInit();
        query( "DELETE FROM Grp WHERE ID='$this->ID'" );
    }



  /*
    Oppdaterer en brukergrupperad i databasen. returnerer 0 dersom ra
  */  
  function update()
  {
    $this->dbInit();
    if ( isset( $this->ID ) )
    {
      query( "UPDATE Grp set
                Name='$this->Name',
                Description='$this->Description',
		UserAdmin='$this->UserAdmin',
      		UserGroupAdmin='$this->UserGroupAdmin',
		PersonTypeAdmin='$this->PersonTypeAdmin',
		CompanyTypeAdmin='$this->CompanyTypeAdmin',
		PhoneTypeAdmin='$this->PhoneTypeAdmin',
		AddressTypeAdmin='$this->AddressTypeAdmin' WHERE ID='$this->ID'" );
    }
  }
  
  /*
    Henter ut brukergruppe med ID == $id
  */  
  function get( $id )
  {
    $this->dbInit();    
    if ( $id != "" )
    {
      array_query( $user_group_array, "SELECT * FROM Grp WHERE ID='$id'" );
      if ( count( $user_group_array ) > 1 )
      {
          die( "Feil: Flere user_grouper med samme ID funnet i database, dette skal ikke være mulig. " );
      }
      else if ( count( $user_group_array ) == 1 )
      {
        $this->ID = $user_group_array[ 0 ][ "ID" ];
        $this->Name = $user_group_array[ 0 ][ "Name" ];
        $this->Description = $user_group_array[ 0 ][ "Description" ];
        $this->UserAdmin = $user_group_array[ 0 ][ "UserAdmin" ];
        $this->UserGroupAdmin = $user_group_array[ 0 ][ "UserGroupAdmin" ];
        $this->PersonTypeAdmin = $user_group_array[ 0 ][ "PersonTypeAdmin" ];
        $this->CompanyTypeAdmin = $user_group_array[ 0 ][ "CompanyTypeAdmin" ];
        $this->PhoneTypeAdmin = $user_group_array[ 0 ][ "PhoneTypeAdmin" ];
        $this->AddressTypeAdmin = $user_group_array[ 0 ][ "AddressTypeAdmin" ];
      }
    }
  }

  /*
    Henter ut alle brukergruppene lagret i databasen.
  */
  function getAll( )
  {
    $this->dbInit();    
    $user_group_array = 0;
    
    array_query( $user_group_array, "SELECT * FROM Grp ORDER BY Name" );
    
    return $user_group_array;
  }  

  function setName( $value )
  {
    $this->Name= $value;
  }
  
  function setDescription( $value )
  {
    $this->Description = $value;
  }

  /*
    Rettighetene til brukergruppen.
  */  
  function setUserAdmin( $value )
  {
    $this->UserAdmin = $value;
  }
  
  /*
    Rettighetene til brukergruppen.
  */  
  function setUserGroupAdmin( $value )
  {
    $this->UserGroupAdmin = $value;
  }

  /*
    Rettighetene til persontype.
  */  
  function setPersonTypeAdmin( $value )
  {
    $this->PersonTypeAdmin = $value;
  }
  
  /*
    Rettighetene til firmatype.
  */  
  function setCompanyTypeAdmin( $value )
  {
    $this->CompanyTypeAdmin = $value;
  }

  /*
    Rettighetene til telefontype.
  */  
  function setPhoneTypeAdmin( $value )
  {
    $this->PhoneTypeAdmin = $value;
  }

  /*
    Rettighetene til telefontype.
  */  
  function setAddressTypeAdmin( $value )
  {
    $this->AddressTypeAdmin = $value;
  }

  function name( )
  {
    return $this->Name;
  }
  
  function description( )
  {
    return $this->Description ;
  }

  /*
    Rettighetene til brukergruppen.
  */  
  function userAdmin( )
  {
    return $this->UserAdmin ;
  }
  
  /*
    Rettighetene til brukergruppen.
  */  
  function userGroupAdmin( )
  {
    return $this->UserGroupAdmin;
  }

  /*
    Rettighetene til persontype.
  */  
  function personTypeAdmin( )
  {
    return $this->PersonTypeAdmin;
  }
  
  /*
    Rettighetene til firmatype.
  */  
  function companyTypeAdmin( )
  {
    return $this->CompanyTypeAdmin;
  }

  /*
    Rettighetene til telefontype.
  */  
  function phoneTypeAdmin( )
  {
    return $this->PhoneTypeAdmin;
  }

  /*
    Rettighetene til adressetypen.
  */  
  function addressTypeAdmin( )
  {
    return $this->AddressTypeAdmin;
  }
  
  /*
    Privat: Initiering av database. 
  */
  function dbInit()
  {
    require "ezcontact/dbsettings.php";
    mysql_pconnect( $SERVER, $USER, $PWD ) or die( "Kunne ikke kople til database" );
    mysql_select_db( $DATABASE ) or die( "Kunne ikke velge database" );
  }

  var $ID;
  var $Name;
  var $Description;
  var $UserAdmin;
  var $UserGroupAdmin;
  var $PersonTypeAdmin;
  var $CompanyTypeAdmin;
  var $PhoneTypeAdmin;
  var $AddressTypeAdmin;
}
?>
