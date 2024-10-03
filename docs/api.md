
# API Documentation

## Authors

### 1. Retrieve a list of all authors
- **URL**: `/authors`
- **Method**: `GET`
- **Description**: Retrieve a list of all authors.
- **Response**:
    - **200 OK**
    ```json
    [
        {
            "id": 1,
            "name": "Author Name",
            "bio": "Author bio",
            "birth_date": "YYYY-MM-DD"
        },
        ...
    ]
    ```

### 2. Retrieve details of a specific author
- **URL**: `/authors/{id}`
- **Method**: `GET`
- **Description**: Retrieve details of a specific author by ID.
- **Response**:
    - **200 OK**
    ```json
    {
        "id": 1,
        "name": "Author Name",
        "bio": "Author bio",
        "birth_date": "YYYY-MM-DD"
    }
    ```
    - **404 Not Found**: Author not found.

### 3. Create a new author
- **URL**: `/authors`
- **Method**: `POST`
- **Description**: Create a new author.
- **Request Body**:
    ```json
    {
        "name": "Author Name",
        "bio": "Author bio",
        "birth_date": "YYYY-MM-DD"
    }
    ```
- **Response**:
    - **201 Created**
    ```json
    {
        "id": 1,
        "name": "Author Name",
        "bio": "Author bio",
        "birth_date": "YYYY-MM-DD"
    }
    ```

### 4. Update an existing author
- **URL**: `/authors/{id}`
- **Method**: `PUT`
- **Description**: Update an existing author.
- **Request Body** (optional fields):
    ```json
    {
        "name": "Updated Author Name",
        "bio": "Updated author bio",
        "birth_date": "YYYY-MM-DD"
    }
    ```
- **Response**:
    - **200 OK**
    ```json
    {
        "id": 1,
        "name": "Updated Author Name",
        "bio": "Updated author bio",
        "birth_date": "YYYY-MM-DD"
    }
    ```
    - **404 Not Found**: Author not found.

### 5. Delete an author
- **URL**: `/authors/{id}`
- **Method**: `DELETE`
- **Description**: Delete an author.
- **Response**:
    - **204 No Content**

### 6. Get all books by a specific author
- **URL**: `/authors/{id}/books`
- **Method**: `GET`
- **Description**: Retrieve all books by a specific author.
- **Response**:
    - **200 OK**
    ```json
    {
        "books": [
            {
                "id": 1,
                "title": "Book Title",
                "description": "Book Description",
                "publish_date": "YYYY-MM-DD",
                "author_id": 1
            },
            ...
        ]
    }
    ```
    - **404 Not Found**: Author not found.

---

## Books

### 1. Retrieve a list of all books
- **URL**: `/books`
- **Method**: `GET`
- **Description**: Retrieve a list of all books.
- **Response**:
    - **200 OK**
    ```json
    [
        {
            "id": 1,
            "title": "Book Title",
            "description": "Book Description",
            "publish_date": "YYYY-MM-DD",
            "author_id": 1
        },
        ...
    ]
    ```

### 2. Retrieve details of a specific book
- **URL**: `/books/{id}`
- **Method**: `GET`
- **Description**: Retrieve details of a specific book by ID.
- **Response**:
    - **200 OK**
    ```json
    {
        "id": 1,
        "title": "Book Title",
        "description": "Book Description",
        "publish_date": "YYYY-MM-DD",
        "author_id": 1
    }
    ```
    - **404 Not Found**: Book not found.

### 3. Create a new book
- **URL**: `/books`
- **Method**: `POST`
- **Description**: Create a new book.
- **Request Body**:
    ```json
    {
        "title": "Book Title",
        "description": "Book Description",
        "publish_date": "YYYY-MM-DD",
        "author_id": 1
    }
    ```
- **Response**:
    - **201 Created**
    ```json
    {
        "id": 1,
        "title": "Book Title",
        "description": "Book Description",
        "publish_date": "YYYY-MM-DD",
        "author_id": 1
    }
    ```

### 4. Update an existing book
- **URL**: `/books/{id}`
- **Method**: `PUT`
- **Description**: Update an existing book.
- **Request Body** (optional fields):
    ```json
    {
        "title": "Updated Book Title",
        "description": "Updated Book Description",
        "publish_date": "YYYY-MM-DD",
        "author_id": 1
    }
    ```
- **Response**:
    - **200 OK**
    ```json
    {
        "id": 1,
        "title": "Updated Book Title",
        "description": "Updated Book Description",
        "publish_date": "YYYY-MM-DD",
        "author_id": 1
    }
    ```
    - **404 Not Found**: Book not found.

### 5. Delete a book
- **URL**: `/books/{id}`
- **Method**: `DELETE`
- **Description**: Delete a book.
- **Response**:
    - **204 No Content**
