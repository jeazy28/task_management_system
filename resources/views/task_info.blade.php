@extends('app')

@section('content')
    <!-- Modal Edit -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Task Modal</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger alert-dismissible fade show d-none" role="alert">
                        <strong>Error!</strong>
                        <ul id="error-list">

                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <form action="" id="form-edit">

                        <div class="form-group mb-3">
                            <label for="">Status:*</label>
                            <select name="status" id="" class="form-select">
                                @foreach ($status as $v)
                                    <option value="{{ $v->id }}" {{ $v->id == $task->status ? 'selected' : '' }}>
                                        {{ $v->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Title:*</label>
                            <input type="search" name="title" value="{{ $task->title }}" id=""
                                class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Content:*</label>
                            <textarea name="content" class="form-control" id="" rows="5">{{ $task->content }}</textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="btn-save" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <a href="{{ route('tasks') }}" class="btn btn-secondary"> Back to Home</a>
                @if (!$task->is_published)
                    <button class="btn btn-info" id="btn-publish">Publish Now</button>
                @endif

                <h1 class="pt-5">{{ $task->is_published == 0 ? '[Draft]' : '' }}{{ $task->title }}</h1>
            </div>
            <div class="col-12">
                <p class="mb-5 mt-4">{{ $task->content }}</p>
                <div class="text-end">
                    <small class="text-muted">Date Created: {{ $task->created_at }}</small><br>
                    <small class="text-muted">Status: {{ $task->TaskStatus->name }}</small><br>
                    @if ($task->has_file)
                        <small class="text-muted">Attachment: <a
                                href="{{ route('tasks.index') }}/download/{{ $file->path }}"
                                target="_blank">Download</a></small>
                    @else
                        <small class="text-muted">Attachment: None</small>
                    @endif
                    <br>
                    <br>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"> Edit</button>
                    <button class="btn btn-danger" id="btn-delete">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        $(() => {
            $('#btn-delete').click(() => {
                if (confirm('Are you sure you want to delete this Task?')) {
                    $.ajax({
                        url: "{{ route('tasks.destroy', $task->id) }}",
                        type: 'DELETE',
                        success: function(res) {
                            alert('Deleted!')
                            window.location = "{{ route('tasks') }}"
                        }
                    })

                }
            })
            $('#btn-publish').click(() => {
                if (confirm('You are abou to Publish this Task.')) {
                    $.ajax({
                        url: "{{ route('tasks.update', $task->id) }}",
                        type: 'PATCH',
                        data: {
                            publish: 1
                        },
                        success: function(res) {
                            alert(res)
                            location.reload()
                        },
                    })
                }

            })
            $('#btn-save').click(() => {
                $.ajax({
                    url: "{{ route('tasks.update', $task->id) }}",
                    type: 'PUT',
                    data: $('#form-edit').serialize(),
                    success: function(res) {
                        alert(res)
                        location.reload()
                    },
                    error: function(res) {
                        $('.alert').removeClass('d-none')
                        res = JSON.parse(res.responseText)
                        $('#error-list').empty()
                        $.each(res, (i, v) => {
                            $('#error-list').append(`<li>${v}</li>`)
                        })
                    }
                })
            })
        })
    </script>
@endsection
