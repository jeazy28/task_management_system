@extends('app')

@section('css')
    <style>
        #task-list div:hover>.d-flex {
            background: #ddd;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <!-- Modal for New Task -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Task Modal</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger alert-dismissible fade show d-none" role="alert">
                        <strong>Error!</strong>
                        <ul id="error-list">

                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <form action="" id="form-task-add" enctype="multipart/form-data">
                        <input type="hidden" name="type" id="type">
                        <div class="form-group mb-3">
                            <label for="">Title:*</label>
                            <input type="search" name="title" id="" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Content:*</label>
                            <textarea name="content" class="form-control" id=""></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="">File:</label>
                            <input type="file" name="task_file" class="form-control">
                            <small class="text-muted">Note: Only png, jpg and jpeg are allowed to upload.</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="btn-draft" class="btn btn-primary">Save as Draft</button>
                    <button type="button" id="btn-create" class="btn btn-success">Create Now</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5 mb-5">
        <div class="row">
            <h1>My Tasks</h1>
            <div class="col-12">
                <div class="text-end mb-2">
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        + Create New Task
                    </button>
                </div>
                <div class="col-12 mb-3">
                    <div class="row">
                        <h3>Filter by:</h3>
                        <div class="col-12">
                            <div class="row">
                                <div class="form-group mb-3 col-12 col-sm-4">
                                    <label for="">Title:</label>
                                    <input type="search" name="" id="title" placeholder="Enter any title here"
                                        class="form-control">
                                </div>
                                <div class="form-group mb-3 col-12 col-sm-4">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="">Status:</label>
                                            <select name="" id="status" class="form-select">
                                                <option value="1">To-do</option>
                                                <option value="2">In-progress</option>
                                                <option value="3">Done</option>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <label for="">Page</label>
                                            <input type="number" name="" value="10" id="render"
                                                class="form-control">
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group mb-3 col-12 col-sm-4">
                                    <label for="">Sory by:</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <select name="" id="sortName" class="form-select">
                                                <option value="title">Title</option>
                                                <option value="id">Date</option>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <select name="" id="sortBy" class="form-select">
                                                <option value="asc">ASC</option>
                                                <option value="desc">DESC</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <div class="text-end">
                            <button class="btn btn-primary" id="btn-filter">Set Filter</button><br>
                            <small class="text-muted"><label><input type="checkbox" name="" id="draft"> Show
                                    Draft Only</label></small>

                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <small id="rendered" class="text-muted"></small>
                </div>
                <div class="card shadow overflow-auto" style="height:400px;">

                    <div class="card-body">

                        <div class="row" id="task-list">
                            <div class="text-center">
                                <div class="spinner-grow text-secondary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('javascript')
    <script>
        let nextURL;
        let load = 1;
        let filter = {
            status: 1,
            render: 10,
            sortName: 'id',
            sortBy: 'asc',
            draft: 0
        }
        $(() => {
            $('#draft').change(function() {
                if ($(this).prop("checked")) {
                    filter.draft = 1
                } else {
                    filter.draft = 0
                }
            })
            $('#btn-filter').click(() => {

                filter.render = $('#render').val()
                filter.title = $('#title').val()
                filter.sortName = $('#sortName').val()
                filter.sortBy = $('#sortBy').val()
                filter.status = $('#status').val()

                $('#task-list').html(`<div class="text-center">
                                <div class="spinner-grow text-secondary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>`)
                $.get("{{ route('tasks.index') }}", filter, res => {
                    $('#task-list').empty()
                    if (res.data.length == 0) {
                        $('#task-list').html(`<div class='text-center'> No Tasks found at this time.</div>`)
                    } else {
                        rowData(res)
                    }
                    load++
                })
            })

            $('.card').scroll(function() {
                var containerHeight = $(this)[0].scrollHeight - 400
                var currPos = Math.ceil($(this).scrollTop())

                if (currPos >= containerHeight && nextURL != null) {
                    if (load) {
                        load--

                        $.get(nextURL, filter, res => {
                            rowData(res)
                            load++
                        })
                    }


                }
            })
            $('#btn-create').click(() => {
                createTask(1)
            })
            $('#btn-draft').click(() => {
                createTask(0)
            })

            $.get("{{ route('tasks.index') }}", filter, res => {
                $('#task-list').empty()
                rowData(res)
            })

            $('#task-list').on('click', `> div`, function() {
                window.location = "{{ route('tasks.index') }}/" + $(this).data('id')
            })
        })

        function createTask(type) {
            $('#type').val(type)
            const formData = new FormData($('#form-task-add')[0]);

            $.ajax({
                url: "{{ route('tasks.store') }}", // server endpoint
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (!$('.alert').hasClass('d-none')) {
                        $('.alert').addClass('d-none')
                    }
                    $('#form-task-add').trigger('reset')
                    alert(res)
                },
                error: function(res) {
                    $('.alert').removeClass('d-none')
                    res = JSON.parse(res.responseText)
                    $('#error-list').empty()
                    $.each(res, (i, v) => {
                        $('#error-list').append(`<li>${v}</li>`)
                    })
                }
            });
        }

        function rowData(res) {

            nextURL = res.links.next
            $('#rendered').html('Rendered ' + (parseInt(res.data.length) + parseInt($('#task-list > div').length)))
            $.each(res.data, (i, v) => {
                $('#task-list').append(`
                    <div class="col-12 col-sm-6 mb-3" data-id="${v.id}">
                                <div class="d-flex justify-content-between border p-3">
                                    <div>
                                        <b>Task Name:</b> ${v.title}
                                        <br>
                                        <b>Status:</b> ${v.task_status.name}
                                    </div>
                                    <div class="text-muted">
                                        Date: ${v.created_at}
                                    </div>
                                </div>
                            </div>
                    `)
            })
        }
    </script>
@endsection
