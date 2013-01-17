OpenCoupon
==========

This is open coupon projects.


Download

	OpenCoupon's source code.
		
		https://github.com/kazu-at-jp/OpenCoupon/archive/master.zip
	
	OpenCoupon is required OnePiece Framework.

		https://github.com/TomoakiNagahara/op-core/archive/stable.zip
		
Install

	Windows example for XAMPP.
	
		C:\www\op-core
		C:\www\OpenCoupon
	
	
	1. Set up virtual host. Added this.

		C:\xampp\apache\conf\extra\httpd-vhosts

			NameVirtualHost *:80

			<VirtualHost *:80>
				ServerName   localhost
				ServerAdmin  root@localhost
				DocumentRoot "C:/xampp/htdocs"
			</VirtualHost>

			<VirtualHost *:80>
				ServerName   local.open-coupon.com
				ServerAdmin  root@localhost
				DocumentRoot "C:/www/OpenCoupon"
				php_value include_path ".;C:\xampp\php\PEAR;C:\www\op-core"
			</VirtualHost>

	2. Set up host name. Added this.
	
		C:\WINDOWS\system32\drivers\etc\hosts
			
			127.0.0.1    local.open-coupon.com
	
	3. Access to http://local.open-coupon.com
	
	4. Database setup.
		
		1. Create database is op_coupon, and user name is op_coupon, password is displayed.
		
		2. Import SQL schema file using phpMyAdmin.
		
			C:\www\OpenCoupon\sql\op_coupon.sql
	



