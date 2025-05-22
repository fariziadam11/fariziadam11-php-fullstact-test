# MyClient CRUD Application

This application implements a CRUD (Create, Read, Update, Delete) system for the `my_client` table in PostgreSQL with Redis integration for caching and AWS S3 for file storage.

## Features

1. **PostgreSQL Database Integration**
   - Complete CRUD operations for the `my_client` table
   - Soft delete functionality (updates `deleted_at` instead of removing records)

2. **Redis Integration**
   - Automatic Redis caching of client data using the slug as the key
   - Persistent storage of JSON data in Redis
   - Automatic cache invalidation on update and delete operations

3. **AWS S3 Integration**
   - File uploads for client logos stored in S3
   - Automatic file management (deletion of old files when updated)

## Implementation Details

### Database Schema

The `my_client` table has the following structure:

```sql
CREATE TABLE my_client ( 
  id int NOT NULL GENERATED ALWAYS AS IDENTITY, 
  name char(250) NOT NULL, 
  slug char(100) NOT NULL, 
  is_project varchar(30) check (is_project in ('0','1')) NOT NULL DEFAULT '0', 
  self_capture char(1) NOT NULL DEFAULT '1', 
  client_prefix char(4) NOT NULL, 
  client_logo char(255) NOT NULL DEFAULT 'no-image.jpg', 
  address text DEFAULT NULL, 
  phone_number char(50) DEFAULT NULL, 
  city char(50) DEFAULT NULL, 
  created_at timestamp(0) DEFAULT NULL, 
  updated_at timestamp(0) DEFAULT NULL, 
  deleted_at timestamp(0) DEFAULT NULL, 
  PRIMARY KEY (id) 
);
```

### API Endpoints

1. **List All Clients**
   - `GET /api/clients`
   - Returns a JSON array of all clients

2. **Get Client by Slug**
   - `GET /api/clients/{slug}`
   - Returns a single client by its slug
   - First checks Redis cache, then falls back to database

3. **Create Client**
   - `POST /api/clients`
   - Creates a new client
   - Handles file upload to S3 for client_logo
   - Automatically caches the new client in Redis

4. **Update Client**
   - `PUT /api/clients/{id}`
   - Updates an existing client
   - Handles file upload to S3 for client_logo
   - Updates Redis cache with new data

5. **Delete Client**
   - `DELETE /api/clients/{id}`
   - Soft deletes the client (sets deleted_at timestamp)
   - Removes the client from Redis cache

### Redis Integration

- Each client is stored in Redis with the key format: `client:{slug}`
- The value is a JSON representation of the client data
- When a client is created, it's automatically stored in Redis
- When a client is updated, the Redis cache is updated
- When a client is deleted, it's removed from Redis
- If the slug changes during an update, the old Redis key is deleted

### S3 Integration

- Client logos are stored in S3 in the `client-logos` directory
- The URL to the image is stored in the `client_logo` field
- When updating a logo, the old file is deleted from S3 (if it's not the default)

## Setup Instructions

1. Configure your database in `.env`:
   ```
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

2. Configure Redis in `.env`:
   ```
   REDIS_CLIENT=phpredis
   REDIS_HOST=127.0.0.1
   REDIS_PASSWORD=null
   REDIS_PORT=6379
   ```

3. Configure AWS S3 in `.env`:
   ```
   AWS_ACCESS_KEY_ID=your_access_key
   AWS_SECRET_ACCESS_KEY=your_secret_key
   AWS_DEFAULT_REGION=your_region
   AWS_BUCKET=your_bucket
   ```

4. Run migrations:
   ```
   php artisan migrate
   ```

5. Start the server:
   ```
   php artisan serve
   ```

## Technical Implementation

The implementation consists of:

1. **Migration** - Creates the database table structure
2. **Model** - Defines the MyClient model with Redis integration
3. **Controller** - Implements CRUD operations with S3 integration
4. **Routes** - Defines API endpoints
5. **Service Provider** - Configures Redis for JSON persistence
