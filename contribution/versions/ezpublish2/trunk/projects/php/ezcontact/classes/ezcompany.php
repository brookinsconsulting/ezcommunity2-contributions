<?

//!! eZContact
//!
/*!

*/

//require "ezphputils.php";

class eZCompany
{
    /*!
      Constructor
    */
    function eZCompany( )
    {
        $this->ID = 0;
    }

    /*!
    Lagrer informasjon til databasen.
  */
    function store( )
    {
        $this->dbInit();
        query( "INSERT INTO eZContact_Company set Name='$this->Name',
	Comment='$this->Comment',
	ContactType='$this->ContactType',
	Owner='$this->Owner'" );
        return mysql_insert_id();
    
    }

    /*
      Sletter kontakt firma i databasen.
    */
    function delete()
    {
        $this->dbInit();
        

        // sletter alle adresser og relasjoner

 $result = mysql_query( "SELECT eZContact_Address.ID AS 'AID', eZContact_CompanyAddressDict.ID AS 'DID' from eZContact_Address, eZContact_CompanyAddressDict WHERE Address.ID=CompanyAddressDict.AddressID AND CompanyAddressDict.CompanyID='$this->ID' " )
      or die( "Kunne ikke slette firma" );

        for ( $i=0; $i<mysql_num_rows( $result ); $i++ )
        {
            $aid = mysql_result( $result, $i, "AID" );
            $did = mysql_result( $result, $i, "DID" );
            query( "DELETE FROM eZContact_Address WHERE ID='$aid'" );
            query( "DELETE FROM eZContact_CompanyAddressDict WHERE ID='$did'" );
        }

 $result = mysql_query( "SELECT eZContact_Phone.ID AS 'PID', eZContact_CompanyPhoneDict.ID AS 'DID' from eZContact_Phone, eZContact_CompanyPhoneDict WHERE Phone.ID=CompanyPhoneDict.PhoneID AND CompanyPhoneDict.CompanyID='$this->ID' " )
      or die( "Kunne ikke slette firma" );

        for ( $i=0; $i<mysql_num_rows( $result ); $i++ )
        {
            $pid = mysql_result( $result, $i, "PID" );
            $did = mysql_result( $result, $i, "DID" );
            query( "DELETE FROM eZContact_Phone WHERE ID='$pid'" );
            query( "DELETE FROM eZContact_CompanyPhoneDict WHERE ID='$did'" );
        }
        
        query( "DELETE FROM eZContact_Company WHERE ID='$this->ID'" );
        
    }

    /*!
    Oppdaterer informasjonen som ligger i databasen.
  */
    function update( )
    {
        $this->dbInit();
        query( "UPDATE eZContact_Company set Name='$this->Name',
	Comment='$this->Comment',
	ContactType='$this->ContactType',
	Owner='$this->Owner' WHERE ID='$this->ID'" );
    }
  
    /*!
    Henter ut et firma fra databasen.
  */
    function get( $id )
    {
        $this->dbInit();    
        if ( $id != "" )
        {
            array_query( $company_array, "SELECT * FROM eZContact_Company WHERE ID='$id'" );
            if ( count( $company_array ) > 1 )
            {
                die( "Feil: Flere firma med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if ( count( $company_array ) == 1 )
            {
                $this->ID = $company_array[ 0 ][ "ID" ];
                $this->Name = $company_array[ 0 ][ "Name" ];
                $this->Comment = $company_array[ 0 ][ "Comment" ];
                $this->Owner = $company_array[ 0 ][ "Owner" ];        
                $this->ContactType = $company_array[ 0 ][ "ContactType" ];        
            }
        }
    }

    /*
    Henter ut alle firma lagret i databasen.
  */
    function getAll( )
    {
        $this->dbInit();    
        $company_array = 0;
    
        array_query( $company_array, "SELECT * FROM eZContact_Company ORDER BY Name" );
    
        return $company_array;
    }

    /*
    Henter ut alle firma i databasen som inneholder søkestrengen.
  */
    function search( $query )
    {
        $this->dbInit();    
        $company_array = 0;
    
        array_query( $company_array, "SELECT * FROM eZContact_Company WHERE Name LIKE '%$query%' ORDER BY Name" );
    
        return $company_array;
    }

    /*
    Henter ut alle firma i databasen hvor en eller flere tilhørende personer    
    inneholder søkestrengen.
  */
    function searchByPerson( $query )
    {
        $this->dbInit();    
        $company_array = 0;
    
        array_query( $company_array, "SELECT  eZContact_Company.ID, eZContact_Company.Name from eZContact_Company, eZContact_Person where ((eZContact_Person.FirstName LIKE '%$query%' OR eZContact_Person.LastName LIKE '%$query%') AND eZContact_Company.ID=eZContact_Person.Company) GROUP BY eZContact_Company.ID ORDER BY eZContact_Company.ID" );

        return $company_array;
    }
    
    /*!
    Setter Navn.
  */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
    Setter kontakttype.
  */
    function setContactType( $value )
    {
        $this->ContactType = $value;
    }

/*!
    Setter kommentar.
  */
    function setComment( $value )
    {
        $this->Comment = $value;
    }

    /*!
    Setter eier.
  */
    function setOwner( $value )
    {
        $this->Owner = $value;
    }

    /*!
    Returnerer ID.
  */
    function id()
    {
        return $this->ID;
    }

    /*!
    Returnerer firmanavn.
  */
    function name()
    {
        return $this->Name;
    }
    
    /*!
    Returnerer ID til eier av firma ( brukeren som opprettet det ).
  */
    function owner()
    {
        return $this->Owner;
    }
    
    /*!
    Returnerer kontakttype.
  */
    function contactType()
    {
        return $this->ContactType;
    }
  
    /*!
    Returnerer kommentar.
  */
    function comment()
    {
        return $this->Comment;
    }  
  
    /*!
      Privat funksjon, skal kun brukes av ezusergroup klassen.
      Funksjon for å åpne databasen.
    */
    function dbInit()
    {
        include_once( "classes/INIFile.php" );

        $ini = new INIFile( "site.ini" );
        
        $SERVER = $ini->read_var( "site", "Server" );
        $DATABASE = $ini->read_var( "site", "Database" );
        $USER = $ini->read_var( "site", "User" );
        $PWD = $ini->read_var( "site", "Password" );
        
        mysql_pconnect( $SERVER, $USER, $PWD ) or die( "Kunne ikke kople til database" );
        mysql_select_db( $DATABASE ) or die( "Kunne ikke velge database" );
    }

    var $ID;
    var $Owner;
    var $Name;
    var $Comment;
    var $ContactType;
}

?>
