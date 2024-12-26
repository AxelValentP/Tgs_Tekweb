@extends('layouts.template')

@section('content')
<style>
    html, body {
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
        box-sizing: border-box;
        background: #111;
    }

    #userPostsContainer {
        display: grid;
        gap: 2rem;
        grid-template-columns: 1fr;
    }

    @media (min-width: 768px) {
        #userPostsContainer {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 1024px) {
        #userPostsContainer {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    .post-card {
        background: #222;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #333;
        display: flex;
        flex-direction: column;
    }

    .post-header {
        display: flex;
        align-items: center;
        padding: 0.5rem;
    }

    .post-header .username {
        font-weight: bold;
        color: #ccc;
    }

    .post-header .time {
        color: #888;
        font-size: 0.9rem;
        margin-left: 0.5rem;
    }

    .menu-btn {
        color: #ccc;
        cursor: pointer;
    }

    .post-image {
        position: relative;
        width: 100%;
        padding-top: 100%;
        overflow: hidden;
        background: #999;
    }

    .post-image img, .post-image .no-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .post-image .no-image {
        display: flex;
        align-items: center;
        justify-content: center;
        color: #000;
        font-weight: bold;
        font-size: 1rem;
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
        font-size: 0.9rem;
    }

    .post-footer .description .edited {
        color: #888;
        font-size: 0.8rem;
        margin-left: 0.3rem;
    }

    .post-footer .actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        color: #ccc;
        font-size: 0.9rem;
        gap: 0.5rem;
    }

    .actions button {
        background: #444;
        color: #ccc;
        border: none;
        padding: 0.3rem 0.5rem;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.8rem;
    }

    .actions button:hover {
        background: #555;
    }

    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
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
        max-width: 900px;
        padding: 1rem;
        display: flex;
        flex-direction: row;
        color: #ccc;
        gap: 1rem;
    }

    .modal-image {
        flex: 2;
        background: #333;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .modal-details {
        flex: 3;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .comments-container,
    .likes-container {
        max-height: 150px;
        overflow-y: auto;
    }

    .comments-container table {
        width: 100%;
        border-collapse: collapse;
        color: #ccc;
    }

    .comments-container th, .comments-container td {
        border: 1px solid #444;
        padding: 0.5rem;
        text-align: left;
        vertical-align: top;
    }

    .likes-container ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .likes-container li {
        margin-bottom: 0.5rem;
    }

    .hide-comment-btn {
        background: #444;
        color: #ccc;
        border: none;
        padding: 0.3rem 0.5rem;
        border-radius: 4px;
        cursor: pointer;
    }

    .hide-comment-btn:hover {
        background: #555;
    }

    @media (max-width: 768px) {
        .modal {
            flex-direction: column;
        }
    }
</style>

<div class="content-wrapper">
    <div class="main-content">
        <h2>Your Posts</h2>
        <div id="userPostsContainer">
            @forelse($posts as $post)
                <div class="post-card" data-post-id="{{ $post->id }}">
                    <div class="post-header">
                        <div class="username">{{ $post->user->name }}</div>
                        <div class="time">{{ $post->created_at->diffForHumans() }}</div>
                        <div class="menu-btn">⋮</div>
                    </div>
                    <div class="post-image">
                        @if($post->images->count() > 0)
                            <img src="{{ $post->images->first()->path }}" alt="Post Image">
                        @else
                            <div class="no-image">No Image</div>
                        @endif
                    </div>
                    <div class="post-footer">
                        <div class="description">
                            {{ $post->description }}
                            @if($post->edited)
                                <span class="edited">(Edited)</span>
                            @endif
                        </div>
                        <div class="actions">
                            <button class="edit-btn">Edit</button>
                            <button class="delete-btn" style="background:#800;">Delete</button>
                        </div>
                    </div>
                </div>
            @empty
                <p>You have not created any posts yet.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="modal-overlay" id="postModalOverlay">
    <div class="modal">
        <div class="modal-image" id="postModalImage"></div>
        <div class="modal-details">
            <div class="comments-container">
                <h4>Comments</h4>
                <table>
                    <tbody id="commentsList"></tbody>
                </table>
            </div>
            <div class="likes-container">
                <h4>Likes</h4>
                <ul id="likesList"></ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modalOverlay = document.getElementById('postModalOverlay');
    const modalImage = document.getElementById('postModalImage');
    const commentsList = document.getElementById('commentsList');
    const likesList = document.getElementById('likesList');

    document.querySelectorAll('.post-card').forEach(card => {
        card.addEventListener('click', () => {
            const postId = card.getAttribute('data-post-id');
            const imageSrc = card.querySelector('.post-image img')?.src || 'No Image';

            modalImage.innerHTML = imageSrc !== 'No Image' ? `<img src="${imageSrc}" alt="Post Image">` : '<div>No Image</div>';

            fetch(`/posts/${postId}/details`)
                .then(res => res.json())
                .then(data => {
                    commentsList.innerHTML = '';
                    data.comments.forEach(comment => {
                        commentsList.innerHTML += `
                            <tr>
                                <td>${comment.user}</td>
                                <td>${comment.text}</td>
                                <td><button class="hide-comment-btn" data-id="${comment.id}">Hide</button></td>
                            </tr>
                        `;
                    });

                    document.querySelectorAll('.hide-comment-btn').forEach(btn => {
                        btn.addEventListener('click', e => {
                            const id = e.target.getAttribute('data-id');
                            fetch(`/comments/${id}/hide`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            }).then(res => {
                                if (res.ok) {
                                    e.target.closest('tr').remove();
                                } else {
                                    console.error('Failed to hide the comment');
                                }
                            });
                        });
                    });

                    likesList.innerHTML = '';
                    data.likes.forEach(like => {
                        likesList.innerHTML += `<li>${like.user}</li>`;
                    });

                    modalOverlay.classList.add('show');
                });
        });
    });

    document.getElementById('closePostModal').addEventListener('click', () => {
        modalOverlay.classList.remove('show');
    });
});
</script>
@endsection
