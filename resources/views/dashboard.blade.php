@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h4>Bienvenido a la Pizzería</h4>
                </div>
                <div class="card-body">
                    <p class="lead">¡Hola, {{ Auth::user()->name }}! Estás logueado correctamente.</p>
                    <p>Este es el dashboard donde puedes ver todas las acciones que puedes realizar.</p>

                    <!-- Opciones para interactuar con la aplicación -->
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action">Ver Menú</a>
                        <a href="{{ route('orders.index') }}"  class="list-group-item list-group-item-action">Gestionar Pedidos</a>
                        <a href="{{ route('clientes.index') }}" class="list-group-item list-group-item-action">Ver Clientes</a>
                    </div>
                    <br/>
                    <h2>Acciones disponibles:</h2>
                    <hr>
                    <!-- Enlaces para Clientes -->
                    <div class="mt-4">
                        <h3>Gestión de Clientes</h3>
                        <ul>
                            <li><a href="{{ route('clientes.create') }}" class="btn btn-primary">Crear Cliente</a></li>
                            <br/>
                            <li><a href="{{ route('clientes.index') }}" class="btn btn-secondary">Ver Clientes</a></li>
                        </ul>
                    </div>

                    <!-- Enlaces para Pedidos -->
                    <div class="mt-4">
                        <h3>Gestión de Pedidos</h3>
                        <ul>
                            <li><a href="{{ route('orders.create') }}" class="btn btn-primary">Crear Pedido</a></li>
                            <br/>
                            <li><a href="{{ route('orders.index') }}" class="btn btn-secondary">Ver Pedidos</a></li>
                        </ul>
                    </div>

                    <!-- Botón para cerrar sesión -->
                    {{--                     
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100">Cerrar sesión</button>
                    </form> 
                    --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
