phpmvc-projekt
=========
This website is built using Anax-MVC as the foundation and is built as a course assignment at BTH.

Install and setup
-------------------

Clone repository from GitHub.
Use the **webroot** folder as the root folder (other files should not be accessible from the browser).

Run `composer install --no-dev` to download all required dependencies.

### Settings

* Apache need to be configured to read .htaccess files and allow override.
* Some files need to be modified in order for the website to work.
  * `webroot/.htaccess`  
  Change RewriteBase to match your setup. 
  * `app/config/database_mysql.php`  
  Change the database settings.
  
  
### Initializing the database  
After successfully changed .htaccess and database_mysql.php goto **/setup** to initialize the database.
