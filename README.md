# Krokology Todo List Application

## Overview

This is a simple Todo List application built using the Laravel framework (version 11) and MySQL. The application supports token-based user authentication, CRUD operations for todos, file attachments, pagination, and email notifications.

## Features

- **User Authentication**: Token-based authentication system for secure access.
- **Manage Todos**: Resourceful routes for managing todos (GET, POST, PUT, DELETE).
- **Image Attachments**: Users can attach images to their todos.
- **Database Seeding**: Pre-populates the database with 1000 todos and supports pagination.
- **Email Notifications**: Simulated email notifications when todos are created or updated.
- **Search Functionality**: Efficient search functionality to find todos based on user input.
- **Unit Testing**: Includes unit tests for creating todos.

## Getting Started

### Prerequisites

Make sure you have the following installed:

- Docker
- Docker Compose

### Clone the Repository

```bash
git https://github.com/AbdulrahmanSoliman25/Krokology-PHP-Laravel-Technical-Task-Solution-2024.git
cd Krokology-PHP-Laravel-Technical-Task-Solution-2024
```

### Docker Setup

**Build and Start the Docker Containers**:

- Open your terminal and navigate to the project root directory. Run the following command to build and start the containers in the background:

```bash
docker compose up --build -d
```
- I Case of the Supervisor not working properly please run the folloing command in the php container I you want to work the mail queue to be able to see the emails on the Mailhog 

```bash
docker compose exec php bash 

then 

php artisan queue:work
 ```

- To stop the containers and remove them 
```bash
docker compose down -v
```

### Accessing the Application

Once your Docker containers are up and running, you can access the application through the following methods:

1. **Web Application**:

   - The main application will be available at [http://localhost](http://localhost) *frontend not finished*.

2. **PhpMyAdmin**:

   - To manage the MySQL database, PhpMyAdmin can be accessed at [http://localhost:8080](http://localhost:8080).
   - Log in using the following credentials:
     - **Username**: `krokology`
     - **Password**: `krokology`

3. **API Endpoints**:

   - Use tools like Postman or your preferred API client to interact with the application’s API.
   - The base URL for the API is [http://localhost/api](http://localhost/api).
   - Key API endpoints include:
     - **POST /api/register**: Register a new user
     - **POST /api/login**: Log in and receive a token
     - **POST /api/todos**: Fetch all todos (supports pagination)
     - **GET /api/todos/{id}**: Fetch a single todo by ID
     - **POST /api/todos**: Create a new todo (attach an image if desired)
     - **PUT /api/todos/{id}**: Update an existing todo (attach an image if needed)
     - **DELETE /api/todos/{id}**: Delete a todo by ID

4. **Mailhog** (for email simulation):
   - Access Mailhog at [http://localhost:8025](http://localhost:8025) to view email notifications sent by the application.



## Folder Structure

Here’s a brief overview of the folder structure:
```
/app
│
├── /backend                 # Contains the Laravel backend code
│   ├── /app                 # Application logic, models, controllers, etc.
│   ├── /config              # Configuration files
│   ├── /database            # Migrations and seeders
│   ├── /public              # Publicly accessible files (e.g., index.php, assets)
│   ├── /resources           # Views, localization files, and raw assets
│   ├── /routes              # API and web routes definitions
│   ├── /storage             # Temporary file storage, logs, and uploads
│   ├── /tests               # Unit and feature tests
│   └── .env                 # Environment configuration file
│
├── /frontend                # Contains frontend code (if applicable)
│   ├── /src                 # Source code for frontend components
│   ├── /public              # Public files for the frontend
│   └── package.json         # Frontend dependencies and scripts
│
├── /nginx                   # Nginx configuration files
│   ├── default.conf         # Nginx server configuration
│
├── /php                     # Docker-related PHP configuration and Dockerfile
│   ├── Dockerfile           # Dockerfile for building the PHP image
│   ├── Dockerfile.supervisor # Dockerfile for Supervisor setup (if applicable)
│   ├── supervisord.conf     # Supervisor configuration for managing queue workers
│
├── /postman                 # Postman collections and environment files
│   ├── krokology.postman_collection.json  # Postman collection for API testing
│   └── krokology.postman_environment.json  # Environment variables for Postman
│
├── docker-compose.yml        # Docker Compose configuration file
└── README.md                # Project documentation and setup instructions
```