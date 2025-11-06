@extends('layouts.app')

@section('content')
    <h1>Detalles del Pedido #{{ $order->id }}</h1>
    <p>Estado: {{ $order->status }}</p>
    <p>Cliente: {{ $order->customer->name }}</p>
    <p>Ingredientes:</p>
    <ul>
        @foreach($order->ingredients as $ingredient)
            <li>{{ $ingredient->name }}</li>
        @endforeach
    </ul>
@endsection
