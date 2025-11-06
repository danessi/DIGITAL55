@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Lista de Clientes</h1>
        <!-- Enlace al Dashboard -->
        <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">Volver al Dashboard</a>
        <a href="{{ route('clientes.create') }}" class="btn btn-primary mb-3">Crear Cliente</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->name }}</td>
                        <td>{{ $cliente->email }}</td>
                        <td>{{ $cliente->phone }}</td>
                        <td>
                            <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
