#   Author: Lars Wilhelmsen <lw@ez.no>   
#  Copyright (C) 2000 eZ systems. All rights reserved.
#

#
# Dumping data for table 'CategoryTable'
#

INSERT INTO CategoryTable VALUES (1,'Nyheter','Nyheter for eZ system','N');
INSERT INTO CategoryTable VALUES (2,'Programmering','Programmeringsdiskusjoner','N');
INSERT INTO CategoryTable VALUES (3,'Humor & morsomheter','Hu mor','N');
INSERT INTO CategoryTable VALUES (4,'Standarder','eZ standarder','N');
INSERT INTO CategoryTable VALUES (5,'Grafisk design','Design & interaksjon diskusjoner','N');

#
# Dumping data for table 'ForumTable'
#

INSERT INTO ForumTable VALUES (1,2,'C++','C++ forum','N','N');
INSERT INTO ForumTable VALUES (2,2,'PHP','PHP forum','N','N');
INSERT INTO ForumTable VALUES (3,2,'SQL','SQL forum','N','N');
INSERT INTO ForumTable VALUES (4,1,'eZ nyheter','Generelle nyheter','N','N');
INSERT INTO ForumTable VALUES (5,4,'Dokumenter','Dokumentstandarder','N','N');
INSERT INTO ForumTable VALUES (6,4,'PHP','PHP Standard','N','N');
INSERT INTO ForumTable VALUES (7,4,'SQL','SQL Standard','N','N');
INSERT INTO ForumTable VALUES (8,4,'C++','C++ Standard','N','N');
INSERT INTO ForumTable VALUES (9,5,'Generelt','Generell  designdiskusjon','N','N');

#
# Dumping data for table 'UserTable'
#

INSERT INTO UserTable VALUES (0,0,'','','Anonym','','','','','','','','','','','','','','');
INSERT INTO UserTable VALUES (1,0,'Lars','Wilhelmsen','lw','lw@ez.no','7488bbbc1f8c27bb','','E','','','','','','','','','','');

