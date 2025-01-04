@extends('layouts.template')

@section('content')
<style>
    html,
    body {
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100%;
        background: #111;
        font-family: sans-serif;
        color: #ccc;
    }

    .content-wrapper {
        display: flex;
        width: 100%;
        min-height: 100vh;
        box-sizing: border-box;
        background: #111;
    }

    .main-content {
        flex: 1;
        padding: 2rem;
        box-sizing: border-box;
        background: #111;
    }

    .upload-form-container {
        max-width: 500px;
        margin: 0 auto;
        background: #222;
        padding: 2rem;
        border-radius: 8px;
        border: 1px solid #333;
    }

    .upload-form-container h2 {
        margin-top: 0;
        margin-bottom: 1.5rem;
        color: #ccc;
        font-size: 1.3rem;
        text-align: center;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        display: block;
        color: #ccc;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-group input[type="text"],
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 0.5rem;
        background: #333;
        color: #ccc;
        border: 1px solid #444;
        border-radius: 4px;
        font-size: 0.9rem;
    }

    .form-group input[type="text"]:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: #555;
    }

    .form-group textarea {
        min-height: 100px;
        resize: vertical;
    }

    .submit-btn {
        display: block;
        width: 100%;
        background: #444;
        color: #ccc;
        border: none;
        padding: 0.75rem;
        font-size: 1rem;
        border-radius: 4px;
        cursor: pointer;
        text-align: center;
        margin-top: 1.5rem;
    }

    .submit-btn:hover {
        background: #555;
    }

    /* Flash Messages */
    .alert {
        padding: 1rem;
        border-radius: 4px;
        margin-top: 1rem;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
    }

    .alert-success {
        background-color: #28a745;
        color: #fff;
    }

    .alert-danger {
        background-color: #dc3545;
        color: #fff;
    }

    /* Topic Suggestions */
    .topic-suggestions {
        background: #333;
        border: 1px solid #444;
        border-radius: 4px;
        margin-top: 0.5rem;
        padding: 0.5rem;
        position: absolute;
        width: calc(100% - 1rem);
        box-sizing: border-box;
        z-index: 999;
        max-height: 200px;
        overflow-y: auto;
        display: none;
    }

    .topic-suggestions .suggestion {
        padding: 0.5rem;
        cursor: pointer;
        font-size: 0.9rem;
        color: #ccc;
    }

    .topic-suggestions .suggestion:hover {
        background: #444;
    }

    .no-results {
        font-size: 0.9rem;
        color: #ccc;
        padding: 0.5rem;
    }

    .no-results:hover {
        background: #444;
        cursor: pointer;
    }
    .content-wrapper{
    opacity: 0;
    transition: opacity 1s ease-in;
}
.content-wrapper.loaded {
      opacity: 1;
    }
</style>

<div class="content-wrapper">
    <div class="main-content">
        <div class="upload-form-container">
            <h2>Upload New Post</h2>
            <form action="{{ route('posts.store') }}" method="POST" id="postForm">
                @csrf

                <!-- Caption -->
                <div class="form-group">
                    <label for="description">Caption</label>
                    <textarea name="description" id="description" placeholder="Write something..." required>{{ old('description') }}</textarea>
                </div>

                <!-- Topic -->
                <div class="form-group" style="position: relative;">
                    <label for="topicInput">Topic</label>
                    <input type="text" name="topic" id="topicInput" placeholder="Type topic..." autocomplete="off" value="{{ old('topic') }}">

                    <!-- Suggestion box -->
                    <div id="topicSuggestions" class="topic-suggestions"></div>
                </div>

                <!-- Status -->
                <div class="form-group">
    <label for="status">Post Status</label>
    <select name="status" id="status" required>
        <option value="public" {{ old('status', 'public') == 'public' ? 'selected' : '' }}>Public</option>
        <option value="private" {{ old('status') == 'private' ? 'selected' : '' }}>Private</option>
    </select>
</div>


                <!-- Image Links -->
                <div class="form-group">
                    <label>Input an Image!</label>
                    <div id="imageLinksContainer">
                        <div class="image-url-group">
                            <input type="text" name="image_links[]" placeholder="Enter image URL..." required>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-btn">Post</button>
            </form>

            <!-- Flash Messages -->
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
</div>
<script>
     window.addEventListener('load', function() {
      document.querySelector('.content-wrapper').classList.add('loaded');
    });
</script>

<script>
    // Topic Suggestions
    let topicTimeout = null;
    const topicInput = document.getElementById('topicInput');
    const topicSuggestions = document.getElementById('topicSuggestions');

    topicInput.addEventListener('input', () => {
        const query = topicInput.value.trim();

        if (topicTimeout) {
            clearTimeout(topicTimeout);
        }

        if (query.length === 0) {
            topicSuggestions.style.display = 'none';
            return;
        }

        topicTimeout = setTimeout(() => {
            fetch(`/topics/search?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    while (topicSuggestions.firstChild) {
                        topicSuggestions.removeChild(topicSuggestions.firstChild);
                    }

                    if (data.length > 0) {
                        data.forEach(topic => {
                            const div = document.createElement('div');
                            div.classList.add('suggestion');
                            div.textContent = topic.name;
                            div.addEventListener('click', () => {
                                topicInput.value = topic.name;
                                topicSuggestions.style.display = 'none';
                            });
                            topicSuggestions.appendChild(div);
                        });
                    } else {
                        const noRes = document.createElement('div');
                        noRes.classList.add('no-results');
                        noRes.textContent = `No results. Add "${query}" as a new topic?`;
                        noRes.addEventListener('click', () => {
                            topicInput.value = query;
                            topicSuggestions.style.display = 'none';
                        });
                        topicSuggestions.appendChild(noRes);
                    }

                    topicSuggestions.style.display = 'block';
                })
                .catch(() => {
                    topicSuggestions.style.display = 'none';
                });
        }, 300);
    });

    document.addEventListener('click', (event) => {
        if (!topicInput.contains(event.target) && !topicSuggestions.contains(event.target)) {
            topicSuggestions.style.display = 'none';
        }
    });
</script>
@endsection
