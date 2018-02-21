## Installation

Composer setup: [composer](https://getcomposer.org/download/)

NPM setup: [npm](https://docs.npmjs.com/cli/install)

Run in console:

- composer install
- php artisan migrate:fresh
- php artisan db:seed
- npm install

for dev environment:
- npm run dev

for prod envoronment:
- npm run prod

## Add/remove questions & answers

Structure of array for questions data in file database/seeds/QuestionsTableSeeder.php:

`$questions = ['question1' => ['answer1', 'answer2', 'answer3'], ['question2' => ['answerX', 'answerY', ...], ...];`

To change questions just add/change values in `$questions` array and run `php artisan db:seed` in console.

## Application details

### Models placed in `app/Models`:
- `Answer.php` contains all answers with relations to questions 
- `Question.php` contains all questions
- `Report.php` contains info about question, response, user who respond and timestamps
- `User.php` users with relation to reports

### Main logic

Main logic placed in app/Http/Api/UserController.php -- methods are commented