@extends('layouts.admin')
@section('title','Usuarios')
@section('page-title','Gestión de Usuarios')

@section('content')
<div class="adm-table-wrap reveal">
  <table class="adm-table">
    <thead>
      <tr><th>#</th><th>Nombre</th><th>Email</th><th>DNI</th><th>Rol</th><th>Registro</th></tr>
    </thead>
    <tbody>
      @foreach($usuarios as $u)
        <tr>
          <td style="color:var(--c-muted)">{{ $u->id }}</td>
          <td style="font-weight:600">{{ $u->nombre }}</td>
          <td style="color:var(--c-muted)">{{ $u->email }}</td>
          <td>{{ $u->dni ?? '—' }}</td>
          <td>
            <span class="badge-tt {{ match($u->rol){
              'admin_general'        => 'badge-danger',
              'admin_sistema'        => 'badge-purple',
              'cajero'               => 'badge-success',
              'coordinador_delivery' => 'badge-warning',
              'barista'              => 'badge-info',
              default                => 'badge-info'} }}">
              {{ $u->rol_label }}
            </span>
          </td>
          <td style="color:var(--c-muted);font-size:0.8rem">{{ $u->created_at->format('d/m/Y') }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
<div class="mt-3">{{ $usuarios->links('pagination::bootstrap-5') }}</div>
@endsection
