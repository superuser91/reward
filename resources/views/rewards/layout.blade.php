<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/vendor/admin-lte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="/vendor/admin-lte/plugins/daterangepicker/daterangepicker.css" crossorigin="anonymous">
    <script src="/vendor/admin-lte/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="/vendor/admin-lte/plugins/sweetalert2/sweetalert2.all.js"></script>
    <script src="/vendor/admin-lte/plugins/datatables/jquery.dataTables.js"></script>
    <script src="/vendor/admin-lte/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
    <script src="/vendor/admin-lte/plugins/datatables-responsive/js/dataTables.responsive.js"></script>
    <script src="/vendor/admin-lte/plugins/datatables-responsive/js/responsive.bootstrap4.js"></script>
    <script src="/vendor/admin-lte/plugins/datatables-buttons/js/dataTables.buttons.js"></script>
    <script src="/vendor/admin-lte/plugins/datatables-buttons/js/buttons.bootstrap4.js"></script>
    @stack('head')

</head>

<body>
    <div class="clearfix mb-3 p-3">
        <div class="btn-group float-left" role="group" aria-label="Basic example">
            <a href="{{ route('rewards.index') }}" class="btn btn-primary">Danh sách</a>
            <a href="{{ route('rewards.create') }}" class="btn btn-success">Thêm</a>
        </div>
        <div class="float-right">
            <a href="{{ url()->current() }}" class="btn btn-info"><i class="fa-solid fa-arrows-rotate"></i></a>
        </div>
    </div>
    @if (session('status'))
        <div class="mb-3">
            <div class="alert alert-custom alert-success mx-3" role="alert">
                <div class="alert-icon">
                    <i class="fas fa-torii-gate"></i>
                </div>
                <div class="alert-text">
                    {{ session('status') }}
                </div>
            </div>
        </div>
    @endif
    <div class="content">
        @yield('content')
    </div>
    @include('ckfinder::setup')
    @stack('scripts')
</body>

</html>
