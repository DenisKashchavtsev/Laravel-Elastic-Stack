<!-- resources/views/elasticsearch.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elasticsearch Interface</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="container mt-5">
<h2>Elasticsearch Interface</h2>
<div class="mt-4">
    <!-- Form for Indexing a Document -->
    <h4>Index Document</h4>
    <form id="indexForm">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" id="content" rows="3" required></textarea>
        </div>
        <button type="button" class="btn btn-primary" onclick="indexDocument()">Index Document</button>
    </form>
    <hr>

    <!-- Form for Searching Documents -->
    <h4>Search Documents</h4>
    <form id="searchForm">
        <div class="mb-3">
            <label for="query" class="form-label">Search Query</label>
            <input type="text" class="form-control" id="query" required>
        </div>

        <div class="mb-3">
            <label for="type">Выберите тип запроса:</label>
            <select class="form-control" id="type" name="type">
                <option value="match">Match</option>
                <option value="match_phrase">Match Phrase</option>
                <option value="term">Term</option>
                <option value="multi_match">Multi Match</option>
                <option value="match_all">Match All</option>
                <option value="wildcard">Wildcard</option>
                <option value="prefix">Prefix</option>
                <option value="fuzzy">Fuzzy</option>
                <option value="range">Range</option>
                <option value="bool">Bool</option>
                <option value="exists">Exists</option>
            </select>
        </div>
        <button type="button" class="btn btn-primary" onclick="searchDocuments()">Search</button>
    </form>
    <div id="searchResults" class="mt-4"></div>
</div>

<script>
    function indexDocument() {
        const title = document.getElementById('title').value;
        const content = document.getElementById('content').value;

        axios.post('/index', { title, content })
            .then(response => {
                alert('Document indexed successfully');
                document.getElementById('indexForm').reset();
            })
            .catch(error => {
                console.error(error);
                alert('Failed to index document');
            });
    }

    function searchDocuments() {
        const query = document.getElementById('query').value;
        const type = document.getElementById('type').value;

        axios.get('/search', { params: { query, type } })
            .then(response => {
                const results = response.data.hits.hits;
                let html = `<h5>Search Results:</h5><ul class="list-group">`;
                results.forEach(result => {
                    html += `<li class="list-group-item">
                            <strong>${result._source.title}</strong><br>
                            ${result._source.content}
                        </li>`;
                });
                html += `</ul>`;
                document.getElementById('searchResults').innerHTML = html;
            })
            .catch(error => {
                console.error(error);
                alert('Failed to search documents');
            });
    }
</script>
</body>
</html>
