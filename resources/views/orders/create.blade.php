@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Crear Nuevo Pedido</h1>
        <!-- Enlace al Dashboard -->
        <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">Volver al Dashboard</a>
        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="customer_id">Cliente</label>
                <select name="customer_id" id="customer_id" class="form-control" required>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="status">Estado</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="pending">Pendiente</option>
                    <option value="completed">Completado</option>
                </select>
            </div>

            <div class="form-group">
                <label for="ingredients">Ingredientes</label>
                <select name="ingredients[]" id="ingredients" class="form-control" multiple required>
                    @foreach($ingredients as $ingredient)
                        <option value="{{ $ingredient->id }}">{{ $ingredient->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Crear Pedido</button>
        </form>
    </div>
@endsection
