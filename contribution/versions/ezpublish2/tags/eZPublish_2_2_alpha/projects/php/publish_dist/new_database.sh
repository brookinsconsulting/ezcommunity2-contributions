#!/bin/sh
# This script will create the publish MySQL database with all the patches applied.
# by Chris Mason

echo -n "Name of Database to create [publish]:"
read DBNAME
if [ -n "$DBNAME" ]; then
   echo -n 'Database is' $DBNAME
else
   DBNAME="publish"
fi
echo "Database "$DBNAME" will be created"

echo -n 'Mysql root password: '
read PASS
if [ -n "$PASS" ]
then
   echo "Password is $PASS"
   echo "Dropping database"

      if  mysqladmin -u root -p'$PASS' drop $DBNAME
      then
         echo "Dropping database"
      else
         echo "No database to drop"
      fi

   echo "Creating database"
   mysqladmin -u root -p'$PASS' create $DBNAME
   echo "Adding Tables"
   mysql -u root -p'$PASS' $DBNAME < sql/publish.sql 
   echo "Adding Data"
   mysql -u root -p'$PASS' $DBNAME < sql/data.sql 
   echo "Upgrading"
   mysql -u root -p'$PASS' $DBNAME < upgrade/2_1_to_2_1_1/2_1_to_2_1_1.sql
   mysql -u root -p'$PASS' -e"grant all on $DBNAME.* to $DBNAME@localhost identified by '$DBNAME' " 
else
   echo "Blank Password"
   echo "Dropping database"

   if  mysqladmin -u root drop $DBNAME
   then
      echo "Dropping database"
   else
      echo "No database to drop"
   fi

   echo "Creating database"
   mysqladmin -u root create $DBNAME
   echo "Adding Tables"
   mysql -u root $DBNAME < sql/publish.sql 
   echo "Adding Data"
   mysql -u root $DBNAME < sql/data.sql 
   echo "Upgrading"
   mysql -u root $DBNAME < upgrade/2_1_to_2_1_1/2_1_to_2_1_1.sql
   mysql -u root -e"grant all on $DBNAME.* to $DBNAME@localhost identified by '$DBNAME' "   
fi


