#!/bin/bash

# PostgreSQL
cat ezsession/sql/postgresql/ezsession.sql > sql/publish_postgresql.sql      
cat ezuser/sql/postgresql/ezuser.sql  >> sql/publish_postgresql.sql
cat ezsitemanager/sql/postgresql/ezsitemanager.sql  >> sql/publish_postgresql.sql
cat ezurltranslator/sql/postgresql/ezurltranslator.sql >> sql/publish_postgresql.sql
cat ezlink/sql/postgresql/ezlink.sql >> sql/publish_postgresql.sql
cat ezad/sql/postgresql/ezad.sql >> sql/publish_postgresql.sql
cat ezimagecatalogue/sql/postgresql/ezimagecatalogue.sql >> sql/publish_postgresql.sql
cat ezarticle/sql/postgresql/ezarticle.sql >> sql/publish_postgresql.sql
