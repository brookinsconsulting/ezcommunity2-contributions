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
                                                  WorkPlace='$this->WorkPlace',
                                                  PositionType='$this->PositionType',
                                                  InitiateType='$this->InitiateType',
                                                  DueDate='$this->DueDate',
                                                  Reference='$this->Reference',
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
                                                  WorkPlace='$this->WorkPlace',
                                                  PositionType='$this->PositionType',
                                                  InitiateType='$this->InitiateType',
                                                  Reference='$this->Reference',
                                                  DueDate='$this->DueDate'
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
                $this->PositionType = $position_array[0]["PositionType"];
                $this->InitiateType = $position_array[0]["InitiateType"];
                $this->DueDate = $position_array[0]["DueDate"];
                $this->Reference = $position_array[0]["Reference"];

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
      Sets the position type
    */
    function setPositionType( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->PositionType = $value;
    }

    /*!
      Sets the initiate type
    */
    function setInitiateType( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->InitiateType = $value;
    }

    /*!
      Sets the due date
    */
    function setDueDate( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->DueDate = $value;
    }

    /*!
      Sets the reference for the position.
    */
    function setReference( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Reference = $value;
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
      Returns position type.
    */
    function positionType()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->PositionType;
    }

    /*!
      Returns initiate type.
    */
    function initiateType()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->InitiateType;
    }

    /*!
      Returns duedate.
    */
    function dueDate()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->DueDate;
    }

    /*!
      Returns reference for position.
    */
    function reference()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Reference;
    }

    var $Duration;
    var $WorkTime;
    var $WorkPlace;
    var $Pay;
//      var $ContactPerson;
    var $PositionType;
    var $InitiateType;
    var $DueDate;
    var $Reference;
}

/*!
  Returns all position types
*/
function getPositionTypes()
{
    $database = new eZDB( "site.ini", "site" );
    $res_array = array();
    $qry_array = array();

    $query= "SELECT ID FROM eZClassified_PositionType";
    $database->array_query( $qry_array, $query );
    foreach ( $qry_array as $qry_item )
        {
            $res_array[] = $qry_item["ID"];
        }
    return $res_array;
}

/*!
  Returns all initiate types
*/
function getInitiateTypes()
{
    $database = new eZDB( "site.ini", "site" );
    $res_array = array();
    $qry_array = array();

    $query= "SELECT ID FROM eZClassified_InitiateType";
    $database->array_query( $qry_array, $query );
    foreach ( $qry_array as $qry_item )
        {
            $res_array[] = $qry_item["ID"];
        }
    return $res_array;
}

/*!
  Returnerer position type name.
*/
function positionTypeName( $position_type )
{
    $database = new eZDB( "site.ini", "site" );
    $res_array = array();
    $name = false;

    $query= "SELECT Name FROM eZClassified_PositionType WHERE ID='$position_type'";
    $database->array_query( $res_array, $query );
    if ( count( $res_array ) < 0 )
        die( "eZPosition::positionTypeName(): No position type found with id=$position_type" );
    else if ( count( $res_array ) == 1 )
    {
        $name = $res_array[0]["Name"];
    }
    else
        die( "eZPosition::positionTypeName(): Found more than one position type with id=$position_type" );

    return $name;
}

/*!
  Returnerer initiate type name.
*/
function initiateTypeName( $initiate_type )
{
    $database = new eZDB( "site.ini", "site" );

    $res_array = array();
    $name = false;

    $query= "SELECT Name FROM eZClassified_InitiateType WHERE ID='$initiate_type'";
    $database->array_query( $res_array, $query );
    if ( count( $res_array ) < 0 )
        die( "eZPosition::initiateTypeName(): No initiate type found with id='$initiate_type'" );
    else if ( count( $res_array ) == 1 )
    {
        $name = $res_array[0]["Name"];
    }
    else
        die( "eZPosition::initiateTypeName(): Found more than one initiate type with id='$initiate_type'" );

    return $name;
}

/*!
  Returns all contact persons for a given position.
*/

function getPositionContactPersons( $position_id )
{
    $database = new eZDB( "site.ini", "site" );

    $qry_array = array();

    $query = "SELECT PersonID FROM eZClassified_ClassifiedPersonLink WHERE ClassifiedID='$position_id'";
    $database->array_query( $qry_array, $query );

    $res_array = array();
    foreach ( $qry_array as $position )
        {
            $res_array[] = $position["PersonID"];
        }

    return $res_array;
}

?>
