<!DOCTYPE html>
<html>
<head>
    <title>Notifications</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <!-- Top Navigation Bar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Sydani Group</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontdesk.notifications.index') }}">Notifications</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontdesk.profile.index') }}">
                            <img src="{{ asset('images/profile.jpg') }}" alt="Profile Picture" class="rounded-circle" width="30">
                            Julianne Roberts
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Notification List View -->
        <div class="mt-4">
            <h2>Notifications</h2>
            @if (count($notifications) > 0)
                <ul class="list-group">
                    @foreach ($notifications as $notification)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <span class="badge badge-primary">{{ $notification['category'] }}</span>
                                    <strong>{{ $notification['title'] }}</strong>
                                    <p>{{ $notification['message'] }}</p>
                                    <small>{{ $notification['timestamp'] }}</small>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-secondary">...</button>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-center mt-5">
                    <img src="{{ asset('images/empty-box.png') }}" alt="No Notifications" width="100">
                    <p>No Notifications Yet.</p>
                </div>
            @endif
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
