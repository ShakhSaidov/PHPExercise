# PHPExercise
An interesting PHP Exercise in the form of an SPA that displays CSV data and allows users to add their own data. Initialized the application with **Composer**, used pure **PHP** for developing and **PHPUnit** for testing. 
I initially was planning to use a LAMP or WAMP stack locally on my device, but ran into issues that were taking longer than expected to fix. So, I decided to just use vanilla PHP and finish as much of the requirements for the task as possible in time.
## Completed:

- Display the data in a SPA, with proper CSS styling<br>
- Unique entries are displayed, no duplicates<br>
- Users can upload their own CSV file<br>
- Output is sorted by Run Number in ascending order by default<br>
- Users can also sort by any other column<br>
- Pagination controls added, showing 5 entries per page<br>

## Further Improvements:
- Set up CRUD (Create, Read, Update, Delete) functionality<br>
- Set up hosting<br>

## Prerequisites:
- PHP
- Composer

## Setup:
- Clone the github repo to a local device
- Go to the `PHPExercise` folder
- Run `composer install` to set up the vendor folder
- Run `php -S localhost:8000` to locally run the application
- Visit `localhost:8000` in the browser to view the application
- Run `./vendor/bin/phpunit tests` to run tests with PHPUnit
