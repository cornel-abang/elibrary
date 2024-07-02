document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('book-list')) {
        fetchBooks();
    } else if (document.getElementById('book-title')) {
        const bookId = getQueryParam('id');
        fetchBookDetails(bookId);
    } else if (document.getElementById('author-name')) {
        const authorId = getQueryParam('id');
        fetchAuthorDetails(authorId);
    }
});

function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

function fetchBooks() {
    fetch('/api/books')
        .then(response => response.json())
        .then(books => {
            const bookList = document.getElementById('book-list');
            books.forEach(book => {
                const li = document.createElement('li');
                const link = document.createElement('a');
                link.href = `book-details.html?id=${book.id}`;
                link.textContent = `${book.title} by ${book.author.name}`;
                li.appendChild(link);
                bookList.appendChild(li);
            });
        })
        .catch(error => console.error('Error fetching books:', error));
}

function fetchBookDetails(bookId) {
    fetch(`/api/books/${bookId}`)
        .then(response => response.json())
        .then(book => {
            document.getElementById('book-title').textContent = book.title;
            document.getElementById('book-author').textContent = `Author: ${book.author.name}`;
            document.getElementById('book-published').textContent = `Published: ${book.created_at}`;
        })
        .catch(error => console.error('Error fetching book details:', error));
}

function fetchAuthorDetails(authorId) {
    fetch(`/api/authors/${authorId}`)
        .then(response => response.json())
        .then(author => {
            document.getElementById('author-name').textContent = author.name;
            const authorBooks = document.getElementById('author-books');
            author.books.forEach(book => {
                const li = document.createElement('li');
                const link = document.createElement('a');
                link.href = `book-details.html?id=${book.id}`;
                link.textContent = book.title;
                li.appendChild(link);
                authorBooks.appendChild(li);
            });
        })
        .catch(error => console.error('Error fetching author details:', error));
}