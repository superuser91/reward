@extends(config('vgplay.products.layout'))

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
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
                        <label for="price">Điểm để đổi</label>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price"
                            name="price" aria-describedby="price" placeholder="Số điểm để đổi phần thưởng này"
                            value="{{ old('price', $reward->price) }}" required>
                        @error('price')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                      <label for="payment_unit">Đơn vị trao đổi</label>
                      <select name="payment_unit" id="payment_unit" class="form-control">
                          @foreach ($paymentUnits as $unit => $displayName)
                              <option value="{{ $unit }}" @if($reward->payment_unit == $unit) selected @endif>{{ $displayName }}</option>
                          @endforeach
                      </select>
                      @error('price')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </div>

                    <div class="form-group">
                        <label for="limit">Số lần đổi</label>
                        <input type="number" class="form-control @error('limit') is-invalid @enderror" id="limit"
                            name="limit" aria-describedby="limit" placeholder="Số lần đổi phần thưởng này"
                            value="{{ old('limit', $reward->limit) }}">
                        @error('limit')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="available_from">Thời gian bắt đầu đổi</label>
                        <input type="text" class="form-control @error('available_from') is-invalid @enderror"
                            id="available_from" name="available_from" aria-describedby="available_from"
                            value="{{ old('available_from', $reward->available_from) }}">
                        @error('available_from')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="available_to">Hạn cuối để đổi</label>
                        <input type="text" class="form-control @error('available_to') is-invalid @enderror"
                            id="available_to" name="available_to" aria-describedby="available_to"
                            value="{{ old('available_to', $reward->available_to) }}">
                        @error('available_to')
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
                        <img class="mw-100 mt-2 rounded" src="{{ old('picture', $reward->picture) }}"
                            id="preview-picture">

                    </div>

                     <div class="custom-control custom-checkbox my-3">
                      <input type="checkbox" class="custom-control-input" id="is_publish" name="is_publish"
                          @if (old('is_publish', $reward->is_publish)) checked @endif>
                      <label class="custom-control-label" for="is_publish">
                          Publish
                      </label>
                      @error('is_publish')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </div>

                    <div class="form-group">
                        <button class="btn btn-success">Lưu lại</button>
                        <a data-action="{{ route('rewards.destroy', $reward->id) }}"
                            class="btn btn-danger btn-delete float-right">
                            <i class="fas fa-trash"></i>
                            Xoá</a>
                    </div>
                </form>
            </div>
        </div>
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
