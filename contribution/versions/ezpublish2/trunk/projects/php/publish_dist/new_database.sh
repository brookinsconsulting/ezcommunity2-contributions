#!/bin/sh
echo "Dropping database"
mysqladmin drop publish
echo "Creating database"
mysqladmin create publish
echo "Adding Tables"
mysql publish < sql/publish.sql 
echo "Adding Data"
mysql publish < sql/data.sql 
echo "Upgrading"
mysql publish < upgrade/2_1_to_2_1_1/2_1_to_2_1_1.sql