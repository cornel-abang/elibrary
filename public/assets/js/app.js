document.addEventListener('DOMContentLoaded', function() {
    const content = document.getElementById('content');

    function renderHomePage() {
        const token = localStorage.getItem('token');
        if (!token) {
            renderLoginPage();
            return;
        }

        fetch('/api/books', {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => {
            if (response.status === 401) {
                renderLoginPage();
                return;
            }
            return response.json();
        })
        .then(data => {
            let html = '<h1>Books</h1><ul>';
            data.forEach(book => {
                html += `<li><a href="#" class="book-link" data-id="${book.id}">${book.title}</a> by <a href="#" class="author-link" data-id="${book.author.id}">${book.author.name}</a></li>`;
            });
            html += '</ul>';
            html += `
                <button id="add-book-button">Add Book</button>
                <button id="add-author-button">Add Author</button>
            `;
            content.innerHTML = html;

            document.querySelectorAll('.book-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    renderBookDetailsPage(this.dataset.id);
                });
            });

            document.querySelectorAll('.author-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    renderAuthorDetailsPage(this.dataset.id);
                });
            });

            document.getElementById('add-book-button').addEventListener('click', renderAddBookForm);
            document.getElementById('add-author-button').addEventListener('click', renderAddAuthorForm);
        })
        .catch(error => console.error('Error fetching books:', error));
    }

    function renderBookDetailsPage(bookId) {
        const token = localStorage.getItem('token');
        fetch(`/api/books/${bookId}`, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => response.json())
        .then(book => {
            const html = `
                <h1>${book.title}</h1>
                <p>Author: <a href="#" class="author-link" data-id="${book.author.id}">${book.author.name}</a></p>
                <p>${book.description}</p>
                <button id="edit-book-button" data-id="${book.id}">Edit Book</button>
                <button id="delete-book-button" data-id="${book.id}">Delete Book</button>
            `;
            content.innerHTML = html;

            document.querySelector('.author-link').addEventListener('click', function(e) {
                e.preventDefault();
                renderAuthorDetailsPage(this.dataset.id);
            });

            document.getElementById('edit-book-button').addEventListener('click', function() {
                renderEditBookForm(this.dataset.id);
            });

            document.getElementById('delete-book-button').addEventListener('click', function() {
                deleteBook(this.dataset.id);
            });
        })
        .catch(error => console.error('Error fetching book details:', error));
    }

    function renderAuthorDetailsPage(authorId) {
        const token = localStorage.getItem('token');
        fetch(`/api/authors/${authorId}`, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => response.json())
        .then(author => {
            const html = `
                <h1>${author.name}</h1>
                <p>${author.bio}</p>
                <button id="edit-author-button" data-id="${author.id}">Edit Author</button>
                <button id="delete-author-button" data-id="${author.id}">Delete Author</button>
            `;
            content.innerHTML = html;

            document.getElementById('edit-author-button').addEventListener('click', function() {
                renderEditAuthorForm(this.dataset.id);
            });

            document.getElementById('delete-author-button').addEventListener('click', function() {
                deleteAuthor(this.dataset.id);
            });
        })
        .catch(error => console.error('Error fetching author details:', error));
    }

    function renderAddBookForm() {
        const html = `
            <h1>Add Book</h1>
            <form id="book-form">
                <div id="book-errors" style="color: red;"></div>
                <label for="title">Title:</label>
                <input type="text" id="title" required>
                <label for="author_id">Author ID:</label>
                <input type="number" id="author_id" required>
                <label for="description">Description:</label>
                <textarea id="description" required></textarea>
                <button type="submit">Add Book</button>
            </form>
        `;
        content.innerHTML = html;

        document.getElementById('book-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const title = document.getElementById('title').value;
            const author_id = document.getElementById('author_id').value;
            const description = document.getElementById('description').value;

            fetch('/api/books', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify({ title, author_id, description })
            })
            .then(response => response.json())
            .then(data => {
                if (data.errors) {
                    let errorsHtml = '';
                    Object.keys(data.errors).forEach(key => {
                        errorsHtml += `<p>${data.errors[key].join(', ')}</p>`;
                    });
                    document.getElementById('book-errors').innerHTML = errorsHtml;
                } else {
                    renderHomePage();
                }
            });
        });
    }

    function renderAddAuthorForm() {
        const html = `
            <h1>Add Author</h1>
            <form id="author-form">
                <div id="author-errors" style="color: red;"></div>
                <label for="name">Name:</label>
                <input type="text" id="name" required>
                <label for="bio">Bio:</label>
                <textarea id="bio" required></textarea>
                <button type="submit">Add Author</button>
            </form>
        `;
        content.innerHTML = html;

        document.getElementById('author-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const name = document.getElementById('name').value;
            const bio = document.getElementById('bio').value;

            fetch('/api/authors', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: JSON.stringify({ name, bio })
            })
            .then(response => response.json())
            .then(data => {
                if (data.errors) {
                    let errorsHtml = '';
                    Object.keys(data.errors).forEach(key => {
                        errorsHtml += `<p>${data.errors[key].join(', ')}</p>`;
                    });
                    document.getElementById('author-errors').innerHTML = errorsHtml;
                } else {
                    renderHomePage();
                }
            });
        });
    }

    function renderEditBookForm(bookId) {
        const token = localStorage.getItem('token');
        fetch(`/api/books/${bookId}`, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => response.json())
        .then(book => {
            const html = `
                <h1>Edit Book</h1>
                <form id="edit-book-form">
                    <div id="edit-book-errors" style="color: red;"></div>
                    <label for="title">Title:</label>
                    <input type="text" id="title" value="${book.title}" required>
                    <label for="author_id">Author ID:</label>
                    <input type="number" id="author_id" value="${book.author_id}" required>
                    <label for="description">Description:</label>
                    <textarea id="description" required>${book.description}</textarea>
                    <button type="submit">Update Book</button>
                </form>
            `;
            content.innerHTML = html;

            document.getElementById('edit-book-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const title = document.getElementById('title').value;
                const author_id = document.getElementById('author_id').value;
                const description = document.getElementById('description').value;

                fetch(`/api/books/${bookId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({ title, author_id, description })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.errors) {
                        let errorsHtml = '';
                        Object.keys(data.errors).forEach(key => {
                            errorsHtml += `<p>${data.errors[key].join(', ')}</p>`;
                        });
                        document.getElementById('edit-book-errors').innerHTML = errorsHtml;
                    } else {
                        renderHomePage();
                    }
                });
            });
        })
        .catch(error => console.error('Error fetching book:', error));
    }

    function renderEditAuthorForm(authorId) {
        const token = localStorage.getItem('token');
        fetch(`/api/authors/${authorId}`, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => response.json())
        .then(author => {
            const html = `
                <h1>Edit Author</h1>
                <form id="edit-author-form">
                    <div id="edit-author-errors" style="color: red;"></div>
                    <label for="name">Name:</label>
                    <input type="text" id="name" value="${author.name}" required>
                    <label for="bio">Bio:</label>
                    <textarea id="bio" required>${author.bio}</textarea>
                    <button type="submit">Update Author</button>
                </form>
            `;
            content.innerHTML = html;

            document.getElementById('edit-author-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const name = document.getElementById('name').value;
                const bio = document.getElementById('bio').value;

                fetch(`/api/authors/${authorId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({ name, bio })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.errors) {
                        let errorsHtml = '';
                        Object.keys(data.errors).forEach(key => {
                            errorsHtml += `<p>${data.errors[key].join(', ')}</p>`;
                        });
                        document.getElementById('edit-author-errors').innerHTML = errorsHtml;
                    } else {
                        renderHomePage();
                    }
                });
            });
        })
        .catch(error => console.error('Error fetching author:', error));
    }

    function deleteBook(bookId) {
        const token = localStorage.getItem('token');
        fetch(`/api/books/${bookId}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
        .then(() => renderHomePage())
        .catch(error => console.error('Error deleting book:', error));
    }

    function deleteAuthor(authorId) {
        const token = localStorage.getItem('token');
        fetch(`/api/authors/${authorId}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
        .then(() => renderHomePage())
        .catch(error => console.error('Error deleting author:', error));
    }

    function renderLoginPage() {
        const html = `
            <h1>Login</h1>
            <form id="login-form">
                <div id="login-errors" style="color: red;"></div>
                <label for="email">Email:</label>
                <input type="email" id="email" required>
                <label for="password">Password:</label>
                <input type="password" id="password" required>
                <button type="submit">Login</button>
            </form>
        `;
        content.innerHTML = html;

        document.getElementById('login-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            fetch('/api/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email, password }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.access_token) {
                    localStorage.setItem('token', data.access_token);
                    renderHomePage();
                } else {
                    document.getElementById('login-errors').innerText = data.error;
                }
            });
        });
    }

    function renderRegisterPage() {
        const html = `
            <h1>Register</h1>
            <form id="register-form">
                <div id="register-errors" style="color: red;"></div>
                <label for="name">Name:</label>
                <input type="text" id="name" required>
                <label for="email">Email:</label>
                <input type="email" id="email" required>
                <label for="password">Password:</label>
                <input type="password" id="password" required>
                <label for="password_confirmation">Confirm Password:</label>
                <input type="password" id="password_confirmation" required>
                <button type="submit">Register</button>
            </form>
        `;
        content.innerHTML = html;

        document.getElementById('register-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const password_confirmation = document.getElementById('password_confirmation').value;
            fetch('/api/auth/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ name, email, password, password_confirmation }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.access_token) {
                    localStorage.setItem('token', data.access_token);
                    renderHomePage();
                } else {
                    let errorsHtml = '';
                    Object.keys(data.errors).forEach(key => {
                        errorsHtml += `<p>${data.errors[key].join(', ')}</p>`;
                    });
                    document.getElementById('register-errors').innerHTML = errorsHtml;
                }
            });
        });
    }

    // Event listeners for navigation links
    document.getElementById('home-link').addEventListener('click', renderHomePage);
    document.getElementById('login-link').addEventListener('click', renderLoginPage);
    document.getElementById('register-link').addEventListener('click', renderRegisterPage);

    // Initially render the home page or login page based on token existence
    if (localStorage.getItem('token')) {
        renderHomePage();
    } else {
        renderLoginPage();
    }
});