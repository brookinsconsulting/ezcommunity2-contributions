<?

/*
  En klasse som håndterer SQL queries. Lager query setninger fra
  tekststrenger. 
  
*/

class eZQuery
{
    function eZQuery( $fields, $queryText )
    {
        $this->Fields = $fields;
        $this->QueryText = $queryText;        
    }

    function buildQuery( )
    {
        $field = "KeyWords";

        $QueryText = $this->QueryText;
        
        $QueryText = trim( $QueryText );
        $QueryText = ereg_replace( "[ ]+", " ", $QueryText );
        $queryArray = explode( " ", $QueryText );

        $query = "";
        for ( $i=0; $i<count($queryArray); $i++ )            
        {
            for ( $j=0; $j<count($this->Fields); $j++ )
            {
                $queryItem = $queryArray[$i];
                if ( $queryItem[0] == "-" )
                {
                    $queryItem = ereg_replace( "^-", "", $queryItem );
                    $not = "NOT";
                }
                else
                    $not = "";
                
                $queryItem = $this->Fields[$j] ." " . $not . " LIKE '%" . $queryItem . "%' ";

                if ( $j > 0 )                    
                    $queryItem = "OR " . $queryItem . " ";
                    
            
                $query .= $queryItem;
            }

            if (  count( $queryArray) != ($i+1) )
                $query = " (" . $query . ") AND ";
            else
                $query = " (" . $query . ") ";
            
        }
        return $query;
    }

    var $Fields;
    var $QueryText;    
}
?>
