# Task Management App Exam

## Problem

### Create a very simple Laravel web application for task management

- Create task (info to save: task name, priority, timestamps)
- Edit task
- Delete task
- Reorder tasks with drag and drop in the browser. Priority should automatically be updated based on this. #1 priority goes at top, #2 next down and so on.
- Tasks should be saved to a mysql table.

BONUS POINT: add project functionality to the tasks. User should be able to select a project from a dropdown and only view tasks associated with that project.

You will be graded on how well-written & readable your code is, if it works, and if you did it the Laravel way.

Include any instructions on how to set up & deploy the web application in your Readme.md file in the project directory (delete the default readme).

Zip up the folder with your web application when you are finished and upload it here.

## Instruction

### How to run the app

- If received as compressed file, export the source code from zip file.
- If not, clone the source code from this like:

  [task-management-app](https://github.com/elmoya/task-management-app)

- Use terminal and navigate to the folder where the source code was extracted.
- Check if you have these versions installed in your system:

  - PHP 8.1
  - Composer 2.8.1

- Install dependencies.

  ```php
  composer install
  ```

- Run migration.

  ```php
  php artisan migrate
  ```

- It will ask "Would you like to create it?". Press "Yes".

- Run it in development.

  ```php
  php artisan serve
  ```

- Go to provided link and by default is like this:

  [http://127.0.0.1:8000](http://127.0.0.1:8000)
