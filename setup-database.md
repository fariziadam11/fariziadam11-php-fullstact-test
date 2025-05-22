# PostgreSQL Database Setup for MyClient CRUD Application

## Database Configuration

To set up the PostgreSQL database for this application, follow these steps:

1. Update your `.env` file with the following PostgreSQL configuration:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=my_client_db
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

2. Make sure Redis is configured in your `.env` file:

```
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

3. Configure AWS S3 for file uploads in your `.env` file:

```
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=your_region
AWS_BUCKET=your_bucket
AWS_URL=https://your-bucket.s3.amazonaws.com
```

## Run Migrations

After configuring your environment variables, run the migrations to create the database tables:

```bash
php artisan migrate
```

## Direct SQL Creation (Alternative)

If you prefer to create the table directly using SQL, you can run the following SQL command in your PostgreSQL database:

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

## Testing the API

After setting up the database, you can test the API endpoints:

1. List all clients:
   ```
   GET /api/clients
   ```

2. Get a specific client by slug:
   ```
   GET /api/clients/{slug}
   ```

3. Create a new client:
   ```
   POST /api/clients
   ```
   With form data or JSON body containing the client information.

4. Update a client:
   ```
   PUT /api/clients/{id}
   ```
   With form data or JSON body containing the updated client information.

5. Delete a client:
   ```
   DELETE /api/clients/{id}
   ```
