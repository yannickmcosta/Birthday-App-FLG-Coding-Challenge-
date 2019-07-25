# FLG Coding Challenge

This repository contains the source code for the coding challenge set by FLG.

Take a look!

https://flg-birthday.mccabecosta.com

**Public Profile**

* Website: https://mccabecosta.com
* GitHub: yannickmcosta

**Other Projects**

* [getCP](https://admin.getcp.io)
* [List kept up to date on my main website](https://mccabecosta.com/projects)

- - -

### File Structure

**Files**

* api.php - The API Endpoint
* index.php - The Homepage/Frontpage of the project
* process.php - The HTML Form processing page

* classes/class.api.php - The API Class
* classes/class.DBConnection.php - A MySQLi DB Connection Class, used in all my projects, written many years ago and actively updated/maintained
* classes/class.interactor.php - Used by process.php as an API Library and Interactor class

* config/config.php - App-wide configuration variables and definitions

* includes/corejs.php - Centrally manageable core and required Javascript libraries
* includes/head.php - Centrally manageable HTML head contents

**Folders**

* assets - Storage location for CSS, Fonts, Images, Javascripts, etc - Not used in this project
* classes - Storage location for PHP Classes
* config - Storage location for App-wide configuration files
* docs - Storage location for Markdown Documentation
* includes - Storage location for App-wide includes

- - -

### Running the application

The application is available at https://flg-birthday.mccabecosta.com, however can be run on any LEMP stack using the configuration files and schemas supplied in `INSTALL`

- - -

### What would I have done differently

* Full AJAX communication with the API, removing the requirement for a `process.php` style page
* Single AJAX call to the API to retrieve the birthday data for the three different tables
* Written a custom exception handler
* More elegant handling of DateTimes using PHP DateTime

- - -

### External Sources

The following external sources and boilerplate material were used in the creation of this app

**Start Bootstrap's Bare HTML5 template**

* URI: https://startbootstrap.com/templates/bare/
* Description: This was used as a starting point for the frontend of the web app, as Bootstrap is a widely implemented framework allowing for rapid development of responsive web applications.

**CDNJS**

* URI: https://cdnjs.com
* Description: Any external CSS and Javascript libraries are served via CloudFlare's CDNJS service, allowing for reduced asset management and storage on the serverside.

**SweetAlert**

* URI: https://sweetalert.js.org
* Description: SweetAlert allows the replacement of standard Javascript alerts with visually improved, and better supported, custom Javascript alerts.