# Scripta News
How to run
1. Clone the project https://github.com/TychaK/scripta-backend.git
2. cd to the project directory
3. Run the following commands to run the project in a docker container

### composer install
After running composer install, run:
### cp .env.example .env

### docker-compose build --no-cache
### docker-compose up

4. After the docker container is up run the following commands in the container
### php artisan migrate
### php artisan db:seed
### php artisan passport:install
### php artisan passport:keys

5. After executing the above commands on the container, run the following command to start fetching articles / news from different providers
### php artisan sync:articles

That's it, the articles syncing will start and will be available for users on the frontend.
