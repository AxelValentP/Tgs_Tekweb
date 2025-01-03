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
        margin-top: 10px;
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
        <button id="clearNotificationsBtn" class="btn-view">Clear Notifications</button>
    </div>

    <div class="notifications-table-container">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Message</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @php $index = 1; @endphp
                @foreach ($likes as $like)
                <tr>
                    <td>{{ $index++ }}</td>
                    <td><strong>{{ $like->user_name }}</strong> liked your post.</td>
                    <td>{{ \Carbon\Carbon::parse($like->created_at)->diffForHumans() }}</td>
                </tr>
                @endforeach
                @foreach ($comments as $comment)
                <tr>
                    <td>{{ $index++ }}</td>
                    <td><strong>{{ $comment->user_name }}</strong> commented: <span>"{{ $comment->comment_text }}"</span></td>
                    <td>{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</td>
                </tr>
                @endforeach
                @if ($likes->isEmpty() && $comments->isEmpty())
                <tr>
                    <td colspan="3" class="empty-message">No new notifications</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Handle clear notifications click
        const clearNotificationsBtn = document.getElementById('clearNotificationsBtn');
        clearNotificationsBtn.addEventListener('click', () => {
            fetch('/clear-notifications', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => {
                if (response.ok) {
                    alert('Notifications cleared successfully.');
                    location.reload(); // Reload the page to update the UI
                } else {
                    alert('Failed to clear notifications. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error clearing notifications:', error);
                alert('An error occurred. Please try again.');
            });
        });
    });
</script>
>


@endsection
