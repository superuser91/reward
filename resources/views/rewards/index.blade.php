@extends(config('vgplay.products.layout'))

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <table class="table table-striped" id="kt_datatable">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tên</th>
                            <th scope="col">Ảnh</th>
                            <th scope="col">Loại quà</th>
                            <th scope="col">Điểm đổi</th>
                            <th scope="col">Giới hạn đổi</th>
                            <th scope="col">Đơn vị</th>
                            <th scope="col">Cập nhật</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rewards as $item)
                            <tr>
                                <th>{{ $item->id }}</th>
                                <td>{{ $item->name }}</td>
                                <td><img src="{{ $item->picture ?? '' }}" alt="..." class="img-thumbnail"
                                        style="max-width:75px">
                                </td>
                                <td>{{ substr(strrchr($item->purchaseable_type, '\\'), 1) }}</td>
                                <td>
                                    {{ $item->store_id }}
                                </td>
                                <td>{{ number_format($item->price) }}</td>
                                <td>{{ $item->limit ?? '--' }}</td>
                                <td>
                                    {{ $item->payment_unit }}
                                </td>
                                <td>
                                    <a href="{{ route('rewards.edit', $item->id) }}"
                                        class="btn btn-success">{{ __('Sửa') }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
