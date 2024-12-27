@extends('layouts.template')

@section('content')
<style>
    /* General Styles */
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
        flex-direction: column;
        padding: 2rem;
        width: 100%;
        min-height: 100vh;
        background: #111;
        box-sizing: border-box;
    }

    .notifications-header {
        margin-bottom: 1rem;
    }

    .notifications-header h2 {
        color: #fff;
        font-size: 1.8rem;
    }

    .notifications-header p {
        color: #888;
        margin: 0;
        font-size: 1rem;
    }

    .notifications-table-container {
        background: #222;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid #333;
        margin-top: 1rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        color: #ccc;
    }

    table thead {
        background: #333;
    }

    table thead th {
        padding: 0.8rem;
        text-align: left;
        font-size: 1rem;
        color: #fff;
    }

    table tbody tr {
        transition: background 0.3s ease;
        cursor: pointer;
    }

    table tbody tr:hover {
        background: #444;
    }

    table tbody td {
        padding: 0.8rem;
        font-size: 0.9rem;
        border-bottom: 1px solid #333;
    }

    table tbody td:first-child {
        width: 40px;
        text-align: center;
    }

    table tbody td:last-child {
        text-align: center;
        width: 120px;
    }

    .empty-message {
        text-align: center;
        color: #888;
        padding: 2rem 0;
        font-size: 1.2rem;
    }

    .btn-view {
        background: #4CAF50;
        color: #fff;
        border: none;
        padding: 0.4rem 0.8rem;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: background 0.3s ease;
    }

    .btn-view:hover {
        background: #45a049;
    }
</style>

<div class="content-wrapper">
    <div class="notifications-header">
        <h2>Notifications</h2>
        <p>Stay updated with the latest interactions on your posts.</p>
    </div>

    <div class="notifications-table-container">
        <!-- Replace this data with dynamic data later -->
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Message</th>
                    <th>Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example row 1 -->
                <tr class="notification-row" data-post-id="123">
                    <td>1</td>
                    <td><strong>John Doe</strong> liked your post.</td>
                    <td>5 minutes ago</td>
                    <td>
                        <button class="btn-view" data-post-id="123">View Post</button>
                    </td>
                </tr>
                <!-- Example row 2 -->
                <tr class="notification-row" data-post-id="124">
                    <td>2</td>
                    <td><strong>Jane Smith</strong> commented: <span>"Nice post!"</span></td>
                    <td>10 minutes ago</td>
                    <td>
                        <button class="btn-view" data-post-id="124">View Post</button>
                    </td>
                </tr>
                <!-- Add more rows dynamically later -->
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Handle row or button click
        document.querySelectorAll('.notification-row, .btn-view').forEach(element => {
            element.addEventListener('click', (e) => {
                const postId = e.target.dataset.postId || e.currentTarget.dataset.postId;
                if (postId) {
                    // Redirect to homee page with the selected post
                    window.location.href = `/homee?post_id=${postId}`;
                }
            });
        });
    });
</script>
@endsection
