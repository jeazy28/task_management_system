@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-10 col-md-6 col-xl-4 m-auto mt-5">

                <div class="card shadow">

                    <div class="card-body">
                        <h1 class="text-center mb-0">LOGIN</h1>
                        <div class="text-center">
                            <small class="text-muted">Task Management System</small>
                        </div>
                        <form action="" class="mt-3" id="form-login">
                            <div class="form-group mb-3">
                                <label for="">Username</label>
                                <input type="search" name="username" placeholder="Enter your Username"
                                    class="form-control">
                            </div>
                            <div class="form-group mb-4">
                                <label for="">Password</label>
                                <input type="password" name="password" placeholder="Enter your Password"
                                    class="form-control">
                            </div>
                            <button class="btn btn-success w-100">LOGIN</button>
                            <div class="text-center">
                                <a href="{{route('register')}}">Account Registration</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        $(()=>{
            $('#form-login').submit(function(){
                $.post("{{route('acc.login')}}",$(this).serialize(),res=>{
                    location.reload()
                }).fail(res=>{
                    alert(res.responseText)
                })
                return false
            })
        })
    </script>
@endsection
