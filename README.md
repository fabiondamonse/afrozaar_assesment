## Table of contents
* [General info](#general-info)
* [Technologies](#technologies)
* [Setup](#setup)

## General info
The code is a bit segmented, started in one direction but due to work fires just had to get it done when Friday morning came along 


getProductsInCategory() - Implemented in app\Model\CategoryCollection

doesProductExistInCategory()  - Implemented in app\Model\CategoryCollection

All classes other than added via composer is in /app.

/app/Model contains the base data classes

/app/Helper has just cookie cutter classes that is used statically.

/app/Controller contains the controller for the ajax calls

## Technologies
Project is created with:
* Apache/2.4.52 (Ubuntu)
* PHP 8.1.16

## Setup
To run this project, set the project root as the web root in apache.
* The sample data is created and set in the index.php and saved in a session.
* data is retrived from the session via the controllers and sent back to the front-end via ajax 