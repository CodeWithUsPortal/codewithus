## About Project

It's a portal for CodeWithUs

## License

The project is proprietary of [CodeWithUs.com](http://codewithus.com/)

## Gitbhub
>git add . <br>
git commit -m "" <br>
git push -u origin master <br>


## How to run the project
1. Change the credentials for Database in .env file, also add Asana API Key in .env file.
2. Run: php artisan migrate (It will generate all the tables in database).
3. Run: php artisan db:seed (It will add all the seeded data into relevant tables inside database.)


## CPanel

When project is uploaded on cPanel, perform the following steps:

1. Upload codewithus (or any laravel project) inside the domain's folder (in this case its portal.codewithus.com).
2. Move all the files from the project's folder to the root folder of the domain. e.g., move all the files from codewithus (laravel project) to the portal.codewithus.com (root folder of the subdomain).
3. change the name of file server.php to index.php
4. move .htaccess file from public folder to the root-folder.
5. Change the content of .htaccess file with the following content:

<pre>
Options -MultiViews -Indexes

RewriteEngine On

# Handle Authorization Header
RewriteCond %{HTTP:Authorization} .
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
# Redirect Trailing Slashes If Not A Folder...
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_URI} (.+)/$
RewriteRule ^ %1 [L,R=301]

# Handle Front Controller...
RewriteCond %{REQUEST_URI} !(\.css|\.js|\.png|\.jpg|\.gif|robots\.txt)$ [NC]
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [L]
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_URI} !^/public/
RewriteRule ^(css|js|images)/(.*)$ public/$1/$2 [L,NC]
</pre>

<strong>Note:</strong> After performing the steps 3,4 and 5, now you can access your laravel project at the root url, e.g., portal.codewithus.com. And without performing these two steps, we had to access the project at: portal.codewithus.com/codewithus/public.


## Command
To create Migration run: php artisan make:migration create_users_table
To create Model with Migration run : php artisan make:model Post -m
To create Model Factory run : php artisan make:factory PostFactory
To create Seeders run: php artisan make:seeder UsersTableSeeder
To create Controller run: php artisan make:controller HomeController
To create Resource run: php artisan make:resource Task
To create Resource Collection run: php artisan make:resource TaskCollection
To create Middleware run: php artisan make:middleware Admin

To run a single migration: php artisan migrate
To create all migrations run: php artisan migrate
To refresh migrations run : php artisan migrate:fresh
To create all seeders run : php artisan db:seed
To create one specific seeder : php artisan db:seed --class=UsersTableSeeder

## Laravel Jobs
To create a new Cron Job, we need to create a command which will be executed by specified intervals by the Cron Job.
1. Create a command using: php artisan make:command CurrentTasks --command=current:tasks
2. Register and schedule the command in App\Console\Kernel.php like this: Commands\CurrentTasks::class and to schedule it use: $schedule->command('current:tasks')->hourly();

## VueJS
1. Run : npm install. <br>
   It will install all the packages which are in package.json.<br>
   **Note** I have changed the default package.json file to overcome some errors.
2. Run: npm run dev. <br>
   This command will compile all the JavaScript files. Run this command every time you make changes in app.js (/resources/assets/js/app.js) or run: npm run watch.


**Note**: Inside the laravel blade, where you need to call any Vue-component, always make sure that the src="/js/app.js" refers to the app.js file inside the /public/js/ folder and not the resources/assets/js/. Otherwise it will give errors like "require is not defined". Because the /public/js/ folder contains compiled files. 
 

## Common Errors
1. After uploading the project on hosting, while making call to database, you might get the error of "Expecting { or ; ..." . This occurs if inside the model of that table you haven't put the " protected $table = 'locations'; "

