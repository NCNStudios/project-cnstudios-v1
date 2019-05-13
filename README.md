# How to install Project CNStudios V1
This is a project in my learning process. Maps and location search.

# 1. Operating environment
Test operation on localhost. Download and install the XAMPP software package at: https://www.apachefriends.org/index.html
Change Port Apache to 8080 (File httpd.conf)
Change Https port to 4433 (File httpd-ssl.conf)
 
# 2. Website package
 ▶ Download the project source code at: https://github.com/NCNStudios/project-cnstudios-v1
 ▶ In the downloaded data, unzip the CNStudios.zip file to the Xampp htdocs installation directory, for example: C:\xampp\htdocs\CNStudios
 
# 3. Restore the database
 ▶ Start Xampp => start Apache, MySQL => Go to PHPMySQL management page at: http://localhost:8080/phpmyadmin
 ▶ Login to initial account:
      ● Username: root
      ● Password: Empty
 ▶ Create and login new user accounts:
      ● Username: cnstudios
      ● Password: Chinguyen5161#
 ▶ Restore the website database via the attached file "cnstudios.sql":
      ● Choose Import => Select file => Select file "cnstudios.sql" => GO.
      ● The database includes 5 tables: users, homes, bills, members, polylines.
# 4. Use
    Access the website at the extracted address, for example: http://localhost:8080/CNStudios/

