phpmvc-projekt
=========
This website is built using Anax-MVC as the foundation and is built as a course assignment at BTH.

Install and setup
-------------------

Clone repository from GitHub.
Use the **webroot** folder as the root folder (other files should not be accessible from the browser).

This site uses composer to download all required dependencies. Run

### Settings

Some files need to be modified in order for the website to work.

* `webroot/.htaccess`  
  Settings for clean URLs. #Rewrite module must be on for clean urls to work. Change base url in #Rewrite base if necessary.
* `app/config/database_mysql.php`  
  Change the database settings.
  
  
### Initializing the database  
After successfully changed .htaccess and database_mysql.php goto **/setup** to initialize the database.