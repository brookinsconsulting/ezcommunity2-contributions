<?
/*
  Denne klassen håndterer personer i eZ contact. Disse lagres og hentes ut fra databasen.
*/

//require "ezphputils.php";

class eZPerson
{
    /*
      Constructor.
    */
    function eZPerson( )
    {
        $this->ID = 0;
        $this->Company = 0;
    }
  
    /*
      Lagrer en ny personrad i databasen. 
    */  
    function store()
    {
        $this->dbInit();
        query( "INSERT INTO eZContact_Person set
    FirstName='$this->FirstName',
	LastName='$this->LastName',
	Owner='$this->Owner',
	Comment='$this->Comment',
	PersonNr='$this->PersonNr',
	Company='$this->Company',
	ContactType='$this->ContactType' " );
        return mysql_insert_id();
    }


    /*
      Sletter informasjonen som ligger i databasen.
    */
    function delete()
    {
        $this->dbInit();

        // sletter alle adresser og relasjoner

        $result = mysql_query( "SELECT eZContact_Address.ID AS 'AID', eZContact_PersonAddressDict.ID AS 'DID' from eZContact_Address, eZContact_PersonAddressDict WHERE Address.ID=PersonAddressDict.AddressID AND PersonAddressDict.PersonID='$this->ID' " )
             or die( "Kunne ikke slette firma" );

        for ( $i=0; $i<mysql_num_rows( $result ); $i++ )
        {
            $aid = mysql_result( $result, $i, "AID" );
            $did = mysql_result( $result, $i, "DID" );
            query( "DELETE FROM Address WHERE ID='$aid'" );
            query( "DELETE FROM PersonAddressDict WHERE ID='$did'" );
        }

        $result = mysql_query( "SELECT eZContact_Phone.ID AS 'PID', eZContact_PersonPhoneDict.ID AS 'DID' from eZContact_Phone, eZContact_PersonPhoneDict WHERE Phone.ID=PersonPhoneDict.PhoneID AND PersonPhoneDict.PersonID='$this->ID' " )
             or die( "Kunne ikke slette firma" );

        for ( $i=0; $i<mysql_num_rows( $result ); $i++ )
        {
            $pid = mysql_result( $result, $i, "PID" );
            $did = mysql_result( $result, $i, "DID" );
            query( "DELETE FROM eZContact_Phone WHERE ID='$pid'" );
            query( "DELETE FROM eZContact_PersonPhoneDict WHERE ID='$did'" );
        }
        


        query( "DELETE FROM eZContact_Person WHERE ID='$this->ID'" );
    }


/*
      Oppdaterer informasjonen som ligger i databasen.
    */  
    function update()
    {
        $this->dbInit();
        query( "UPDATE eZContact_Person set
    FirstName='$this->FirstName',
	LastName='$this->LastName',
	Owner='$this->Owner',
	Comment='$this->Comment',
	PersonNr='$this->PersonNr',
	Company='$this->Company',
	ContactType='$this->ContactType' WHERE ID='$this->ID'" );
        
    }

    /*
      Henter ut person med ID == $id
    */  
    function get( $id )
    {
        $this->dbInit();    
        if ( $id != "" )
        {
            array_query( $person_array, "SELECT * FROM eZContact_Person WHERE ID='$id'" );
            if ( count( $person_array ) > 1 )
            {
                die( "Feil: Flere personer med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if ( count( $person_array ) == 1 )
            {
                $this->ID = $person_array[ 0 ][ "ID" ];
                $this->FirstName = $person_array[ 0 ][ "FirstName" ];
                $this->LastName = $person_array[ 0 ][ "LastName" ];
                $this->Owner = $person_array[ 0 ][ "Owner" ];
                $this->ContactType = $person_array[ 0 ][ "ContactType" ];
                $this->Company = $person_array[ 0 ][ "Company" ];
                $this->Comment = $person_array[ 0 ][ "Comment" ];
            }
        }
    }
    
    /*
      Henter ut alle personene lagret i databasen.
    */
    function getAll( )
    {
        $this->dbInit();    
        $person_array = 0;
    
        array_query( $person_array, "SELECT * FROM eZContact_Person ORDER BY LastName" );
    
        return $person_array;
    }

    /*
      Henter ut alle personene lagret i databasen.
    */
    function getByCompany( $id )
    {
        $this->dbInit();
        $person_array = 0;
    
        array_query( $person_array, "SELECT * FROM eZContact_Person WHERE Company='$id' ORDER BY LastName" );

        return $person_array;
    }
    
    /*
      Henter ut alle personene hvor etternavn eller fornavn inneholder søkestrengen.
    */
    function search( $query )
    {
        $this->dbInit();    
        $person_array = 0;
    
        array_query( $person_array, "SELECT * FROM eZContact_Person WHERE FirstName LIKE '%$query%' OR LastName LIKE '%$query%' ORDER BY LastName" );
    
        return $person_array;
    }
    
    /*
      Henter ut alle personene hvor etternavn eller fornavn inneholder søkestrengen og Company = $company.
    */
    function searchByCompanyAndName( $company, $query )
    {
        $this->dbInit();    
        $person_array = 0;
    
        array_query( $person_array, "SELECT * FROM eZContact_Person WHERE (FirstName LIKE '%$query%' OR LastName LIKE '%$query%') AND Company='$company' ORDER BY LastName" );
    
        return $person_array;
    }
    
    /*
     */
    function setFirstName( $value )
    {
        $this->FirstName = $value;
    }

    /*
     */
    function setLastName( $value )
    {
        $this->LastName = $value;
    }

    /*!
     */
    function setCompany( $value )
    {
        $this->Company = $value;
    }

    /*!
     */
    function setComment( $value )
    {
        $this->Comment = $value;
    }

    /*!
     */
    function setContactType( $value )
    {
        $this->ContactType = $value;
    }

    /*!
     */
    function setOwner( $value )
    {
        $this->Owner = $value;
    }
  
  
    /*!
     */
    function setPersonNr( $value )
    {
        $this->PersonNr = $value;
    }
  
    /*
      Returnerer ID'en til personen.
    */
    function id()
    {
        return $this->ID;
    }
  
    /*
      Returnerer fornavnet.
    */
    function firstName()
    {
        return $this->FirstName;
    }

    /*
      Returnerer etternavnet.
    */
    function lastName()
    {    
        return $this->LastName;
    }

    /*!
      Returnerer person nummer
    */
    function personNr()
    {
        return $this->PersonNr;
    }

    /*!
      Returnerer firma
    */
    function company()
    {
        return $this->Company;
    }

    /*!
     */
    function comment( )
    {
        return $this->Comment;
    }

    /*!
     */
    function contactType( )
    {
        return $this->ContactType;
    }

    /*!
     */
    function owner( )
    {
        return $this->Owner;
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
    var $FirstName;
    var $LastName;
    var $Company;  
    var $Owner;
    var $PersonNr;
    var $ContactType;
    var $Comment;
};

?>
