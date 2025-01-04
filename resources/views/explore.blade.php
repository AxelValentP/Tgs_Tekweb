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
        background: #111;
        box-sizing: border-box;
    }

    .main-feed {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 1rem;
        background: #111;
        box-sizing: border-box;
    }

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

    .post-header .profile-pic {
        width: 40px;
        /* Adjust size as needed */
        height: 40px;
        /* Match width for a perfect circle */
        border-radius: 50%;
        /* This creates the circular frame */
        margin-right: 0.5rem;
        /* Space between the image and username */
        object-fit: cover;
        /* Ensures the image fits well */
        border: 2px solid #333;
        /* Optional border for aesthetics */
    }

    .comment-item-header .comment-user .profile-pic {
        width: 40px;
        /* Adjust size as needed */
        height: 40px;
        /* Match width for a perfect circle */
        border-radius: 50%;
        /* This creates the circular frame */
        margin-right: 0.5rem;
        /* Space between the image and username */
        object-fit: cover;
        /* Ensures the image fits well */
        border: 2px solid #333;
        /* Optional border for aesthetics */
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
        height: 20rem;
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

    .sidebar-right {
        background: #111;
        border-left: 1px solid #333;
        display: flex;
        flex-direction: column;
        box-sizing: border-box;
        padding: 1rem;
        width: 300px;
        flex-shrink: 0;
    }

    .search-box-container {
        margin-bottom: 1.5rem;
    }

    .search-box-container h4 {
        margin: 0 0 0.5rem 0;
        font-size: 1rem;
        color: #ccc;
    }

    .search-box {
        background: #000;
        display: flex;
        align-items: center;
        padding: 0.5rem;
        border-radius: 4px;
    }

    .search-box input {
        flex: 1;
        background: none;
        border: none;
        color: #ccc;
        margin-right: 0.5rem;
    }

    .search-box input:focus {
        outline: none;
    }

    .search-box i {
        color: #ccc;
        cursor: pointer;
    }

    .sidebar-section {
        background: #000;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border: 1px solid #333;
    }

    .sidebar-section h3 {
        margin-top: 0;
        margin-bottom: 1rem;
        font-size: 1rem;
        color: #ccc;
    }

    .sidebar-section a {
        color: #ccc;
        text-decoration: none;
        display: block;
        margin-bottom: 0.5rem;
    }

    .sidebar-section a.hide {
        display: none;
    }

    .sidebar-section a:hover {
        text-decoration: underline;
    }

    .sidebar-section .see-more {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 0.5rem;
    }

    .see-more-btn {
        background: #222;
        color: #ccc;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        cursor: pointer;
        border: 1px solid #333;
        font-size: 0.9rem;
    }

    .see-more-btn.disabled {
        opacity: 0.5;
        cursor: default;
    }

    .reset-btn {
        background: #333;
        color: #ccc;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        border: 1px solid #444;
        font-size: 0.9rem;
        cursor: pointer;
        margin-left: 0.5rem;
    }

    .no-more {
        color: #888;
    }

    .see-more-posts-container {
        text-align: center;
        margin-top: 20px;
    }

    .see-more-posts-container .see-more-btn {
        margin-top: 1rem;
    }

    @media (max-width: 992px) and (min-width: 768px) {
        .sidebar-right {
            width: 250px;
        }
    }

    @media (max-width: 768px) {
        .content-wrapper {
            flex-direction: column;
        }

        .sidebar-right {
            width: 100%;
            border-left: none;
            border-top: 1px solid #333;
            order: 1;
        }

        .main-feed {
            order: 2;
        }

        .post-card {
            max-width: 100%;
        }
    }

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
        /* Limit the maximum height */
        overflow: hidden;
        /* Ensure no overflow affects the modal layout */
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
        /* Grow to fill available space */
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        overflow-y: auto;
        /* Add vertical scrolling */
        max-height: calc(2.5rem * 5);
        /* 2.5rem (approx height of one comment) * 5 = Height for 5 comments */
        margin-bottom: 1rem;
        /* Space for the comment input */
        scrollbar-width: thin;
        /* Modern browsers - thin scrollbar */
        scrollbar-color: #555 #222;
        /* Modern browsers - custom scrollbar colors */
    }

    /* Optional: Customize scrollbar for webkit browsers (Chrome, Edge, Safari) */
    .comment-list::-webkit-scrollbar {
        width: 8px;
    }

    .comment-list::-webkit-scrollbar-track {
        background: #222;
    }

    .comment-list::-webkit-scrollbar-thumb {
        background-color: #555;
        border-radius: 4px;
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

    .comment-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .comment-user {
        display: flex;
        align-items: center;
        font-weight: bold;
        color: #ddd;
    }

    .like-btn {
        display: flex;
        align-items: center;
        margin-left: 10px;
        cursor: pointer;
        color: #aaa;
        font-size: 0.9rem;
    }

    .like-btn.liked i {
        color: red;
    }

    .like-btn:hover {
        color: #fff;
    }

    .comment-time {
        font-size: 0.8rem;
        color: #aaa;
    }

    .comment-text {
        margin-top: 5px;
        color: #ccc;
        font-size: 0.9rem;
        line-height: 1.4;
    }

    .comment-actions {
        margin-top: 10px;
    }

    .reply-btn {
        background: none;
        border: none;
        color: #008cba;
        cursor: pointer;
        font-size: 0.9rem;
        padding: 0;
    }

    .reply-btn:hover {
        text-decoration: underline;
    }

    .content-wrapper {
        opacity: 0;
        transition: opacity 1s ease-in;
    }

    .content-wrapper.loaded {
        opacity: 1;
    }
</style>

<div class="content-wrapper">
    <div class="main-feed">
        <div class="feed-header">
            <h2>Newest</h2>
            <div class="options">
                <select id="followFilter">
                    <option value="showall">Show All</option>
                    <option value="followed">Followed Only</option>
                </select>

                <select id="sortPostsSelect">
                    <option value="newest">Newest</option>
                    <option value="popular">Popular</option>
                    <option value="oldest">Oldest</option>
                </select>
            </div>

        </div>
        <div class="divider"></div>

        <div id="postContainer"></div>
        <div class="see-more-posts-container">
            <span class="see-more-btn" id="seeMorePostsBtn">See More Posts</span>
            <span class="reset-btn" id="resetPostsBtn" style="display:none;">Reset</span>
        </div>

    </div>

    <div class="sidebar-right">
        <div class="search-box-container">
            <h4>Search Topics</h4>
            <div class="search-box">
                <input type="text" placeholder="Search topics..." id="searchTopicsInput" />
                <i class="lni lni-search-alt"></i>
            </div>
        </div>

        <div class="sidebar-section">
            <h3>TOPICS</h3>
            <div id="topicContainer"></div>
            <div class="see-more">
                <span class="see-more-btn" id="seeMoreTopicsBtn">See More</span>
                <span class="reset-btn" id="resetTopicsBtn" style="display:none;">Reset</span>
            </div>
        </div>

        <div class="search-box-container">
            <h4>Search Profiles</h4>
            <div class="search-box">
                <input type="text" placeholder="Search profiles..." id="searchProfilesInput" />
                <i class="lni lni-search-alt"></i>
            </div>
        </div>

        <div class="sidebar-section profile-list">
            <h3>PROFILE</h3>
            <div id="profileContainer"></div>
            <div class="see-more">
                <span class="see-more-btn" id="seeMoreProfileBtn">See More</span>
                <span class="reset-btn" id="resetProfileBtn" style="display:none;">Reset</span>
            </div>
        </div>
    </div>
</div>

<!-- Modal Overlay -->
<div class="modal-overlay" id="commentModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Comments</h3>
            <span class="close-btn" id="closeModal">&times;</span>
        </div>
        <div class="modal-body">
            <div class="comment-list" id="commentList"></div>
        </div>
        <form class="comment-form" id="commentForm">
            <input type="text" placeholder="Add a comment..." required />
            <button type="submit">Post</button>
        </form>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        document.querySelector('.content-wrapper').classList.add('loaded');
    });
</script>
<script>
    // Ensure that the DOM is fully loaded before executing scripts
    document.addEventListener('DOMContentLoaded', () => {
        // Select Elements
        const postContainer = document.getElementById('postContainer');
        const seeMorePostsBtn = document.getElementById('seeMorePostsBtn');
        const resetPostsBtn = document.getElementById('resetPostsBtn');
        const sortSelect = document.getElementById('sortPostsSelect');

        // Pagination and Sorting Variables
        let currentPage = 1;
        let currentSort = 'newest';
        let totalPages = 1;
        const chunkSize = 3; // You can adjust this based on your preference

        // Modal Elements
        const commentModal = document.getElementById('commentModal');
        const closeModal = document.getElementById('closeModal');
        const commentList = document.getElementById('commentList');
        const commentForm = document.getElementById('commentForm');
        let currentPostId = null;

        // Initial Load
        fetchPosts(currentSort, currentPage, false, currentFilter);


        const currentUser = {
            id: {
                {
                    auth() - > user() - > id
                }
            },
            name: "{{ auth()->user()->name }}"
        };

        // Event Listeners

        followSelect.addEventListener('change', () => {
            const currentFilter = followSelect.value; // Get the current filter value
            currentSort = sortSelect.value; // Get the current sort value
            currentPage = 1; // Reset to the first page
            postContainer.innerHTML = ''; // Clear the current posts
            fetchPosts(currentSort, currentPage, false, currentFilter); // Fetch posts with filter
        });

        sortSelect.addEventListener('change', () => {
            const currentFilter = followSelect.value; // Get the current filter value
            currentSort = sortSelect.value; // Get the current sort value
            currentPage = 1; // Reset to the first page
            postContainer.innerHTML = ''; // Clear the current posts
            fetchPosts(currentSort, currentPage, false, currentFilter); // Fetch posts with sorting

            // Update the header title dynamically
            const headerTitle = document.querySelector('.feed-header h2');
            switch (currentSort) {
                case 'popular':
                    headerTitle.textContent = 'Popular';
                    break;
                case 'oldest':
                    headerTitle.textContent = 'Oldest';
                    break;
                case 'newest':
                default:
                    headerTitle.textContent = 'Newest';
                    break;
            }

            resetPostsBtn.style.display = 'none'; // Hide the reset button if applicable
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

        // Function to Fetch Posts
        function fetchPosts(sort, page, append = false, filter = 'showall') {
            fetch(`/posts?sort=${sort}&page=${page}&filter=${filter}`)
                .then(response => response.json())
                .then(data => {
                    totalPages = data.last_page;

                    if (!append) {
                        postContainer.innerHTML = ''; // Clear container if not appending
                    }

                    data.data.forEach(post => {
                        const postElement = createPostElement(post);
                        postContainer.appendChild(postElement);
                    });

                    // Check if more pages are available
                    if (currentPage >= totalPages) {
                        observer.unobserve(scrollTrigger); // Stop observing when no more pages
                    } else {
                        observer.observe(scrollTrigger); // Re-attach observer for next page
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
        <img src="${post.user.profile_image}" alt="User Profile Picture" class="profile-pic">
        <div class="username">${post.user.name}</div>
        <div class="time">${timeAgo(new Date(post.created_at))}</div>
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
                prevBtn.addEventListener('click', () => slideImages(slider, -1));

                const nextBtn = document.createElement('div');
                nextBtn.classList.add('slider-btn', 'slider-next');
                nextBtn.innerHTML = '&#10095;';
                nextBtn.addEventListener('click', () => slideImages(slider, 1));

                slider.appendChild(prevBtn);
                slider.appendChild(nextBtn);
            }

            slider.addEventListener('dblclick', () => toggleLike(post.id, card));
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
            likeBtn.addEventListener('click', () => toggleLike(post.id, card));

            return card;
        }

        // Infinite Scroll Setup

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    currentPage++;
                    fetchPosts(currentSort, currentPage, true, followSelect.value);
                }
            });
        });

        // Start observing the trigger element
        observer.observe(scrollTrigger);

        // Function to Update Actions HTML
        function updateActionsHTML(actions, post) {
            // Hitung komentar yang tidak di-hide
            const visibleCommentsCount = post.comments.filter(comment => comment.hide === 0).length;

            actions.innerHTML = `
        <span class="comment-btn"><i class="lni lni-comments"></i> ${visibleCommentsCount}</span>
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
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // Ensure CSRF token is included
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
        // Show comments in modal
        function showComments(postId) {
            currentPostId = postId; // Set the current post ID
            commentList.innerHTML = ''; // Clear previous comments
            commentModal.classList.add('show'); // Show the modal

            // Fetch comments for the specific post
            fetch(`/posts/${postId}/comments`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch comments');
                    }
                    return response.json();
                })
                .then(comments => {
                    if (comments.length === 0) {
                        commentList.innerHTML = '<p>No comments yet. Be the first to comment!</p>';
                    } else {
                        comments.forEach(comment => {
                            const commentElement = createCommentWithReplies(comment);
                            commentList.appendChild(commentElement);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching comments:', error);
                    commentList.innerHTML = '<p>Failed to load comments. Please try again later.</p>';
                });
        }


        // Create a comment element with reply functionality
        function createCommentWithReplies(comment) {
            const commentItem = document.createElement('div');
            commentItem.classList.add('comment-item');
            commentItem.innerHTML = `
        <div class="comment-item-header">
            <span class="comment-user">
                <img src="${comment.user.profile_image}" alt="User Profile Picture" class="profile-pic">
                ${comment.user.name}
                <span class="like-btn ${comment.liked ? 'liked' : ''}" data-comment-id="${comment.id}">
                    <i class="lni lni-heart"></i> ${comment.likes_count || 0}
                </span>
            </span>
            <span class="comment-time">${new Date(comment.created_at).toLocaleString()}</span>
            ${comment.user.id === currentUser.id ? '<button class="delete-comment-btn">Delete</button>' : ''}
        </div>
        <div class="comment-text">${comment.text}</div>
        <div class="comment-actions">
            <button class="reply-btn" data-comment-id="${comment.id}">Reply</button>
        </div>
        <div class="replies-list" id="replies-${comment.id}" style="margin-left: 20px; margin-top: 10px;"></div>
        <form class="reply-form" id="reply-form-${comment.id}" style="display: none; margin-top: 10px;">
            <input type="text" placeholder="Write a reply..." required />
            <button type="submit">Reply</button>
        </form>
    `;

            const replyBtn = commentItem.querySelector('.reply-btn');
            const replyForm = commentItem.querySelector(`#reply-form-${comment.id}`);
            const repliesList = commentItem.querySelector(`#replies-${comment.id}`);
            const likeBtn = commentItem.querySelector('.like-btn');

            // Toggle reply form visibility
            replyBtn.addEventListener('click', () => {
                replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
                if (replyForm.style.display === 'block') {
                    fetchReplies(comment.id, repliesList);
                }
            });

            // Handle reply submission
            replyForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const input = replyForm.querySelector('input');
                const replyText = input.value.trim();
                if (!replyText) return;

                fetch(`/comments/${comment.id}/replies`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            text: replyText
                        }),
                    })
                    .then(response => response.json())
                    .then(reply => {
                        input.value = '';
                        fetchReplies(comment.id, repliesList); // Refresh replies after adding
                    })
                    .catch(error => console.error('Error adding reply:', error));
            });

            // Handle comment like
            likeBtn.addEventListener('click', () => {
                const commentId = likeBtn.dataset.commentId;

                fetch(`/comments/${commentId}/like`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Update like button and count
                        likeBtn.classList.toggle('liked');
                        likeBtn.innerHTML = `<i class="lni lni-heart"></i> ${data.likes_count}`;
                    })
                    .catch(error => console.error('Error liking comment:', error));
            });
            // Delete comment button handler
            const deleteCommentBtn = commentItem.querySelector('.delete-comment-btn');
            if (deleteCommentBtn) {
                deleteCommentBtn.addEventListener('click', () => deleteComment(comment.id));
            }

            return commentItem;
        }

        function deleteComment(commentId) {
            fetch(`/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to delete comment');
                    }
                    return response.json();
                })
                .then(data => {
                    showComments(currentPostId); // Refresh comments
                })
                .catch(error => console.error('Error deleting comment:', error));
        }

        function deleteReply(replyId) {
            fetch(`/replies/${replyId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to delete reply');
                    }
                    return response.json();
                })
                .then(data => {
                    showComments(currentPostId); // Refresh comments
                })
                .catch(error => console.error('Error deleting reply:', error));
        }



        // Fetch replies for a specific comment
        function fetchReplies(commentId, repliesList) {
            fetch(`/comments/${commentId}/replies`)
                .then(response => response.json())
                .then(replies => {
                    repliesList.innerHTML = ''; // Clear existing replies
                    if (replies.length === 0) {
                        repliesList.innerHTML = '<p>No replies yet.</p>';
                    } else {
                        replies.forEach(reply => {
                            const replyElement = document.createElement('div');
                            replyElement.classList.add('comment-item');
                            replyElement.innerHTML = `
                        <div class="comment-item-header">
                            <span class="comment-user">
                                <img src="${reply.user.profile_image}" alt="User Profile Picture" class="profile-pic">
                                ${reply.user.name}
                                </span>
                            <span class="comment-time">${new Date(reply.created_at).toLocaleString()}</span>
                            ${currentUser && reply.user.id === currentUser.id ? '<button class="delete-reply-btn">Delete</button>' : ''}
                        </div>
                        <div class="comment-text">${reply.text}</div>
                    `;
                            const deleteReplyBtn = replyElement.querySelector('.delete-reply-btn');
                            if (deleteReplyBtn) {
                                deleteReplyBtn.addEventListener('click', () => deleteReply(reply.id));
                            }
                            repliesList.appendChild(replyElement);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching replies:', error);
                    repliesList.innerHTML = '<p>Failed to load replies. Please try again later.</p>';
                });
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
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
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

        // Initial Loading of Topics and Profiles
        loadMoreTopics();
        loadMoreProfiles();

        // Search Functionality for Topics and Profiles

        searchTopicsInput.addEventListener('input', filterTopics);
        searchProfilesInput.addEventListener('input', filterProfiles);

        seeMoreTopicsBtn.addEventListener('click', loadMoreTopics);
        resetTopicsBtn.addEventListener('click', resetTopics);

        function loadMoreTopics() {
            const slice = allTopics.slice(topicIndex, topicIndex + chunkSize);
            slice.forEach(t => {
                const a = document.createElement('a');
                a.href = "#";
                a.textContent = t;
                topicContainer.appendChild(a);
            });
            topicIndex += chunkSize;
            checkTopicsPagination();
        }

        function resetTopics() {
            topicIndex = 0;
            topicContainer.innerHTML = '';
            seeMoreTopicsBtn.textContent = 'See More';
            seeMoreTopicsBtn.classList.remove('disabled');
            seeMoreTopicsBtn.style.cursor = 'pointer';
            resetTopicsBtn.style.display = 'none';
            seeMoreTopicsBtn.addEventListener('click', loadMoreTopics);
            loadMoreTopics();
        }

        function checkTopicsPagination() {
            if (topicIndex >= allTopics.length) {
                seeMoreTopicsBtn.textContent = 'No more';
                seeMoreTopicsBtn.classList.add('disabled');
                seeMoreTopicsBtn.style.cursor = 'default';
                resetTopicsBtn.style.display = 'inline-block';
                seeMoreTopicsBtn.removeEventListener('click', loadMoreTopics);
            } else {
                seeMoreTopicsBtn.textContent = 'See More';
                seeMoreTopicsBtn.classList.remove('disabled');
            }
        }

        // Functions for Profiles Pagination


        seeMoreProfileBtn.addEventListener('click', loadMoreProfiles);
        resetProfileBtn.addEventListener('click', resetProfiles);

        function loadMoreProfiles() {
            const slice = allProfiles.slice(profileIndex, profileIndex + chunkSize);
            slice.forEach(pf => {
                const a = document.createElement('a');
                a.href = "#";
                a.textContent = pf;
                profileContainer.appendChild(a);
            });
            profileIndex += chunkSize;
            checkProfilesPagination();
        }

        function resetProfiles() {
            profileIndex = 0;
            profileContainer.innerHTML = '';
            seeMoreProfileBtn.textContent = 'See More';
            seeMoreProfileBtn.classList.remove('disabled');
            seeMoreProfileBtn.style.cursor = 'pointer';
            resetProfileBtn.style.display = 'none';
            seeMoreProfileBtn.addEventListener('click', loadMoreProfiles);
            loadMoreProfiles();
        }

        function checkProfilesPagination() {
            if (profileIndex >= allProfiles.length) {
                seeMoreProfileBtn.textContent = 'No more';
                seeMoreProfileBtn.classList.add('disabled');
                seeMoreProfileBtn.style.cursor = 'default';
                resetProfileBtn.style.display = 'inline-block';
                seeMoreProfileBtn.removeEventListener('click', loadMoreProfiles);
            } else {
                seeMoreProfileBtn.textContent = 'See More';
                seeMoreProfileBtn.classList.remove('disabled');
            }
        }

        // Functions for Filtering Topics and Profiles
        function filterTopics() {
            const q = searchTopicsInput.value.toLowerCase();
            Array.from(topicContainer.querySelectorAll('a')).forEach(a => {
                if (a.textContent.toLowerCase().includes(q)) {
                    a.classList.remove('hide');
                } else {
                    a.classList.add('hide');
                }
            });
        }

        function filterProfiles() {
            const q = searchProfilesInput.value.toLowerCase();
            Array.from(profileContainer.querySelectorAll('a')).forEach(a => {
                if (a.textContent.toLowerCase().includes(q)) {
                    a.classList.remove('hide');
                } else {
                    a.classList.add('hide');
                }
            });
        }
    });

    // ========== TOPICS & PROFILES SECTION =========== //

    // =========================================================
    // =============== TOPICS SECTION ==========================
    // =========================================================
    const searchTopicsInput = document.getElementById('searchTopicsInput');
    const topicContainer = document.getElementById('topicContainer');
    const seeMoreTopicsBtn = document.getElementById('seeMoreTopicsBtn');
    const resetTopicsBtn = document.getElementById('resetTopicsBtn');

    let allTopics = []; // Menampung semua topics acak dari server
    let topicIndex = 0; // Penanda index "See More"
    const topicChunkSize = 3; // Jumlah item per "See More"
    let searchActiveTopics = false; // Apakah user sedang melakukan search?

    // 1) Fetch data default (topics shuffled)
    fetch('/topics/all-shuffled')
        .then(res => res.json())
        .then(data => {
            allTopics = data;
            loadMoreTopics(); // Tampilkan 3 item awal
        })
        .catch(err => console.error('Error fetching /topics/all-shuffled:', err));

    let debounceTimeout = null;


    searchTopicsInput.addEventListener('input', function() {
        const query = this.value.trim();

        // Clear request sebelumnya
        clearTimeout(debounceTimeout);

        // Debounce 300 ms
        debounceTimeout = setTimeout(() => {
            // Di sini baru fetch
            doTopicSearch(query);
        }, 150);
    });

    function doTopicSearch(query) {
        topicContainer.innerHTML = '';
        seeMoreTopicsBtn.style.display = 'none';
        resetTopicsBtn.style.display = 'none';

        if (!query) {
            searchActiveTopics = false;
            topicIndex = 0; // Reset index
            topicContainer.innerHTML = '';
            seeMoreTopicsBtn.style.display = 'inline-block'; // Tampilkan "See More"
            resetTopicsBtn.style.display = 'none'; // Sembunyikan reset
            loadMoreTopics(); // Munculkan 3 item awal
            return;
        }

        fetch('/topics/search?query=' + encodeURIComponent(query))
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    topicContainer.innerHTML = '<p>No Topics Found.</p>';
                } else {
                    data.forEach(topic => {
                        const a = document.createElement('a');
                        a.href = '#';
                        a.textContent = '#' + topic.name;
                        topicContainer.appendChild(a);
                    });
                }
            })
            .catch(err => console.error(err));
    }

    // 3) Load more topics (default data)
    seeMoreTopicsBtn.addEventListener('click', loadMoreTopics);

    function loadMoreTopics() {
        // Jika user sedang search, batalkan "See More"
        if (searchActiveTopics) return;

        const slice = allTopics.slice(topicIndex, topicIndex + topicChunkSize);
        slice.forEach(t => {
            const a = document.createElement('a');
            a.href = '#';
            a.textContent = '# ' + t; // 't' karena di /topics/all-shuffled => pluck('name')
            topicContainer.appendChild(a);
        });
        topicIndex += topicChunkSize;

        if (topicContainer.children.length > 3) {
            resetTopicsBtn.style.display = 'inline-block'; // Show reset button
        }

        // Jika sudah melebihi total data
        if (topicIndex >= allTopics.length) {
            seeMoreTopicsBtn.textContent = 'No more';
            seeMoreTopicsBtn.classList.add('disabled');
            seeMoreTopicsBtn.style.cursor = 'default';
            resetTopicsBtn.style.display = 'inline-block';
        }
    }

    // 4) Reset button
    resetTopicsBtn.addEventListener('click', () => {
        // Kembalikan ke awal
        seeMoreTopicsBtn.textContent = 'See More';
        seeMoreTopicsBtn.classList.remove('disabled');
        seeMoreTopicsBtn.style.cursor = 'pointer';
        resetTopicsBtn.style.display = 'none';

        topicIndex = 0;
        topicContainer.innerHTML = '';
        loadMoreTopics();
    });


    // =========================================================
    // =============== USERS SECTION ===========================
    // =========================================================
    const searchProfilesInput = document.getElementById('searchProfilesInput');
    const profileContainer = document.getElementById('profileContainer');
    const seeMoreProfileBtn = document.getElementById('seeMoreProfileBtn');
    const resetProfileBtn = document.getElementById('resetProfileBtn');

    let allUsers = []; // Menampung semua users acak
    let profileIndex = 0; // Penanda index "See More"
    const userChunkSize = 3;
    let searchActiveUsers = false; // Apakah user sedang melakukan search?

    // 1) Fetch data default (users shuffled)
    fetch('/users/all-shuffled')
        .then(res => res.json())
        .then(data => {
            allUsers = data;
            loadMoreProfiles();
        })
        .catch(err => console.error('Error fetching /users/all-shuffled:', err));


    // Variabel penampung timeout untuk debounce
    let debounceProfilesTimeout = null;

    // Ganti event 'input' seperti ini
    searchProfilesInput.addEventListener('input', function() {
        // Bersihkan timeout sebelumnya (jika ada)
        clearTimeout(debounceProfilesTimeout);

        // Simpan nilai query
        const query = this.value.trim();

        // Tunda eksekusi fetch selama 300ms setelah user berhenti mengetik
        debounceProfilesTimeout = setTimeout(() => {
            doUserSearch(query);
        }, 150);
    });

    // Fungsi yang benar-benar melakukan fetch ke server
    function doUserSearch(query) {
        if (!query) {
            // Query kosong -> kembali ke "default data" mode
            searchActiveUsers = false;
            profileIndex = 0;
            profileContainer.innerHTML = '';
            seeMoreProfileBtn.style.display = 'inline-block';
            resetProfileBtn.style.display = 'none';
            loadMoreProfiles();
            return;
        }

        // Ada query -> "search mode"
        searchActiveUsers = true;

        profileContainer.innerHTML = '';
        seeMoreProfileBtn.style.display = 'none';
        resetProfileBtn.style.display = 'none';

        // Lakukan pencarian
        fetch('/users/search?query=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    profileContainer.innerHTML = '<p>No Profiles Found.</p>';
                } else {
                    data.forEach(user => {
                        // Bungkus satu user dalam div "user-list-item"
                        const userItem = document.createElement('div');
                        userItem.classList.add('user-list-item');

                        // Buat elemen avatar
                        const avatar = document.createElement('div');
                        avatar.classList.add('user-avatar');

                        // Atau, kalau nanti ada sumber gambar: 
                        // const avatar = document.createElement('img');
                        // avatar.src = user.avatar_url || 'default-avatar.png';
                        // avatar.classList.add('user-avatar');

                        // Buat elemen link atau teks nama user
                        const userNameLink = document.createElement('a');
                        userNameLink.href = "#";
                        userNameLink.textContent = user.name;
                        userNameLink.style.color = '#ccc';
                        userNameLink.style.textDecoration = 'none';

                        // Rangkai: avatar + nama
                        userItem.appendChild(avatar);
                        userItem.appendChild(userNameLink);

                        // Masukkan ke container
                        profileContainer.appendChild(userItem);
                    });

                }
            })
            .catch(error => {
                console.error('Error searching users:', error);
                profileContainer.innerHTML = '<p>Error loading profiles.</p>';
            });
    }


    // 3) Load more profiles (default data)
    seeMoreProfileBtn.addEventListener('click', loadMoreProfiles);

    function loadMoreProfiles() {
        if (searchActiveUsers) return;

        const slice = allUsers.slice(profileIndex, profileIndex + userChunkSize);
        slice.forEach(user => {
            // Create the user item container
            const userItem = document.createElement('div');
            userItem.classList.add('user-list-item');
            userItem.style.display = 'flex';
            userItem.style.alignItems = 'center';
            userItem.style.marginBottom = '10px';

            // Create the avatar element (using user's profile image)
            const avatar = document.createElement('img');
            avatar.src = user.profile_image || 'https://via.placeholder.com/40';
            avatar.alt = user.name;
            avatar.style.width = '40px';
            avatar.style.height = '40px';
            avatar.style.borderRadius = '50%'; // Make it circular
            avatar.style.marginRight = '10px'; // Space between image and name

            // Create the user name link
            const userNameLink = document.createElement('a');
            userNameLink.href = '#'; // Adjust this if you have a profile page link
            userNameLink.textContent = user.name;
            userNameLink.style.color = '#ccc';
            userNameLink.style.textDecoration = 'none';

            // Assemble the user item
            userItem.appendChild(avatar);
            userItem.appendChild(userNameLink);

            // Append the user item to the profile container
            profileContainer.appendChild(userItem);
        });

        profileIndex += userChunkSize;

        // Show the reset button if more than 3 profiles are displayed
        if (profileContainer.children.length > 3) {
            resetProfileBtn.style.display = 'inline-block';
        }

        // Check if all profiles have been loaded
        if (profileIndex >= allUsers.length) {
            seeMoreProfileBtn.textContent = 'No more';
            seeMoreProfileBtn.classList.add('disabled');
            seeMoreProfileBtn.style.cursor = 'default';
        } else {
            seeMoreProfileBtn.textContent = 'See More';
            seeMoreProfileBtn.classList.remove('disabled');
            seeMoreProfileBtn.style.cursor = 'pointer';
        }
    }


    // 4) Reset button
    resetProfileBtn.addEventListener('click', () => {
        seeMoreProfileBtn.textContent = 'See More';
        seeMoreProfileBtn.classList.remove('disabled');
        seeMoreProfileBtn.style.cursor = 'pointer';
        resetProfileBtn.style.display = 'none';

        profileIndex = 0;
        profileContainer.innerHTML = '';
        loadMoreProfiles();
    });
</script>

@endsection