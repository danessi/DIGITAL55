@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Crear Cliente</h1>
        <!-- Enlace al Dashboard -->
        <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">Volver al Dashboard</a>
        <form action="{{ route('clientes.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="phone" name="phone">
            </div>
            <button type="submit" class="btn btn-primary">Crear Cliente</button>
        </form>
    </div>
@endsection
