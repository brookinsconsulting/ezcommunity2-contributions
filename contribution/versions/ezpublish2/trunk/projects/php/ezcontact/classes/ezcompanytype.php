<?

//!! eZContact
//!
/*!

*/

class eZCompanyType
{
    /*!
      Constructor.
    */
    function eZCompanyType( $id="-1", $fetch=true )
    {
        $this->IsConnected = false;
        
        if ( $id != -1 )
        {
            $this->ID = $id;
            if ( $fetch == true )
            {
                
                $this->get( $this->ID );
            }
            else
            {
                $this->State_ = "Dirty";
                
            }
        }
        else
        {
            $this->State_ = "New";
        }
    }

    /*!
      Lagrer informasjonen til databasen.
    */
    function store()
    {
        $this->dbInit();
        
        $ret = false;
        
        if ( !isSet( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZContact_CompanyType set Name='$this->Name', Description='$this->Description'" );

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
            $ret = true;
        }
        else
        {
            $this->Database->query( "UPDATE eZContact_CompanyType set Name='$this->Name', Description='$this->Description' WHERE ID='$this->ID'" );

            $this->State_ = "Coherent";
            $ret = true;
        }
        return $ret;
    }

    /*
      Sletter adressetypen fra databasen.
     */
    function delete()
    {
        $this->dbInit();
        $this->Database->query( "DELETE FROM eZContact_CompanyType WHERE ID='$this->ID'" );
    }

    /*
      Henter ut en firmatype med ID == $id
    */  
    function get( $id )
    {
        $this->dbInit();    
        if ( $id != "" )
        {
            array_query( $company_type_array, "SELECT * FROM eZContact_CompanyType WHERE ID='$id'" );
            if ( count( $company_type_array ) > 1 )
            {
                die( "Feil: Flere companytype med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if ( count( $company_type_array ) == 1 )
            {
                $this->ID = $company_type_array[ 0 ][ "ID" ];
                $this->Name = $company_type_array[ 0 ][ "Name" ];
                $this->Description = $company_type_array[ 0 ][ "Description" ];
            }
        }
    }
    
    /*!

     */
    function getAll( )
    {
        $this->dbInit();
        $company_type_array = array();
        $return_array = array();

        
        $this->Database->array_query( $company_type_array, "SELECT ID FROM eZContact_CompanyType ORDER BY Name" );

        foreach( $company_type_array as $companyTypeItem )
        {
            $return_array[] = new eZCompanyType( $companyTypeItem["ID"] );
        }
    
        return $return_array;
    }
  
  
    /*!
      Setter navn.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }
    /*!
      Setter navn.
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

  
    /*!
      Returnerer navnet.
    */
    function name( )
    {
        return $this->Name;
    }
  
    /*!
      Returnerer kommentaren.
    */
    function description( )
    {
        return $this->Description;
    }
    
    /*!
      \private
      Open the database.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = new eZDB( "site.ini", "site" );
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $Name;
    var $Description;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;

}

?>
