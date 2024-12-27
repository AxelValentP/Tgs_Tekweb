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
</style>

<div class="content-wrapper">
    <div class="main-feed">
        <div class="feed-header">
            <h2>Newest</h2>
            <select id="sortPostsSelect">
                <option value="newest">Newest</option>
                <option value="popular">Popular</option>
                <option value="oldest">Oldest</option>
            </select>

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
    document.addEventListener('DOMContentLoaded', () => {
        // **Elements Selection**
        // Posts Elements
        const postContainer = document.getElementById('postContainer');
        const seeMorePostsBtn = document.getElementById('seeMorePostsBtn');
        const resetPostsBtn = document.getElementById('resetPostsBtn');
        const sortSelect = document.getElementById('sortPostsSelect');

        // Modal Elements
        const commentModal = document.getElementById('commentModal');
        const closeModal = document.getElementById('closeModal');
        const commentList = document.getElementById('commentList');
        const commentForm = document.getElementById('commentForm');
        let currentPostId = null;

        // Topics Elements
        const topicContainer = document.getElementById('topicContainer');
        const seeMoreTopicsBtn = document.getElementById('seeMoreTopicsBtn');
        const resetTopicsBtn = document.getElementById('resetTopicsBtn');
        const searchTopicsInput = document.getElementById('searchTopicsInput');

        // Profiles Elements
        const profileContainer = document.getElementById('profileContainer');
        const seeMoreProfileBtn = document.getElementById('seeMoreProfileBtn');
        const resetProfileBtn = document.getElementById('resetProfileBtn');
        const searchProfilesInput = document.getElementById('searchProfilesInput');

        // **State Management**
        // Posts State
        let postsState = {
            currentPage: 1,
            sort: 'newest',
            totalPages: 1,
            limit: 10 // Adjust based on your preference
        };

        // Topics State
        let topicsState = {
            currentPage: 1,
            lastPage: 1,
            searchQuery: '',
            limit: 3
        };

        // Profiles State
        let profilesState = {
            currentPage: 1,
            lastPage: 1,
            searchQuery: '',
            limit: 3
        };

        // **Functions to Fetch Posts**
        function fetchPosts(sort, page, append = false) {
            fetch(`/posts?sort=${sort}&page=${page}`)
                .then(response => response.json())
                .then(data => {
                    postsState.totalPages = data.last_page;

                    data.data.forEach(post => {
                        const postElement = createPostElement(post);
                        if (append) {
                            postContainer.appendChild(postElement);
                        } else {
                            postContainer.innerHTML += postElement.outerHTML;
                        }
                    });

                    // Update "See More" and "Reset" Buttons
                    if (postsState.currentPage >= postsState.totalPages) {
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

        // **Functions to Fetch Topics**
        function fetchTopics() {
            const {
                currentPage,
                searchQuery,
                limit
            } = topicsState;
            let url = `/api/topics?page=${currentPage}&limit=${limit}`;
            if (searchQuery) {
                url += `&search=${encodeURIComponent(searchQuery)}`;
            }

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    console.log('Topics Data:', data); // Debug: Log data topics ke console
                    data.data.forEach(topic => {
                        const a = document.createElement('a');
                        a.href = "#";
                        a.textContent = topic.name;
                        topicContainer.appendChild(a);
                    });

                    topicsState.lastPage = data.last_page;

                    // Update "See More" and "Reset" Buttons
                    if (topicsState.currentPage >= topicsState.lastPage) {
                        seeMoreTopicsBtn.textContent = 'No more';
                        seeMoreTopicsBtn.classList.add('disabled');
                        seeMoreTopicsBtn.style.cursor = 'default';
                        resetTopicsBtn.style.display = 'inline-block';
                    } else {
                        seeMoreTopicsBtn.textContent = 'See More';
                        seeMoreTopicsBtn.classList.remove('disabled');
                        seeMoreTopicsBtn.style.cursor = 'pointer';
                    }
                })
                .catch(error => console.error('Error fetching topics:', error));
        }

        // **Functions to Fetch Profiles**
        function fetchProfiles() {
            const {
                currentPage,
                searchQuery,
                limit
            } = profilesState;
            let url = `/api/users?page=${currentPage}&limit=${limit}`;
            if (searchQuery) {
                url += `&search=${encodeURIComponent(searchQuery)}`;
            }

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    console.log('Users Data:', data); // Debug: Log data users ke console
                    data.data.forEach(user => {
                        const a = document.createElement('a');
                        a.href = "#";
                        a.textContent = user.name;
                        profileContainer.appendChild(a);
                    });

                    profilesState.lastPage = data.last_page;

                    // Update "See More" and "Reset" Buttons
                    if (profilesState.currentPage >= profilesState.lastPage) {
                        seeMoreProfileBtn.textContent = 'No more';
                        seeMoreProfileBtn.classList.add('disabled');
                        seeMoreProfileBtn.style.cursor = 'default';
                        resetProfileBtn.style.display = 'inline-block';
                    } else {
                        seeMoreProfileBtn.textContent = 'See More';
                        seeMoreProfileBtn.classList.remove('disabled');
                        seeMoreProfileBtn.style.cursor = 'pointer';
                    }
                })
                .catch(error => console.error('Error fetching profiles:', error));
        }

        // **Event Listeners**
        // Posts Event Listeners
        sortSelect.addEventListener('change', () => {
            postsState.sort = sortSelect.value;
            postsState.currentPage = 1;
            postContainer.innerHTML = '';
            fetchPosts(postsState.sort, postsState.currentPage);
            resetPostsBtn.style.display = 'none';
        });

        seeMorePostsBtn.addEventListener('click', () => {
            if (postsState.currentPage < postsState.totalPages) {
                postsState.currentPage++;
                fetchPosts(postsState.sort, postsState.currentPage, true);
            }
        });

        resetPostsBtn.addEventListener('click', () => {
            postsState.sort = 'newest';
            sortSelect.value = 'newest';
            postsState.currentPage = 1;
            postContainer.innerHTML = '';
            fetchPosts(postsState.sort, postsState.currentPage);
            resetPostsBtn.style.display = 'none';
        });

        // Modal Event Listeners
        closeModal.addEventListener('click', () => {
            commentModal.classList.remove('show');
        });

        commentModal.addEventListener('click', (e) => {
            if (e.target === commentModal) {
                commentModal.classList.remove('show');
            }
        });

        commentForm.addEventListener('submit', addComment);

        // Topics Event Listeners
        seeMoreTopicsBtn.addEventListener('click', () => {
            if (topicsState.currentPage < topicsState.lastPage) {
                topicsState.currentPage++;
                fetchTopics();
            }
        });

        resetTopicsBtn.addEventListener('click', () => {
            topicsState.currentPage = 1;
            topicsState.searchQuery = '';
            topicContainer.innerHTML = '';
            fetchTopics();
            resetTopicsBtn.style.display = 'none';
        });

        searchTopicsInput.addEventListener('input', () => {
            const query = searchTopicsInput.value.trim();
            topicsState.searchQuery = query;
            topicsState.currentPage = 1;
            topicContainer.innerHTML = '';
            fetchTopics();
            resetTopicsBtn.style.display = 'none';
        });

        // Profiles Event Listeners
        seeMoreProfileBtn.addEventListener('click', () => {
            if (profilesState.currentPage < profilesState.lastPage) {
                profilesState.currentPage++;
                fetchProfiles();
            }
        });

        resetProfileBtn.addEventListener('click', () => {
            profilesState.currentPage = 1;
            profilesState.searchQuery = '';
            profileContainer.innerHTML = '';
            fetchProfiles();
            resetProfileBtn.style.display = 'none';
        });

        searchProfilesInput.addEventListener('input', () => {
            const query = searchProfilesInput.value.trim();
            profilesState.searchQuery = query;
            profilesState.currentPage = 1;
            profileContainer.innerHTML = '';
            fetchProfiles();
            resetProfileBtn.style.display = 'none';
        });

        // **Functions to Create Post Elements**
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
                console.log('Comment button clicked for post ID:', post.id);
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

        // **Functions to Update Actions HTML**
        function updateActionsHTML(actions, post) {
            actions.innerHTML = `
                <span class="comment-btn"><i class="lni lni-comments"></i> ${post.comments.length}</span>
                <span class="like-btn ${post.liked ? 'liked' : ''}"><i class="lni lni-heart"></i> ${post.likes_count}</span>
            `;
        }

        // **Functions to Slide Images in the Slider**
        function slideImages(slider, direction) {
            const imgs = slider.querySelectorAll('img');
            let activeIndex = Array.from(imgs).findIndex(img => img.classList.contains('active'));
            imgs[activeIndex].classList.remove('active');
            activeIndex += direction;
            if (activeIndex < 0) activeIndex = imgs.length - 1;
            if (activeIndex >= imgs.length) activeIndex = 0;
            imgs[activeIndex].classList.add('active');
        }

        // **Function to Toggle Like**
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

        // **Function to Display "Time Ago" Format**
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

        // **Function to Show Comments in Modal**
        function showComments(postId) {
            console.log('Fetching comments for post ID:', postId);
            currentPostId = postId;
            commentList.innerHTML = ''; // Clear previous comments
            commentModal.classList.add('show');

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
                            const commentItem = document.createElement('div');
                            commentItem.classList.add('comment-item');
                            commentItem.innerHTML = `
                                <div class="comment-item-header">
                                    <span class="comment-user">${comment.user.name}</span>
                                    <span class="comment-time">${new Date(comment.created_at).toLocaleString()}</span>
                                </div>
                                <div class="comment-text">${comment.text}</div>
                            `;
                            commentList.appendChild(commentItem);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching comments:', error);
                    commentList.innerHTML = '<p>Failed to load comments. Please try again later.</p>';
                });
        }

        // **Function to Add a Comment**
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

        // **Functions to Load Topics and Profiles Initially**
        function fetchAndInitializeTopics() {
            fetchTopics();
        }

        function fetchAndInitializeProfiles() {
            fetchProfiles();
        }

        // **Initial Load**
        fetchPosts(postsState.sort, postsState.currentPage);
        fetchAndInitializeTopics();
        fetchAndInitializeProfiles();

        // **Note:** All other functions and event listeners are already handled above.
    });
</script>


@endsection