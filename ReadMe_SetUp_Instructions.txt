READ ME FILE
##################3

This application is released under LGPL license.


INSTRUCTIONS 
#################################

1. MINIMUM REQUIREMENTS / ENVIRONMENT
------------------------------------------------------------------------
a) MySQL version 5.x
b) PHP 5.x

If new to PHP and MySQL installation, please check out WAMP and XAMPP servers for Windows Environment

Please Note : This application is developed using the mysql_ functions. However, you may convert to mysqli_ functions should you wish to use higher versions of PHP


2. HOW TO INSTALL / SETUP
--------------------------------------------------------------------------
a) Extract the majivoice_github.zip file into your www folder
b) Database Connection 
(i) Create a Database eg: majivoice_github
(ii) Import the db.sql file from www folder into your database

IMPORTANT / CUSTOMIZATIONS
Incase your database name is different and other configuration, please change variable settings in
a) /Connections/connSystem.php ; and
b) /assets_backend/be_includes/config_settings.php

(iii) To log in, just go to your browser to the path of your installation eg:http://localhost/customerfeedback/ and the login screen should load automatically

Default Log In: User => admin  Password => admin

(iv) To change company name from Demo Company, please go change the values in usrteam table

3. MORE CONFIGURATION / SMS
(i) Incoming SMSes should be stored in the mdata_in_sms table while outgoing smses are stored in the mdata_out_sms table by the system


4. USERS AND  MANAGEMENT
(i) To manage users and workflow configuration, please go to System Admin menu


That's It!



