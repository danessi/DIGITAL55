@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h4>Login</h4>
                </div>
                <div class="card-body">
                    <!-- Muestra errores de validación si los hay -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <!-- Formulario de login -->
                    <form action="{{ url('/login') }}" method="POST">
                        @csrf
                        <!-- Email -->
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                        </div>

                        <!-- Password -->
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>

                        <!-- Submit -->
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
