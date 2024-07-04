# API Documentation

## Authentication Endpoints

### Register
- **URL:** `/api/auth/register`
- **Method:** `POST`
- **Description:** Registers a new user.
- **Request Parameters:**
  - `name` (string, required): The name of the user.
  - `email` (string, required): The email address of the user.
  - `password` (string, required): The password for the user.
  - `password_confirmation` (string, required): The password confirmation.

### Login
- **URL:** `/api/auth/login`
- **Method:** `POST`
- **Description:** Logs in a user.
- **Request Parameters:**
  - `email` (string, required): The email address of the user.
  - `password` (string, required): The password for the user.

### Logout
- **URL:** `/api/auth/logout`
- **Method:** `POST`
- **Description:** Logs out the currently authenticated user.
- **Headers:**
  - `Authorization` (string, required): Bearer token.


## Book Endpoints

### Get All Books
- **URL:** `/api/books`
- **Method:** `GET`
- **Description:** Retrieves a list of all books.
- **Headers:**
  - `Authorization` (string, required): Bearer token.

### Get a Single Book
- **URL:** `/api/books/{id}`
- **Method:** `GET`
- **Description:** Retrieves a single book by ID.
- **Headers:**
  - `Authorization` (string, required): Bearer token.

### Create a Book
- **URL:** `/api/books`
- **Method:** `POST`
- **Description:** Creates a new book.
- **Headers:**
  - `Authorization` (string, required): Bearer token.
- **Request Parameters:**
  - `title` (string, required): The title of the book.
  - `author_id` (integer, required): The ID of the author.
  - `description` (string, required): The description of the book.

### Update a Book
- **URL:** `/api/books/{id}`
- **Method:** `PUT`
- **Description:** Updates an existing book.
- **Headers:**
  - `Authorization` (string, required): Bearer token.
- **Request Parameters:**
  - `title` (string, required): The title of the book.
  - `author_id` (integer, required): The ID of the author.
  - `description` (string, reuired): The description of the book.

### Delete a Book
- **URL:** `/api/books/{id}`
- **Method:** `DELETE`
- **Description:** Deletes a book with the given ID.
- **Headers:**
  - `Authorization` (string, required): Bearer token.


## Author Endpoints

### Get All Authors
- **URL:** `/api/authors`
- **Method:** `GET`
- **Description:** Retrieves a list of all authors.
- **Headers:**
  - `Authorization` (string, required): Bearer token.

### Get a Single Author
- **URL:** `/api/authors/{id}`
- **Method:** `GET`
- **Description:** Retrieves a single author by ID.
- **Headers:**
  - `Authorization` (string, required): Bearer token.

### Create an Author
- **URL:** `/api/authors`
- **Method:** `POST`
- **Description:** Creates a new author.
- **Headers:**
  - `Authorization` (string, required): Bearer token.
- **Request Parameters:**
  - `name` (string, required): The name of the author.
  - `bio` (string, required): The biography of the author.

### Update an Author
- **URL:** `/api/authors/{id}`
- **Method:** `PUT`
- **Description:** Updates an existing author.
- **Headers:**
  - `Authorization` (string, required): Bearer token.
- **Request Parameters:**
  - `name` (string, required): The name of the author.
  - `bio` (string, required): The biography of the author.

### Delete an Author
- **URL:** `/api/authors/{id}`
- **Method:** `DELETE`
- **Description:** Deletes an author with the given ID.
- **Headers:**
  - `Authorization` (string, required): Bearer token.


## Search Endpoint

### Search Books
- **URL:** `/api/search`
- **Method:** `GET`
- **Description:** Searches for books based on a query string.
- **Headers:**
  - `Authorization` (string, required): Bearer token.
- **Request Parameters:**
  - `q` (string, required): The search query string.