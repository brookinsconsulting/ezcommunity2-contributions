#!/bin/bash

modules=`ls -d ez*`

for module in $modules
do
    if [ -f $module/sql/postgresql/$module.sql ]
	then cat $module/sql/postgresql/$module.sql >> sql/publish_postgresql.sql
    fi
    if [ -f $module/sql/mysql/$module.sql ]
	then cat $module/sql/mysql/$module.sql >> sql/publish_mysql.sql
    fi
done
