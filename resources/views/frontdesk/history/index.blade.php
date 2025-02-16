<!DOCTYPE html>
<html>
<head>
    <title>History Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <!-- Navigation Bar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Sydani Group</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <img src="{{ asset('images/profile.jpg') }}" alt="Profile Picture" class="rounded-circle" width="30">
                            Julianne Roberts
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Filtering & Selection Controls -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action active">Pre-Registered Visitors</a>
                    <a href="#" class="list-group-item list-group-item-action">Walk-in Visitors</a>
                </div>
            </div>
            <div class="col-md-9">
                <div class="form-inline mb-3">
                    <label for="month" class="mr-2">Month:</label>
                    <select id="month" class="form-control mr-3" onchange="filterHistory()">
                        <option value="">All</option>
                        @foreach (range(1, 12) as $month)
                            <option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 1)) }}</option>
                        @endforeach
                    </select>

                    <label for="visitor_type" class="mr-2">Visitor Type:</label>
                    <select id="visitor_type" class="form-control" onchange="filterHistory()">
                        <option value="all">All</option>
                        <option value="individual">Individual</option>
                        <option value="group">Group</option>
                    </select>
                </div>

                <!-- Visitor Records Table -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact Information</th>
                            <th>Email Address</th>
                            <th>Address</th>
                            <th>Host Name</th>
                            <th>Visit Date</th>
                            <th>Special Requirements</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($visits as $date => $dailyVisits)
                            <tr>
                                <td colspan="7" class="bg-light">{{ $date }}</td>
                            </tr>
                            @foreach ($dailyVisits as $visit)
                                <tr>
                                    <td>{{ $visit->visitor->name }}</td>
                                    <td>{{ $visit->visitor->contact_number }}</td>
                                    <td>{{ $visit->visitor->email }}</td>
                                    <td>{{ $visit->visitor->address }}</td>
                                    <td>{{ $visit->host->name }}</td>
                                    <td>{{ $visit->visit_date->format('d/m/Y H:i') }}</td>
                                    <td>{{ $visit->special_requirements }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        function filterHistory() {
            const month = document.getElementById('month').value;
            const visitorType = document.getElementById('visitor_type').value;
            window.location.href = `?month=${month}&visitor_type=${visitorType}`;
        }
    </script>
</body>
</html>
