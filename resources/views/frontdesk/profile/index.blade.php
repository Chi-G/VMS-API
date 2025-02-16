<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
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

        <!-- User Profile Card -->
        <div class="card mt-4">
            <div class="card-body text-center">
                <img src="{{ asset('images/' . $profile['image']) }}" alt="Profile Picture" class="rounded-circle mb-3" width="100">
                <h4>{{ $profile['name'] }}</h4>
                <span class="badge badge-primary">{{ $profile['role'] }}</span>
                <p>{{ $profile['position'] }}</p>
                <p>{{ $profile['company'] }}</p>
                <p>{{ $profile['email'] }}</p>
                <p>Employee ID: {{ $profile['employee_id'] }}</p>
                <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
