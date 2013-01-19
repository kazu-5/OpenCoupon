OpenCoupon
==========

This is open coupon projects.
=============================

Downloading

	To use OpenCoupon, you should download and install the two package: OpenCoupon and OnePiece Framework.
	First, download the following files:

	OpenCoupon's source code:
		
		https://github.com/TomoakiNagahara/OpenCoupon/archive/develop.zip
	
	OnePiece Framework (it is required to use OpenCoupon):

		https://github.com/TomoakiNagahara/op-core/archive/develop.zip


Installing (For Windows users with XAMPP)

	1. Unzip and place the files.
	
	   Unzip the downloaded files and place them into the following folders, respectively.
	
		OpenCoupon		C:¥www¥OpenCoupon
		OnePiece FW		C:¥www¥op-core


	2. Set up a virtual host and specify a document root.
	
		1. Go to the folder 'C:¥xampp¥apache¥conf¥extra¥', and open the file 'httpd-vhosts.conf' by text editor of your choice.
		
		2. Search for the line "#NameVirtualHost *:80", and remove the # symbol to enable it (if it isn't already enabled).
		
		3. Add the following lines to the end of the file:

			<VirtualHost *:80>
				ServerName   localhost
				ServerAdmin  root@localhost
				DocumentRoot "C:/xampp/htdocs"
			</VirtualHost>

			<VirtualHost *:80>
				ServerName   local.open-coupon.com
				ServerAdmin  root@localhost
				DocumentRoot "C:/www/OpenCoupon"
				php_value include_path ".;C:¥xampp¥php¥PEAR;C:¥www¥op-core"
			</VirtualHost>
		
		4. Save change and close the file.
		
		5. (only if Apache is already launched) In XAMPP Control Panel, stop and restart Apache web server for the change to take effect.


	3. Set up host name.
	
	 To set up the host name, do the following:
	
		1. Go to the folder 'C:¥WINDOWS¥system32¥drivers¥etc¥', locate the file 'hosts', and open it by text editor of your choice (Note: this file has no extension).
		
		2. At the end of the file, add the following line:
		
			127.0.0.1    local.open-coupon.com
		
		3. Save change and close the file.


	4. Check access to the virtual host.
	
	 In your web browser, try to access to 'http://local.open-coupon.com'. If installed correctly, A web page with white background and some information (including username, passwrod, and so) in table will be displayd.
	 
	 If a message like 'Object not found' or 'Access forbidden' is displayd on your browser, the vertual host may not be set correctly. In this case, check and correct the settings, and retry it until succeed.


	5. Database setup.
	
	 Create a database and its user in MySQL. In phpMyAdmin, do the following:
		
		1. Create a MySQL database called 'op_coupon'.
		
		2. Create a MySQL user using the following information:

			user name: op_coupon
			password : <use the password which displayd in browser's screen on the first access on Step 4>

		3. Import the following SQL schema file into the database 'op_coupon':
		
			C:¥www¥OpenCoupon¥sql¥op_coupon.sql
		
		4. Access to 'http://local.open-coupon.com' again. This will show a web page with green background and some tables containing various information. 
		
		