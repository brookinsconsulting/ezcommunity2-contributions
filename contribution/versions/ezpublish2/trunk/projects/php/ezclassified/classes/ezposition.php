<?
include_once( "ezclassified/classes/ezclassified.php" );

class eZPosition extends eZClassified
{
    function eZPosition( $id="-1", $fetch=true )
    {
        // run the parents constructor.
//        eZClassified::eZClassified( $id, $fetch );

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

    function store()
    {
        eZClassified::store( );

        if ( ( $this->Status_ ) == "Insert" )
        {
                $this->dbInit();
                
                $this->Database->query( "INSERT INTO eZClassified_Position SET
                                                  Duration='$this->Duration',
	                                              WorkTime='$this->WorkTime',
	                                              Pay='$this->Pay',
                                                  ContactPerson='$this->ContactPerson',
                                                  WorkPlace='$this->WorkPlace',
                                                  ID='$this->ID'
                                                  ");
                    $this->State_ = "Coherent";
        }
        elseif ( ( $this->Status_ ) == "Update" )
        {
            $this->Database->query( "UPDATE eZClassified_Position SET
                                                  Duration='$this->Duration',
	                                              WorkTime='$this->WorkTime',
	                                              Pay='$this->Pay',
                                                  ContactPerson='$this->ContactPerson',
                                                  WorkPlace='$this->WorkPlace'
                                               	  WHERE ID='$this->ID'
                                               	  " );
            $this->State_ = "Coherent";
        }
        
        return true;
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        eZClassified::get( $id );

        $this->dbInit();
        $ret = false;

        if ( $id != "" )
        {
            $this->Database->array_query( $position_array, "SELECT * FROM eZClassified_Position WHERE ID='$id'" );
            if ( count( $position_array ) > 1 )
            {
                die( "Error: More than one company with the same id was found. " );
            }
            else if ( count( $position_array ) == 1 )
            {
                $this->Duration = $position_array[0]["Duration"];
                $this->WorkTime = $position_array[0]["WorkTime"];
                $this->WorkPlace = $position_array[0]["WorkPlace"];
                $this->Pay = $position_array[0]["Pay"];
                $this->ContactPerson = $position_array[0]["ContactPerson"];
                
                $ret = true;
            }
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
        return $ret;
    }

    /*!
      Sets the name of the company.
    */
    function setDuration( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Duration = $value;
    }
    /*!
      Sets the name of the company.
    */
    function setWorkTime( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->WorkTime = $value;
    }
    /*!
      Sets the name of the company.
    */
    function setWorkPlace( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->WorkPlace = $value;
    }

    /*!
      Sets the name of the company.
    */
    function setPay( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Pay = $value;
    }

    /*!
      Sets the name of the company.
    */
    function setContactPerson( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->ContactPerson = $value;
    }


        /*!
      Returnerer firmanavn.
    */
    function duration()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Duration;
    }
    /*!
      Returnerer firmanavn.
    */
    function workTime()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->WorkTime;
    }
    /*!
      Returnerer firmanavn.
    */
    function workPlace()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->WorkPlace;
    }

    /*!
      Returnerer firmanavn.
    */
    function pay()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Pay;
    }

        /*!
      Returnerer firmanavn.
    */
    function contactPerson()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->ContactPerson;
    }

    
    var $Duration;
    var $WorkTime;
    var $WorkPlace;
    var $Pay;
    var $ContactPerson;
}

?>
