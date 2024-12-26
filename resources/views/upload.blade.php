@extends('layouts.template')

@section('content')
<style>
    /* Existing CSS */
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

    /* Removed Sidebar Styles */

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
    .form-group textarea {
        width: 100%;
        padding: 0.5rem;
        background: #333;
        color: #ccc;
        border: 1px solid #444;
        border-radius: 4px;
        font-size: 0.9rem;
    }

    .form-group input[type="text"]:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #555;
    }

    .form-group textarea {
        min-height: 100px;
        resize: vertical;
    }

    .form-group .file-input {
        background: #000;
        border: 1px dashed #444;
        padding: 1rem;
        text-align: center;
        cursor: pointer;
    }

    .form-group .file-input:hover {
        background: #111;
    }

    .form-group .file-input input[type=file] {
        opacity: 0;
        position: absolute;
        left: -9999px;
    }

    .form-group .file-text {
        color: #888;
        font-size: 0.9rem;
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

    /* Suggestion box for topics */
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
        /* Ensure it's hidden by default */
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

    @media (max-width: 768px) {
        /* Removed Sidebar Media Queries */

        .main-content {
            order: 1;
            padding: 1rem;
        }

        .upload-form-container {
            padding: 1rem;
        }
    }

    .image-url-group {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .image-url-group input[type="text"] {
        flex: 1;
        margin-right: 0.5rem;
    }

    .remove-link-btn {
        background: #ff4d4d;
        border: none;
        color: #fff;
        padding: 0.5rem 0.75rem;
        border-radius: 4px;
        cursor: pointer;
    }

    .remove-link-btn:hover {
        background: #ff1a1a;
    }

    .add-image-btn {
        background: #28a745;
        border: none;
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        cursor: pointer;
    }

    .add-image-btn:hover {
        background: #218838;
    }

    /* Flash Messages */
    .alert {
        padding: 1rem;
        border-radius: 4px;
        margin-top: 1rem;
        width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    .alert-success {
        background-color: #28a745;
        color: #fff;
        margin-right: 2px;
    }

    .alert-danger {
        background-color: #dc3545;
        color: #fff;
    }

    /* Main Feed Styles */
    .feed-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .feed-header h2 {
        margin: 0;
        font-size: 1.2rem;
        color: #ccc;
    }

    .feed-header select {
        background: #333;
        color: #ccc;
        border: none;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        cursor: pointer;
    }

    .divider {
        width: 100%;
        height: 1px;
        background: #333;
        margin: 0.75rem 0 1rem 0;
    }

    /* Grid layout posts: 1 col <768px, 2 col ≥768px, 3 col ≥1024px */
    #postContainer {
        display: grid;
        gap: 2rem;
        grid-template-columns: 1fr;
    }

    @media (min-width: 768px) {
        #postContainer {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 1024px) {
        #postContainer {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    .post-card {
        background: #222;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #333;
        position: relative;
    }

    .post-header {
        display: flex;
        align-items: center;
        padding: 0.5rem;
    }

    /* Avatar dihilangkan, hanya username dan waktu */
    .post-header .username {
        font-weight: bold;
        color: #ccc;
    }

    .post-header .time {
        color: #888;
        font-size: 0.9rem;
        margin-left: 0.5rem;
    }

    .post-header .menu-btn {
        margin-left: auto;
        color: #ccc;
        cursor: pointer;
    }

    /* Slider Container */
    .image-slider {
        position: relative;
        width: 100%;
        overflow: hidden;
    }

    .image-slider img {
        width: 100%;
        display: none;
        object-fit: cover;
    }

    .image-slider img.active {
        display: block;
    }

    /* Prev/Next button */
    .slider-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.3);
        color: #fff;
        padding: 0.5rem;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.2rem;
        user-select: none;
    }

    .slider-btn:hover {
        background: rgba(0, 0, 0, 0.5);
    }

    .slider-prev {
        left: 0.5rem;
    }

    .slider-next {
        right: 0.5rem;
    }

    .post-footer {
        padding: 0.5rem;
        background: #222;
        display: flex;
        flex-direction: column;
    }

    .post-footer .description {
        color: #ccc;
        margin-bottom: 0.5rem;
        word-wrap: break-word;
    }

    .post-footer .actions {
        display: flex;
        align-items: center;
        color: #ccc;
        font-size: 0.9rem;
    }

    .post-footer .actions i {
        margin-right: 0.25rem;
    }

    .post-footer .actions span {
        margin-right: 1rem;
        cursor: pointer;
    }

    .post-footer .actions span.liked i {
        color: red;
    }

    /* See More Posts Container */
    .see-more-posts-container {
        text-align: center;
        margin-top: 20px;
    }

    .see-more-posts-container .see-more-btn {
        margin-top: 1rem;
        cursor: pointer;
        padding: 0.5rem 1rem;
        background: #333;
        border: none;
        border-radius: 4px;
        color: #ccc;
        font-size: 0.9rem;
    }

    .see-more-posts-container .see-more-btn.disabled {
        opacity: 0.5;
        cursor: default;
    }

    .see-more-posts-container .reset-btn {
        background: #333;
        color: #ccc;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        border: 1px solid #444;
        font-size: 0.9rem;
        cursor: pointer;
        margin-left: 0.5rem;
    }

    .see-more-posts-container .reset-btn:hover {
        background: #444;
    }

    /* Modal Overlay */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal {
        background: #222;
        border: 1px solid #333;
        border-radius: 8px;
        width: 90%;
        max-width: 400px;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        max-height: 80vh;
        overflow-y: auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 1.1rem;
        color: #ccc;
    }

    .modal-header .close-btn {
        cursor: pointer;
        color: #ccc;
        font-size: 1.5rem;
    }

    .comment-list {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .comment-item {
        background: #333;
        padding: 0.5rem;
        border-radius: 4px;
    }

    .comment-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
        margin-bottom: 0.25rem;
    }

    .comment-user {
        font-weight: bold;
        color: #ddd;
    }

    .comment-time {
        color: #aaa;
        font-size: 0.75rem;
    }

    .comment-text {
        font-size: 0.9rem;
        color: #ccc;
        line-height: 1.2;
    }

    .comment-form {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .comment-form input {
        flex: 1;
        background: #000;
        border: 1px solid #444;
        color: #ccc;
        border-radius: 4px;
        padding: 0.5rem;
    }

    .comment-form button {
        background: #444;
        border: none;
        color: #ccc;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        cursor: pointer;
    }

    .comment-form button:hover {
        background: #555;
    }
</style>

<div class="content-wrapper">
    <!-- Main Content -->
    <div class="main-content">
        <!-- Upload Form -->
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
                <div class="form-group" style="position:relative;">
                    <label for="topicInput">Topic</label>
                    <input type="text" name="topic" id="topicInput" placeholder="Type topic..." autocomplete="off" value="{{ old('topic') }}">

                    <!-- Suggestion box -->
                    <div id="topicSuggestions" class="topic-suggestions"></div>
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

        <!-- Main Feed -->
        <!-- <div class="main-feed" style="margin-top: 3rem;">
            <div class="feed-header">
                <h2>Posts</h2> -->
                <!-- Updated Select Element with ID and Value Attributes -->
                <!-- <select id="sortPostsSelect">
                    <option value="newest" selected>Newest</option>
                    <option value="popular">Popular</option>
                    <option value="oldest">Oldest</option>
                </select>
            </div>
            <div class="divider"></div>

            <div id="postContainer"></div>
            <div class="see-more-posts-container">
                <span class="see-more-btn" id="seeMorePostsBtn">See More Posts</span>
                <span class="reset-btn" id="resetPostsBtn" style="display:none;">Reset</span>
            </div> -->
        <!-- </div> -->
    </div>
</div>

<!-- Modal Overlay -->
<div class="modal-overlay" id="commentModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Comments</h3>
            <div class="close-btn" id="closeModal">&times;</div>
        </div>
        <div class="comment-list" id="commentList"></div>
        <form class="comment-form" id="commentForm">
            <input type="text" placeholder="Add a comment..." />
            <button type="submit">Post</button>
        </form>
    </div>
</div>

<!-- Updated JavaScript Section -->
<script>
    // Handling dynamic addition/removal of image link fields
    const imageLinksContainer = document.getElementById('imageLinksContainer');
    const addImageLinkBtn = document.getElementById('addImageLinkBtn');

    // Add new image link field
    addImageLinkBtn.addEventListener('click', () => {
        const currentFields = imageLinksContainer.querySelectorAll('.image-url-group').length;

        if (currentFields >= 5) {
            alert('You can add up to 5 image links only.');
            return;
        }

        const div = document.createElement('div');
        div.classList.add('image-url-group');
        div.innerHTML = `
            <input type="text" name="image_links[]" placeholder="Enter image URL..." required>
            <button type="button" class="remove-link-btn" onclick="removeImageLink(this)">Remove</button>
        `;
        imageLinksContainer.appendChild(div);
    });

    // Remove specific image link field
    function removeImageLink(button) {
        const currentFields = imageLinksContainer.querySelectorAll('.image-url-group').length;

        if (currentFields <= 1) {
            alert('You must have at least one image link.');
            return;
        }

        button.parentElement.remove();
    }

    // Topic Suggestions via AJAX
    let topicTimeout = null; // To debounce AJAX requests
    const topicInput = document.getElementById('topicInput');
    const topicSuggestions = document.getElementById('topicSuggestions');

    topicInput.addEventListener('input', () => {
        const query = topicInput.value.trim();

        // Clear any existing timeout to debounce
        if (topicTimeout) {
            clearTimeout(topicTimeout);
        }

        if (query.length === 0) {
            topicSuggestions.style.display = 'none';
            return;
        }

        // Debounce AJAX request by 300ms
        topicTimeout = setTimeout(() => {
            fetch(`/topics/search?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    // Clear previous suggestions
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
                        // No results, show option to add new topic
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
                .catch(error => {
                    console.error('Error fetching topics:', error);
                    topicSuggestions.style.display = 'none';
                });
        }, 300); // 300ms debounce
    });

    // Close suggestion box when clicking outside
    document.addEventListener('click', (event) => {
        if (!topicInput.contains(event.target) && !topicSuggestions.contains(event.target)) {
            topicSuggestions.style.display = 'none';
        }
    });

    // ===========================
    // Main Feed Functionality
    // ===========================

    const postContainer = document.getElementById('postContainer');
    const seeMorePostsBtn = document.getElementById('seeMorePostsBtn');
    const resetPostsBtn = document.getElementById('resetPostsBtn');
    const sortSelect = document.getElementById('sortPostsSelect');

    // Pagination and Sorting Variables
    let currentPage = 1;
    let currentSort = 'newest';
    let totalPages = 1;

    // Modal Elements
    const commentModal = document.getElementById('commentModal');
    const closeModal = document.getElementById('closeModal');
    const commentList = document.getElementById('commentList');
    const commentForm = document.getElementById('commentForm');
    let currentPostId = null;

    // Initial Load
    fetchPosts(currentSort, currentPage);

    // Event Listeners
    sortSelect.addEventListener('change', () => {
        currentSort = sortSelect.value;
        currentPage = 1;
        postContainer.innerHTML = '';
        fetchPosts(currentSort, currentPage);
        resetPostsBtn.style.display = 'none';
    });

    seeMorePostsBtn.addEventListener('click', () => {
        if (currentPage < totalPages) {
            currentPage++;
            fetchPosts(currentSort, currentPage, true);
        }
    });

    resetPostsBtn.addEventListener('click', () => {
        currentSort = 'newest';
        sortSelect.value = 'newest';
        currentPage = 1;
        postContainer.innerHTML = '';
        fetchPosts(currentSort, currentPage);
        resetPostsBtn.style.display = 'none';
    });

    closeModal.addEventListener('click', () => {
        commentModal.classList.remove('show');
    });

    commentModal.addEventListener('click', (e) => {
        if (e.target === commentModal) {
            commentModal.classList.remove('show');
        }
    });

    commentForm.addEventListener('submit', addComment);

    // Function to Fetch Posts from the Server
    function fetchPosts(sort, page, append = false) {
        fetch(`/posts?sort=${sort}&page=${page}`)
            .then(response => response.json())
            .then(data => {
                totalPages = data.last_page;

                data.data.forEach(post => {
                    const postElement = createPostElement(post);
                    postContainer.appendChild(postElement);
                });

                // Update "See More" and "Reset" Buttons
                if (currentPage >= totalPages) {
                    seeMorePostsBtn.textContent = "No more";
                    seeMorePostsBtn.classList.add('disabled');
                    seeMorePostsBtn.style.cursor = 'default';
                    resetPostsBtn.style.display = 'inline-block';
                } else {
                    seeMorePostsBtn.textContent = "See More Posts";
                    seeMorePostsBtn.classList.remove('disabled');
                    seeMorePostsBtn.style.cursor = 'pointer';
                }
            })
            .catch(error => console.error('Error fetching posts:', error));
    }

    // Function to Create a Post Element
    function createPostElement(post) {
        const card = document.createElement('div');
        card.classList.add('post-card');
        card.dataset.postId = post.id;

        // Post Header
        const header = document.createElement('div');
        header.classList.add('post-header');
        header.innerHTML = `
            <div class="username">${post.user.name}</div>
            <div class="time">${timeAgo(new Date(post.created_at))}</div>
            <div class="menu-btn">⋮</div>
        `;
        card.appendChild(header);

        // Image Slider
        const slider = document.createElement('div');
        slider.classList.add('image-slider');

        post.images.forEach((image, index) => {
            const img = document.createElement('img');
            img.src = image.path; // Assuming 'path' contains the image URL
            if (index === 0) img.classList.add('active');
            slider.appendChild(img);
        });

        if (post.images.length > 1) {
            const prevBtn = document.createElement('div');
            prevBtn.classList.add('slider-btn', 'slider-prev');
            prevBtn.innerHTML = '&#10094;';
            const nextBtn = document.createElement('div');
            nextBtn.classList.add('slider-btn', 'slider-next');
            nextBtn.innerHTML = '&#10095;';

            prevBtn.addEventListener('click', () => slideImages(slider, -1));
            nextBtn.addEventListener('click', () => slideImages(slider, 1));

            slider.appendChild(prevBtn);
            slider.appendChild(nextBtn);
        }

        // Like on Double Click
        slider.addEventListener('dblclick', () => {
            toggleLike(post.id, card);
        });

        card.appendChild(slider);

        // Post Footer
        const footer = document.createElement('div');
        footer.classList.add('post-footer');

        const desc = document.createElement('div');
        desc.classList.add('description');
        desc.textContent = post.description;
        footer.appendChild(desc);

        const actions = document.createElement('div');
        actions.classList.add('actions');
        updateActionsHTML(actions, post);
        footer.appendChild(actions);

        card.appendChild(footer);

        // Event Listeners for Actions
        const commentBtn = actions.querySelector('.comment-btn');
        commentBtn.addEventListener('click', () => {
            currentPostId = post.id;
            showComments(post.id);
            commentModal.classList.add('show');
        });

        const likeBtn = actions.querySelector('.like-btn');
        likeBtn.addEventListener('click', () => {
            toggleLike(post.id, card);
        });

        return card;
    }

    // Function to Update Actions HTML
    function updateActionsHTML(actions, post) {
        actions.innerHTML = `
            <span class="comment-btn"><i class="lni lni-comments"></i> ${post.comments.length}</span>
            <span class="like-btn ${post.liked ? 'liked' : ''}"><i class="lni lni-heart"></i> ${post.likes_count}</span>
        `;
    }

    // Function to Slide Images in the Slider
    function slideImages(slider, direction) {
        const imgs = slider.querySelectorAll('img');
        let activeIndex = Array.from(imgs).findIndex(img => img.classList.contains('active'));
        imgs[activeIndex].classList.remove('active');
        activeIndex += direction;
        if (activeIndex < 0) activeIndex = imgs.length - 1;
        if (activeIndex >= imgs.length) activeIndex = 0;
        imgs[activeIndex].classList.add('active');
    }

    // Function to Toggle Like
    function toggleLike(postId, card) {
        fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Ensure CSRF token is included
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                const likeBtn = card.querySelector('.like-btn');
                likeBtn.classList.toggle('liked');
                likeBtn.innerHTML = `<i class="lni lni-heart"></i> ${data.likes_count}`;
            })
            .catch(error => console.error('Error liking post:', error));
    }

    // Function to Display "Time Ago" Format
    function timeAgo(date) {
        const seconds = Math.floor((new Date() - date) / 1000);

        let interval = Math.floor(seconds / 31536000);
        if (interval >= 1) return interval + " year" + (interval > 1 ? "s" : "") + " ago";

        interval = Math.floor(seconds / 2592000);
        if (interval >= 1) return interval + " month" + (interval > 1 ? "s" : "") + " ago";

        interval = Math.floor(seconds / 86400);
        if (interval >= 1) return interval + " day" + (interval > 1 ? "s" : "") + " ago";

        interval = Math.floor(seconds / 3600);
        if (interval >= 1) return interval + " hour" + (interval > 1 ? "s" : "") + " ago";

        interval = Math.floor(seconds / 60);
        if (interval >= 1) return interval + " minute" + (interval > 1 ? "s" : "") + " ago";

        return "Just now";
    }

    // Function to Show Comments in Modal
    function showComments(postId) {
        commentList.innerHTML = '';
        fetch(`/posts/${postId}/comments`)
            .then(response => response.json())
            .then(data => {
                data.forEach(comment => {
                    const div = document.createElement('div');
                    div.classList.add('comment-item');

                    const header = document.createElement('div');
                    header.classList.add('comment-item-header');

                    const userSpan = document.createElement('span');
                    userSpan.classList.add('comment-user');
                    userSpan.textContent = comment.user.name;

                    const timeSpan = document.createElement('span');
                    timeSpan.classList.add('comment-time');
                    timeSpan.textContent = timeAgo(new Date(comment.created_at));

                    header.appendChild(userSpan);
                    header.appendChild(timeSpan);

                    const textDiv = document.createElement('div');
                    textDiv.classList.add('comment-text');
                    textDiv.textContent = comment.text;

                    div.appendChild(header);
                    div.appendChild(textDiv);

                    commentList.appendChild(div);
                });
            })
            .catch(error => console.error('Error fetching comments:', error));
    }

    // Function to Add a Comment
    function addComment(e) {
        e.preventDefault();
        const input = commentForm.querySelector('input');
        const newCommentText = input.value.trim();
        if (!newCommentText) return;

        fetch(`/posts/${currentPostId}/comments`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Ensure CSRF token is included
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    text: newCommentText
                })
            })
            .then(response => response.json())
            .then(data => {
                input.value = '';
                showComments(currentPostId);

                // Update the comments count in the post card
                const postCard = document.querySelector(`.post-card[data-post-id='${currentPostId}']`);
                const commentBtn = postCard.querySelector('.comment-btn');
                const currentCount = parseInt(commentBtn.textContent.split(' ')[1]) || 0;
                commentBtn.innerHTML = `<i class="lni lni-comments"></i> ${currentCount + 1}`;
            })
            .catch(error => console.error('Error adding comment:', error));
    }

    // ===========================
    // Remove Fake Data Generation
    // ===========================

    // Since we're fetching real data from the server, there's no need for fake data generation.
    // Ensure that any previous fake data scripts are removed or commented out to prevent conflicts.

    // For example, if you have something like this, make sure it's removed:
    /*
    // Fake Data Generation (Remove or Comment Out)
    function randomLikes(max = 1000) {
        return Math.floor(Math.random() * max);
    }

    const users = ["L0v3lyy", "User2", "JohnDoe", "Jane", "UserX", "Someone", "User3", "User4", "User5"];

    const descriptions = [
        "Dumbass looking cat...",
        "Some random post...",
        "Beautiful scenery!",
        "Had a great day at the beach.",
        "Check out my new artwork.",
        "Loving this weather!",
        "Can't believe this happened.",
        "Throwback to last summer.",
        "Enjoying my coffee ☕️"
    ];

    const imageUrls = [
        "https://via.placeholder.com/600x400/FF5733/ffffff?text=Image1",
        "https://via.placeholder.com/600x400/33FF57/ffffff?text=Image2",
        "https://via.placeholder.com/600x400/3357FF/ffffff?text=Image3",
        "https://via.placeholder.com/600x400/FF33A1/ffffff?text=Image4",
        "https://via.placeholder.com/600x400/A133FF/ffffff?text=Image5",
        "https://via.placeholder.com/600x400/33FFA1/ffffff?text=Image6",
        "https://via.placeholder.com/600x400/FFA133/ffffff?text=Image7",
        "https://via.placeholder.com/600x400/33A1FF/ffffff?text=Image8",
        "https://via.placeholder.com/600x400/A1FF33/ffffff?text=Image9"
    ];

    const commentUsers = ["JohnDoe", "Jane", "UserX", "Someone", "User3", "User4"];

    const commentTexts = [
        "Wow, cute!",
        "LOL 😂",
        "Nice post!",
        "Where is this?",
        "Amazing!",
        "So cool!",
        "Love this!",
        "Great shot!",
        "Beautiful!"
    ];

    function generateComments() {
        const comments = [];
        const numComments = Math.floor(Math.random() * 5); // Max 5 comments
        for (let i = 0; i < numComments; i++) {
            comments.push({
                user: commentUsers[Math.floor(Math.random() * commentUsers.length)],
                text: commentTexts[Math.floor(Math.random() * commentTexts.length)],
                time: `${Math.floor(Math.random() * 60)}m` // Comment time in minutes
            });
        }
        return comments;
    }

    function generateImages() {
        const images = [];
        const numImages = Math.floor(Math.random() * 5) + 1; // 1 to 5 images per post
        for (let i = 0; i < numImages; i++) {
            const randomIndex = Math.floor(Math.random() * imageUrls.length);
            images.push(imageUrls[randomIndex]);
        }
        return images;
    }

    function generatePosts(totalPosts = 50) {
        const posts = [];
        for (let i = 1; i <= totalPosts; i++) {
            const post = {
                id: i,
                user: users[Math.floor(Math.random() * users.length)],
                time: `${Math.floor(Math.random() * 24)}h`, // Post time in hours
                desc: descriptions[Math.floor(Math.random() * descriptions.length)],
                liked: Math.random() < 0.5, // Random true or false
                comments: generateComments(),
                likes: randomLikes(),
                images: generateImages()
            };
            posts.push(post);
        }
        return posts;
    }

    let allPosts = generatePosts(100); // Remove this line
    */
</script>
@endsection