@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 pt-5">
                <a href="{{ route('login') }}">
                    << Account Login</a>
                        <div class="card shadow">
                            <form action="" id="form-register">
                                <div class="card-header">
                                    Account Registration
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-12 col-sm-6 col-md-4 mb-3">
                                            <label for="">First Name:*</label>
                                            <input type="search" name="first_name" placeholder="Enter your given name"
                                                class="form-control">
                                            <div class="invalid-feedback">
                                                First Name is required.
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-sm-6 col-md-4 mb-3">
                                            <label for="">Last Name:*</label>
                                            <input type="search" name="last_name" placeholder="Enter your last name"
                                                class="form-control">
                                            <div class="invalid-feedback">
                                                Last Name is required.
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-sm-6 col-md-4 mb-3">
                                            <label for="">Username:*</label>
                                            <input type="search" name="username" placeholder="Enter your Username"
                                                class="form-control">
                                            <div class="invalid-feedback">
                                                Username is required.
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-sm-6 col-md-4 mb-3">
                                            <label for="">Password:*</label>
                                            <input type="password" name="password" placeholder="Enter your Password"
                                                class="form-control">
                                            <div class="invalid-feedback">
                                                Password is Invalid.
                                            </div>
                                        </div>
                                        <div class="form-group col-12 col-sm-6 col-md-4 mb-3">
                                            <label for="">Confirm Password:*</label>
                                            <input type="password" name="password_confirmation"
                                                placeholder="Confirm your Password" class="form-control">
                                            <div class="invalid-feedback">
                                                Password did not match.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-success">Register</button>
                                    <button type="reset" class="btn btn-danger">Reset</button>
                                </div>
                            </form>
                        </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        $(() => {
            $('#form-register').submit(function() {
                $.post("{{ route('acc.create') }}", $(this).serialize(), res => {
                    alert(res)
                    $('#form-register').trigger('reset')
                    $('form input').attr('class','form-control')
                }).fail(res => {
                    var response = JSON.parse(res.responseText)
                    $('form input').attr('class','form-control is-valid')
                    $.each(response, (i,v) =>{
                        $('form').find(`input`).eq(v).attr('class','form-control is-invalid')
                    })

                })

                return false
            });
        })
    </script>
@endsection
