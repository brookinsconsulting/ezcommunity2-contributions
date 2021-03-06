/*Style Sheet for Version 5 Browsers */

/* Please Order Styles by Use In Application, Most Important to Lestat */

/*The Body Background */
body {
	background-color : #ffffff;
}

/* Layer Styles */

.LayerContentL {
/*        border-left : 1px solid #000000;  */

        margin-top : 6px;
        font-family : Arial, Helvetica, sans-serif;
        font-size : 12px;

        padding: 10px;
        margin-bottom: 2px;
        margin-top: 2px;
}

.LayerContent {
/*        border-left : 1px solid #000000;  */

        font-family : Arial, Helvetica, sans-serif;
        font-size : 12px;
}

.LayerMain {
    width: 76%;
	position: absolute;
	left:169px;
	top:152px;
	float: right;
 	z-index:4;
	width: 62%;
    margin-top: 12px;
}
.LayerMain p { 
	margin-top : 6px;

        font-family : Arial, Helvetica, sans-serif;
        font-size : 12px;

}
.LayerMain h1 {
        color : #999999;
        font-family : Arial, Helvetica, sans-serif;
        font-size : 16px;
        margin-bottom : 12px;
        margin-top : 0px
}
.LayerMain h2 {
        color : #000000;
        font-family : Arial, Helvetica, sans-serif;
        font-size : 16px;
        margin-bottom : 12px;
        margin-top : 30px
}
.LayerMenuDate {
        position:relative;
        left:0px; right:0px;
        color : rgb(0, 0, 0);
        font-family : Arial, Helvetica, sans-serif;
        font-size : 12px;
	font-weight: bold; 
}

/* the silver menu */
.menuContainerItem { 
	cursor: pointer;
	padding-left: 5px ;
	padding-top: 2px;
	padding-bottom: 2px;
	color: #444444;
	background-image: url('/design/nsb/images/menubg.png');
}
.menuContainer { padding-bottom: 2px; }
.menuContainerCell {  
	border-top: 1px solid #333;
	border-left: 1px solid #333;
	border-right: 1px solid #333;
	border-bottom: 1px solid #333;
	height: 10px; padding-top: 1px; padding-bottom: 1px; }

/* the v1 green menu */
.LayerMenu {
	right:0px;
        top:5px; 
	width:155px;

	align: center;
        font-family : Arial, Helvetica, sans-serif;
        font-size : 12px;

        z-index:1
}
.LayerLogo {
	
	position:relative; 

        top:0px;
        width:152.5px;
	left:0px; 
	right:0px;
	z-index:1
}
/* Div tags can use # - ID's */
#LayerCopyright {
	position: relative;
	text-align: center;
	bottom: 0;
	width: 97%;

 	color : #339999;
        font-family : Arial, Helvetica, sans-serif;
        font-size : 11px;
        margin-top : 90px;
        margin-bottom: 6px;
}
div.gpl_copyright {
	font-size: 8pt;
	border: 2px solid #ffbd00;
	padding: 4px;
	background-color: #f9f9f9;
	border-style: solid;
	border-width: thin;
}
/*Style Applied to the Paragraphs for Copyright-Small-Text */
.categoryListItems {
/*        color: #7c7a6e;   */
        font-family: Arial, Helvetica, sans-serif;
        font-size: 11px;
	color : #333366;	

	padding-bottom: 10px;
        margin-bottom: 2px;
}

.categoryListItems a:link { color : #333366}
.categoryListItems a:visited { color : #003366 }
.categoryListItems a:hover { color : #000099}
.body {
        color: #7c7a6e;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;

        margin-bottom: 2px;
        margin-top: 2px;
}
p.body {
        color: #000000;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        margin-bottom: 2px;
        margin-top: 2px;
}
.bgdark
{
        background-color: #f6f6f6;
}
.bglight
{
        background-color: #EDEDED;
}


/* Lists*/
li {
        font-family : Arial, Helvetica, sans-serif;
        font-size : 14px;
        margin-bottom : 12px;
        margin-right : 48px;
        margin-top : 12px
}
ol { color : #000000 }
ul { color : #000099 }

/*Styles for the Main Content Layer */

.rfpListIndent {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 11px;
        color: #7c7a6e;
        padding-left: 0px;
        padding-right: 100px;
        padding: 10px;
}
.box+-text
{
	font-weight: bold;
	margin-bottom: 4px;
}

span {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
}
.p { 
	font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        color: #7c7a6e;
        padding-left: 0px;
        padding-right: 100px;
        padding: 20px 0px;
}
.div {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        color: #7c7a6e;
        padding-left: 0px;
        padding-right: 100px;
        padding: 20px 0px;
}
.subdiv {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #7c7a6e;
	padding-left: 0px;
}
.subdiv img {
	float: right;
	padding: 0% 35% 2%;
}
.subdiv p {
	margin: 0px 0px;
}
.subdiv h1 {
	color : #999999;
	font-family : Arial, Helvetica, sans-serif;
	font-size : 16px;
	margin-bottom : 12px;
	margin-top : 0px
}
.subdiv h2 {
	color : #000000;
	font-family : Arial, Helvetica, sans-serif;
	font-size : 16px;
	margin-bottom : 0px;
	margin-top : 0px
}
.subdiv a:link { color : #333366}
.subdiv a:visited { color : #003366 }
.subdiv a:hover { color : #000099}

/*Style for The Little Icon Layers */
.icondiv {
	border-bottom : 1px solid #CCCCCC;
	border-left : 0px solid #CCCCCC;
	border-right : 1px solid #CCCCCC;
	border-top : 1px solid #CCCCCC
}
.icondivL {
	border-bottom : 1px solid #CCCCCC;
	border-left : 1px solid #CCCCCC;
	border-right : 0px solid #CCCCCC;
	border-top : 1px solid #CCCCCC
}

/*Style Applied to the Menu Layer */
.navDiv	{
	color : #669966;
	font-family : Arial, Helvetica, sans-serif;
	font-size : 12px
}
.navDiv p { margin-bottom : 12px; margin-top : 6px }
.navDiv h1 {
	color : #000099;
	font-family : Arial, Helvetica, sans-serif;
	font-size : 16px;
	margin-left : 0px;
	margin-top : 0px;
	text-align : right
}
.navDiv h2 {
	color : #000099;
	font-family : Arial, Helvetica, sans-serif;
	font-size : 16px;
	margin-bottom : 0px;
	margin-left : 0px;
	margin-right : 0px;
	margin-top : 24px
}
/*Style Applied to the Paragraphs for Main Section Links */
p.navButts {
	color : #000099;
	font-family : Arial, Helvetica, sans-serif;
	font-size : 11px;
	margin-bottom : 4px;
	margin-top : 6px
}
.navButts a {
	background-color : #339999;
	border : 2px outset #669999;
	color : #000000;
	display : block;
	padding-bottom : 2px;
	padding-left : 12px;
	padding-top : 4px;
	text-decoration : none;
	width : 100%
}
.navButts a:hover {
	background-color : #669999;
	border : 2px inset #339999;
	color : #000000;
	text-decoration : none
}

/*Style Applied to the Paragraphs for the Sub-Section Links */
p.navSubs {
	background-color : #669966;
	color : #ffffff;
	font-family : Arial, Helvetica, sans-serif;
	font-size : 11px;
	margin-bottom : 2px;
	margin-left : 2px;
	margin-top : 2px
}
.navSubs a {
	background-color : #669966;
	color : #ffffff;
	display : block;
	padding-left : 14px;
	text-decoration : none;	
	width : 100%;
	border : 1px groove #000000;
}
.navSubs a:hover {
	background-color : #336633;
	color : #ffffff;
	text-decoration : none;
	border : 1px groove #000000;
}

/*Style Applied to the Paragraphs for Static Down-State Main Section Links */
p.navViz {
	color : #0000CC;
	font-family : Arial, Helvetica, sans-serif;
	font-size : 11px;
	font-weight : normal;
	margin-bottom : 6px;
	margin-left : 0px;
	margin-top : 6px
}

.navViz a {
	background-color : #666699;
	border : 1px solid #666666;
	color : #ffffff;
	display : block;
	padding-bottom : 2px;
	padding-left : 12px;
	padding-top : 4px;
	text-decoration : none;
	width : 100%
}

/*Turns off Borders around Hyperlinked Images*/
/* img { border : 0px; display : block }
.inlinimg { display : inline } */

/*Styles Applied to Form Elements */
form {
	color : #000000;
	font-family : Arial, Helvetica, sans-serif;
	font-size : 12px;
	margin-right : 36px;
	margin-top : 0px;
}
input { background-color : #e3e3e3; border: 1px solid black; padding: 3px 3px 3px 3px; }
input:focus { background : #f9f9f9; border: 1px solid #cc730d; }
textarea {
	background-color : #bfc0c2;
	border : 1px solid #2379CF;
	font-family : Arial, Helvetica, sans-serif;
	font-size : 12px 
}
textarea:foxus { background-color: #e3e3e3; border: 1px solid #cc730d;}
.checks { background-color : #efefde; border: 1px solid black; }
.formBut { background-color : #a19f91; }

/*Styles Applied to Float Forms left or right */
.formfloatright { float : right }
.formfloatleft { float : left }
textarea.box
{
	background-color: #e3e3e3; border: 1px solid black; padding: 3px 3px 3px 3px;
}
textarea.box:focus
{
 	background-color: #f9f9f9; border: 1px solid #cc730d;
}
hr 
{ 
	color: #cc730d;
 	background-color: #cc730d;
 	height: 1px;
 	border: 0;
 	width: 100%;
}
input.stdbutton, input.okbutton
{
	background-image: url('/design/nsb/images/buttonbg.png');
	background-repeat: repeat-x;
	border-left: 1px solid #bfc0c2;
	border-top: 1px solid #bfc0c2;
	padding: 2px;
	height: 25px;
	font-size: 13px;
}

/*Styles Applied to Images left or right */
.imgfloatright {
	float: right;
}
.imgfloatleft {
	float: left;
}

/* The Captions and Descriptions For the Portfolio Images */
.imgCaptionshd {
	color : #b40101;
	font : bold 14px Arial, Helvetica, sans-serif;
	margin-bottom : 6px;
	margin-top : 0px
}
.imgCaptions {
	color : #a19f91;
	font : 12px Arial, Helvetica, sans-serif;
	margin : 3px 0px 6px 3px
}
.imgCaptionsbg {
	background-image : url(../assets/img_main/loading.gif);
	background-repeat : no-repeat;
	color : #a19f91;
	font : 12px Arial, Helvetica, sans-serif;
	margin-bottom : 6px;
	margin-top : 3px
}
.imgCaptions a:link { color : #b40101; text-decoration : none }
.imgCaptions a:visited { color : #a19f91; text-decoration : underline }
.imgCaptions a:hover { color : #b40101; text-decoration : underline overline }
.LayerMain a { color: #264D7D; text-decoration: none; }
.LayerMain a:link { color: #264D7D; text-decoration: none; }
.LayerMain a:visited { color: #666666; text-decoration: none; }
.LayerMain a:hover { color: #333333; text-decoration: underline; }
a:link { color: #264D7D; text-decoration: none; }
a:visited { color: #666666; text-decoration: none; }
a:hover { color: #333333; text-decoration: underline; }
