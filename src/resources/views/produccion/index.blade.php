@extends('layouts.app')
@section('title','Inicio')

@section('content')
    <a href="{{route('proyectos.create')}}">Crear un nuevo Proyecto</a>
    @forelse ($proyectos as $proyecto)
        <ul>
            <li>
                <a href="#">{{$proyecto->Nombre}}</a>
            </li>
        </ul>
    @empty
        <strong>Aun no hay datos</strong>
    @endforelse
@endsection
