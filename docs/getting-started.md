# Getting Started

This guide will walk you through the process of setting up your local development environment.

## Prerequisites

- **Node.js:** v18.x or later
- **PHP:** v8.2 or later
- **Composer:** v2.x or later
- **MySQL:** v8.x or later

## Installation

1. **Clone the repository:**

   ```bash
   git clone https://github.com/your-repo/project.git
   cd project
   ```

2. **Install backend dependencies:**

   ```bash
   composer install
   ```

3. **Install frontend dependencies:**

   ```bash
   npm install
   ```

4. **Set up your environment:**

   - Copy the `.env.example` file to `.env`:
     ```bash
     cp .env.example .env
     ```
   - Generate an application key:
     ```bash
     php artisan key:generate
     ```
   - Configure your database credentials in the `.env` file.

5. **Run database migrations:**

   ```bash
   php artisan migrate --seed
   ```

## Running the Project

- **Start the development server:**

  ```bash
  npm run dev
  ```

- **Start the backend server:**

  ```bash
  php artisan serve
  ```

Your application should now be running at `http://localhost:8000`.
