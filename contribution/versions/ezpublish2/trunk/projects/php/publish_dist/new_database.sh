#!/bin/sh
# This script will create the publish MySQL database with all the patches applied.
echo -n 'Mysql root password:'
read PASS
if $PASS
then
   echo "Blank Password"
   echo "Dropping database"
   
   if  mysqladmin -u root drop publish 
   then
      echo "Dropping database"
   else
      echo "No database to drop"
   fi
   
   echo "Creating database"
   mysqladmin -u root create publish
   echo "Adding Tables"
   mysql -u root publish < sql/publish.sql 
   echo "Adding Data"
   mysql -u root publish < sql/data.sql 
   echo "Upgrading"
   mysql -u root publish < upgrade/2_1_to_2_1_1/2_1_to_2_1_1.sql
   mysql -u root -e"grant all on publish.* to publish@localhost identified by 'publish' "
   
else
   echo "Password is $PASS"
   echo "Dropping database"
   
      if  mysqladmin -u root -p'$PASS' drop publish 
      then
         echo "Dropping database"
      else
         echo "No database to drop"
      fi

   echo "Creating database"
   mysqladmin -u root -p'$PASS' create publish
   echo "Adding Tables"
   mysql -u root -p'$PASS' publish < sql/publish.sql 
   echo "Adding Data"
   mysql -u root -p'$PASS' publish < sql/data.sql 
   echo "Upgrading"
   mysql -u root -p'$PASS' publish < upgrade/2_1_to_2_1_1/2_1_to_2_1_1.sql
   mysql -u root -p'$PASS' -e"grant all on publish.* to publish@localhost identified by 'publish' " 
fi


