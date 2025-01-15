<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 5rem;
        }
        .container {
            max-width: 95vw;
        }
    </style>
</head>
<body>

<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('logout') ?>">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="jumbotron mt-4">
        <h1 class="display-4">Welcome, <?= $session->get('username') ?>!</h1>
        <p class="lead">This is your dashboard. You can manage your bookmarks here.</p>
        
        <div class="card mt-4">
            <div class="card-header">
                Bookmark Management
            </div>
            <div class="card-body">
                
                <form id="addBookmarkForm">
                    <div class="form-group">
                        <label for="bookmarkTitle">Title:</label>
                        <input type="text" class="form-control" id="bookmarkTitle" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="bookmarkURL">URL:</label>
                        <input type="url" class="form-control" id="bookmarkURL" name="url" required>
                    </div>
                    <div class="form-group">
                        <label for="bookmarkTags">Tags (comma-separated):</label>
                        <input type="text" class="form-control" id="bookmarkTags" name="tags">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Bookmark</button>
                </form>
                
                <hr>
                
                <h5>My Bookmarks</h5>
                <div class="form-group">
                    <label for="searchTags">Search by Tags:</label>
                    <input type="text" class="form-control" id="searchTags" name="searchTags">
                </div>
                <table class="table table-striped" id="bookmarkTable">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>URL</th>
                            <th>Tags</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center" id="pagination">
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <footer class="text-muted text-center py-4">
        &copy; 2024
    </footer>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        var currentPage = 1;
        var perPage = 10;

        function fetchBookmarks(page, tags = '') {
            $.ajax({
                url: '<?= site_url('bookmark') ?>',
                method: 'GET',
                data: {
                    page: page,
                    perPage: perPage,
                    tags: tags
                },
                success: function(response) {
                    console.log(response);
                    updateBookmarkTable(response.bookmarks);
                    updatePagination(parseInt(response.currentPage), Math.ceil(response.total/10));
                },
                error: function(xhr, status, error) {
                    console.error('Failed to fetch bookmarks:', error);
                }
            });
        }
        $('#searchTags').on('change', function() {
            var tags = $(this).val().trim();
            fetchBookmarks(currentPage, tags);
        });

        function updateBookmarkTable(bookmarks) {
            var tableBody = $('#bookmarkTable tbody');
            tableBody.empty();
            bookmarks.forEach(function(bookmark) {
                tableBody.append(`
                    <tr>
                        <td>${bookmark.title}</td>
                        <td><a href="${bookmark.url}" target="_blank">${bookmark.url}</a></td>
                        <td>${bookmark.tags}</td>
                        <td>
                            <button class="btn btn-warning editBookmark" data-id="${bookmark.id}">Edit</button>
                            <button class="btn btn-danger deleteBookmark" data-id="${bookmark.id}">Delete</button>
                        </td>
                    </tr>
                `);
            });
        }

        function updatePagination(currentPage, totalPages) {
            var pagination = $('#pagination');
            pagination.empty();
            console.log(currentPage, totalPages);
            pagination.append(`
                <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage - 1}">&laquo; Previous</a>
                </li>
            `);

            for (var i = 1; i <= totalPages; i++) {
                pagination.append(`
                    <li class="page-item ${currentPage === i ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>
                `);
            }

            pagination.append(`
                <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage + 1}">Next &raquo;</a>
                </li>
            `);
        }

        $('#pagination').on('click', 'a', function(e) {
            e.preventDefault();
            var page = parseInt($(this).data('page'));
            fetchBookmarks(page);
        });

        $('#addBookmarkForm').on('submit', function(event) {
            event.preventDefault();
            var formData = {
                title: $('#bookmarkTitle').val(),
                url: $('#bookmarkURL').val(),
                tags: $('#bookmarkTags').val()
            };

            $.ajax({
                url: '<?= site_url('bookmark/add') ?>',
                method: 'POST',
                data: formData,
                success: function() {
                    fetchBookmarks(currentPage);
                    $('#addBookmarkForm')[0].reset();
                },
                error: function() {
                    alert('Failed to add bookmark. Please check your input.');
                }
            });
        });

        $(document).on('click', '.deleteBookmark', function() {
            var id = $(this).data('id');
            if (confirm('Are you sure you want to delete this bookmark?')) {
                $.ajax({
                    url: '<?= site_url('bookmark/delete') ?>/' + id,
                    method: 'DELETE',
                    success: function(response) {
                        alert(response.message);
                        $('#bookmark-' + id).remove(); 
                    },
                    error: function(xhr, status, error) {
                        alert(error);
                    }
                });
            }
        });




        $(document).on('click', '.editBookmark', function() {
            var id = $(this).data('id');
            var row = $(this).closest('tr');
            var currentTitle = row.find('td:eq(0)').text();
            var currentUrl = row.find('td:eq(1) a').attr('href');
            var currentTags = row.find('td:eq(2)').text();

            var newTitle = prompt('Enter new title:', currentTitle);
            var newUrl = prompt('Enter new URL:', currentUrl);
            var newTags = prompt('Enter new tags (comma-separated):', currentTags);

            if (newTitle && newUrl && newTags) {
                $.ajax({
                    url: '<?= site_url('bookmark/edit') ?>/' + id,
                    method: 'POST',
                    data: {
                        title: newTitle,
                        url: newUrl,
                        tags: newTags
                    },
                    success: function() {
                        fetchBookmarks(currentPage);
                    },
                    error: function() {
                        alert('Failed to update bookmark.');
                    }
                });
            }
        });

        fetchBookmarks(currentPage);
    });
</script>

</body>
</html>
