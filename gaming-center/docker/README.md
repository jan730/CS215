to run program, follow the instructions in Assignment 5: phpMyAdmin instructions
add the .env file to the docker folder and run docker compose up -d
docker compose down to stop docker
http://localhost:26011 for phpmyadmin http://localhost:2601 for the website

note: i added the line
    env_file:
      - .env
to the docker-compose.yml to the end of service 1
this allows php to access the .env file and pull the variables out of the $_ENV superglobal
that is how i set up db.php

1 . Environment Setup: phpMyAdmin + MariaDB (Docker)
You will use phpMyAdmin (a web interface) to create your database. In the setup files, you will see MariaDB instead of “MySQL”. MariaDB is an open-source database that is fully compatible with MySQL. For this course, you can treat MariaDB and MySQL as the same thing. The SQL you write in labs for “MySQL” works the same way here.

We will run phpMyAdmin and MariaDB using Docker. You do not need to learn Docker. Just follow the steps below.

1.1 Install Docker Desktop (one time only)
Go to the official Docker website and download Docker Desktop for your operating system (Windows / macOS / Linux).
Install it using the default options.
Restart your computer if the installer asks you to.
You only do this once on your computer.

1.2 Check the project folder structure
You will create a project folder called gaming-center. Your project directory should look like:

gaming-center/
    app/         <- your .html, .js, .css files

    db_data/    <- database data

    docker/      <- Docker-related files will go here
   

Inside the gaming-center/docker folder, you should have docker-compose.yml  and Dockerfile. Do not rename these folders or files. The setup in this tutorial depends on these exact names and locations.

1.3 Create the .env file (very important)
You will create the .env file that tells Docker which ports and passwords to use.

In VS Code, open the docker folder. In the docker folder, create a new file called .env.
Open .env and paste the content below:
APP_PORT=2601 

MARIADB_ROOT_PASSWORD=rootpassword123
MARIADB_DATABASE=gaming_center
MARIADB_USER=gaming_user
MARIADB_PASSWORD=gaming_password123

PHPMYADMIN_PORT=26011

Save the file. You may change the passwords if you want, but then you must remember them and use the same values when logging into phpMyAdmin. Do not change the variable names (APP_PORT, MARIADB_DATABASE, etc.)
1.4 Start Docker Desktop
You must have Docker Desktop running before continuing.

1.5 Start the containers (using VS Code terminal)
We will run the commands from VS Code’s integrated terminal.

Open the terminal. In the terminal, check that you are inside the docker folder. Once you are inside the docker folder, run docker compose up -d.

1.6 Open phpMyAdmin in your browser
In your browser, type http://localhost:26011. You should see the phpMyAdmin login page.

Log in using the values from your .env:

Username = value of MARIADB_USER
→ with the template above: gaming_user

Password = value of MARIADB_PASSWORD
→ with the template above: gaming_password123

After logging in, you should see the database named gaming_center (from MARIADB_DATABASE) in the left sidebar.

Remember: this MariaDB database behaves just like the MySQL you use in labs.
You can think of it as “your MySQL server for this project.”

This is where you will create your tables for Assignment #4.

1.8 If something does not work
phpMyAdmin page does not load

Is Docker Desktop running?

Are you using the correct URL, e.g. http://localhost:26011 (or your PHPMYADMIN_PORT)?

Login fails in phpMyAdmin

Double-check MARIADB_USER and MARIADB_PASSWORD in your .env.

Make sure there are no extra spaces before/after the values.

If you are still stuck, take screenshots of:

your .env file (you may blur/hide the password), and

the error message (browser or VS Code terminal),

and send them to your instructor or TA.

After phpMyAdmin is working, continue with Section 2: Requirements and build your database as described there.




