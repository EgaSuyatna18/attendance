@extends('layouts.master')
@section('content')
<!-- Button trigger modal -->
<button type="button" class="btn btn-success my-3" data-bs-toggle="modal" data-bs-target="#addModal">
    <i class="fa fa-plus"></i>
</button>
<table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Name</th>
        <th scope="col">Email</th>
        <th scope="col">Role</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($users as $user)
          <tr>
            <td>{{ $loop->index + 1 }}</td>
            <td>{{ $user['name'] }}</td>
            <td>{{ $user['email'] }}</td>
            <td>{{ $user['role'] }}</td>
            <td>
              @if (session()->get('auth')['id'] !== $user['id'])
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal"
                  onclick="setData('{{ $user['id'] }}', '{{ $user['name'] }}', '{{ $user['email'] }}', '{{ $user['password'] }}', '{{ $user['role'] }}')">
                  <i class="fa fa-edit"></i>
                </button>
                <form action="/user/{{ $user['id'] }}" method="post" class="d-inline">
                  @csrf
                  @method('delete')
                  <button class="btn btn-danger" onclick="return confirm('Delete User?')"><i class="fa fa-trash"></i></button>
                </form>
              @else
                <p class="d-inline-block alert alert-info m-0 p-0">Disabled</p>
              @endif
            </td>
          </tr>
      @endforeach
    </tbody>
  </table>

  <!-- Modal -->
  <div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="addModalLabel">Add Modal</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="/user" method="post" id="addForm">
            @csrf
            <div class="mb-3">
              <label>Name</label>
              <input type="text" class="form-control" name="name" placeholder="Full name">
            </div>
            <div class="mb-3">
              <label>Email</label>
              <input type="email" class="form-control" name="email" placeholder="Email">
            </div>
            <div class="mb-3">
              <label>Password</label>
              <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <div class="mb-3">
              <label>Password Confirmation</label>
              <input type="password" class="form-control" name="password_confirmation" placeholder="Password Confirmation">
            </div>
            <div class="mb-3">
              <label>Role</label>
              <select name="role" class="form-control">
                <option value="admin">Admin</option>
                <option value="lecturer">Lecturer</option>
                <option value="user">User</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success" form="addForm">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Edit -->
  <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="editModalLabel">Edit Modal</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="post" id="editForm">
            @csrf
            @method('put')
            <div class="mb-3">
              <label>Name</label>
              <input type="text" class="form-control" name="name" placeholder="Full name" id="editName">
            </div>
            <div class="mb-3">
              <label>Email</label>
              <input type="email" class="form-control" name="email" placeholder="Email" id="editEmail">
            </div>
            <div class="mb-3">
              <label>Password</label>
              <input type="password" class="form-control" name="password" id="editPassword" placeholder="Password">
            </div>
            <div class="mb-3">
              <label>Password Confirmation</label>
              <input type="password" class="form-control" name="password_confirmation" id="editPassCon" placeholder="Password Confirmation">
            </div>
            <div class="mb-3">
              <label>Role</label>
              <select name="role" class="form-control" id="editRole">
                <option value="admin">Admin</option>
                <option value="lecturer">Lecturer</option>
                <option value="user">User</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success" form="editForm">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    function setData(id, name, email, password, role) {
      editForm.action = '/user/' + id;
      editName.value = name;
      editEmail.value = email;
      editPassword.value = password;
      editPassCon.value = password;
      editRole.value = role;
    }
  </script>
@endsection