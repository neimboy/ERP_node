@extends('layouts.app')

@section('content')
    <a href="{{route('proyectos.index')}}">Volver</a>
    <form action="" method="post">
        @csrf
        <label for="">Nombre Del proyecto</label>
        <input type="text">
        <label for="">Fecha de Inicio</label>
        <input type="date" name="Fecha_Inicio">
        <label for="">Fecha fin del proyecto</label>
        <input type="date" name="Fecha_Fin">
        <label for="">Estado</label>
        <select name="">
            <option value="">Seleccione un Estado</option>
            <option value="admin">Pendiente</option>
            <option value="user">Progreso</option>
            <option value="user">Completado</option>
        </select>

        <input type="submit" value="create">
    </form>
@endsection
