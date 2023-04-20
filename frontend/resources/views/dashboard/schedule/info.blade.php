@extends('layouts.master')
@section('content')
<!-- Button trigger modal -->
<a href="/schedule" class="btn btn-success my-4"><i class="fa fa-arrow-left"></i></a>
<table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Name</th>
        <th scope="col">Email</th>
        <th scope="col">Password</th>
        <th scope="col">Role</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($users as $user)
          <tr>
            <td>{{ $loop->index + 1 }}</td>
            <td>{{ $user['name'] }}</td>
            <td>{{ $user['email'] }}</td>
            <td>{{ $user['password'] }}</td>
            <td>{{ $user['role'] }}</td>
          </tr>
      @endforeach
    </tbody>
  </table>

@endsection