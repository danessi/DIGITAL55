@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Editar Cliente</h1>
        <form action="{{ route('clientes.update', $customer->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $customer->name }}" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $customer->email }}" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ $customer->phone }}">
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Cliente</button>
        </form>
    </div>
@endsection
