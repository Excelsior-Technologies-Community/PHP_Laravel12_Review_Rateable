<!DOCTYPE html>
<html>
<head>
    <title>Admin Review Moderation</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8fafc; padding: 30px; }
        .container { max-width: 1000px; margin: 0 auto; }
        .card { background: white; border-radius: 16px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .status { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: bold; }
        .pending { background: #fef3c7; color: #92400e; }
        .approved { background: #dcfce7; color: #166534; }
        .actions button { margin-right: 8px; padding: 8px 12px; border: none; border-radius: 8px; cursor: pointer; }
        .approve { background: #22c55e; color: white; }
        .reject { background: #ef4444; color: white; }
        .back { display: inline-block; margin-bottom: 20px; color: #2563eb; text-decoration: none; }
    </style>
</head>
<body>
<div class="container">
    <a href="/reviews" class="back">← Back to Products</a>
    <h1>Admin Review Moderation</h1>

    @if(session('success'))
        <div class="card" style="background:#ecfdf5;color:#166534;">{{ session('success') }}</div>
    @endif

    <div class="card">
        <h2>Pending Reviews</h2>
        @forelse($pendingReviews as $review)
            <div style="border-top:1px solid #e2e8f0;padding-top:12px;margin-top:12px;">
                <p><strong>Product:</strong> {{ optional($review->reviewable)->name ?? 'Unknown' }}</p>
                <p><strong>Review:</strong> {{ $review->review ?: 'No text provided' }}</p>
                <p><strong>Rating:</strong> {{ optional($review->ratings()->where('key','overall')->first())->value ?? 'N/A' }}</p>
                <div class="actions" style="margin-top:10px;">
                    <form method="POST" action="/admin/reviews/{{ $review->id }}/approve" style="display:inline;">
                        @csrf
                        <button class="approve">Approve</button>
                    </form>
                    <form method="POST" action="/admin/reviews/{{ $review->id }}/reject" style="display:inline;">
                        @csrf
                        <button class="reject">Reject</button>
                    </form>
                </div>
            </div>
        @empty
            <p>No pending reviews.</p>
        @endforelse
    </div>

    <div class="card">
        <h2>Approved Reviews</h2>
        @forelse($approvedReviews as $review)
            <div style="border-top:1px solid #e2e8f0;padding-top:12px;margin-top:12px;">
                <p><strong>Product:</strong> {{ optional($review->reviewable)->name ?? 'Unknown' }}</p>
                <p><strong>Review:</strong> {{ $review->review ?: 'No text provided' }}</p>
                <span class="status approved">Approved</span>
            </div>
        @empty
            <p>No approved reviews yet.</p>
        @endforelse
    </div>
</div>
</body>
</html>
