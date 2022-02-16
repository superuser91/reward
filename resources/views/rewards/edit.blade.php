@extends('vgplay::rewards.layout')

@section('content')
    <div class="container-fluid">
        <form action="{{ route('rewards.update', $reward->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="name">Tên giải thưởng</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    aria-describedby="name" placeholder="Tên" value="{{ old('name', $reward->name) }}" required>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="reward">Phần thưởng</label>
                <select name="reward" id="reward" class="form-control" required>
                    @foreach ($rewardables as $item)
                        <option value="{{ get_class($item) }}::{{ $item->id }}"
                            @if ($reward->rewardable_type == get_class($item) && $reward->rewardable_id == $item->id) selected @endif>
                            {{ $item->name }} ({{ substr(strrchr(get_class($item), '\\'), 1) }})
                        </option>
                    @endforeach
                </select>
                @error('reward')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="price">Điểm để đổi</label>
                <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price"
                    aria-describedby="price" placeholder="Số điểm để đổi phần thưởng này"
                    value="{{ old('price', $reward->price) }}" required>
                @error('price')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="point_milestone">Tổng điểm đạt mốc tối thiểu</label>
                <input type="number" class="form-control @error('point_milestone') is-invalid @enderror"
                    id="point_milestone" name="point_milestone" aria-describedby="point_milestone"
                    placeholder="Mốc điểm để đổi phần thưởng này"
                    value="{{ old('point_milestone', $reward->point_milestone) }}">
                <small class="form-text text-muted">Bỏ trống nếu quà đổi tự do không theo từng mốc.</small>
                @error('point_milestone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="min_vxu">Nạp vxu tối thiểu</label>
                <input type="number" class="form-control @error('min_vxu') is-invalid @enderror" id="min_vxu" name="min_vxu"
                    aria-describedby="min_vxu" placeholder="Vxu tối thiểu để đổi phần thưởng này"
                    value="{{ old('min_vxu', $reward->min_vxu) }}">
                <small class="form-text text-muted">Bỏ trống nếu không yêu cầu nạp tiền.</small>
                @error('min_vxu')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="purchaseable_amount">Số lần đổi</label>
                <input type="number" class="form-control @error('purchaseable_amount') is-invalid @enderror"
                    id="purchaseable_amount" name="purchaseable_amount" aria-describedby="purchaseable_amount"
                    placeholder="Số lần đổi phần thưởng này"
                    value="{{ old('purchaseable_amount', $reward->purchaseable_amount) }}" required>
                @error('purchaseable_amount')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="picture">Ảnh minh hoạ</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="ckfinder-picture" name="picture"
                        value="{{ old('picture', $reward->picture) }}">
                    <span class="input-group-append">
                        <button type="button" class="btn btn-info"
                            onclick="selectFileWithCKFinder('picture')">Browse</button>
                    </span>
                </div>
                <img class="mw-100 mt-2 rounded" src="{{ old('picture', $reward->picture) }}" id="preview-picture">

            </div>

            <div class="form-group">
                <label for="stats">Chỉ số</label>
                <textarea name="stats" id="stats" class="form-control"
                    rows="10">{{ json_encode(old('stats', $reward->stats)) }}</textarea>
            </div>

            <div class="custom-control custom-checkbox my-3">
                <input type="checkbox" class="custom-control-input" id="is_personal" name="is_personal"
                    @if (old('is_personal', $reward->is_personal)) checked @endif>
                <label class="custom-control-label" for="is_personal">
                    Nhận quà theo mốc điểm cá nhân.
                </label>
                @error('is_personal')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <button class="btn btn-success">Lưu lại</button>
                <a data-action="{{ route('admin.rewards.destroy', $reward->id) }}"
                    class="btn btn-danger btn-delete float-right">
                    <i class="fas fa-trash"></i>
                    Xoá</a>
            </div>
        </form>
    </div>
    <form method="POST" id="form-delete">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            let action = $(this).data('action');
            let confirmed = confirm("Bạn có chắc chắc muốn xoá?");
            if (confirmed) {
                $('#form-delete').attr('action', action);
                $('#form-delete').submit();
            }
        });

        $('#btn-add-stat').click(function(e) {
            $('#stats').append(`
            <div class="stat row mb-3">
                <div class="col">
                    <input type="text" name="stats[name][]" class="form-control" placeholder="Tên chỉ số">
                </div>
                <div class="col-1">
                    <span class="btn text-danger btn-remove-stat"><i class="fas fa-times"></i></span>
                </div>
            </div>
        `);
        })

        $(document).on('click', '.btn-remove-stat', function() {
            $(this).closest('.stat').remove();
        })
    </script>
    <script>
        function selectFileWithCKFinder(elementId) {
            CKFinder.modal({
                chooseFiles: true,
                width: 800,
                height: 600,
                onInit: function(finder) {
                    finder.on('files:choose', function(evt) {
                        var file = evt.data.files.first();
                        var output = document.getElementById('ckfinder-' + elementId);
                        var preview = document.getElementById('preview-' + elementId);
                        if (output) {
                            output.value = file.getUrl();
                        }
                        if (preview) {
                            preview.src = file.getUrl();
                        }
                    });

                    finder.on('file:choose:resizedImage', function(evt) {
                        var output = document.getElementById(elementId);
                        output.value = evt.data.resizedUrl;
                    });
                }
            });
        }
    </script>
@endpush
