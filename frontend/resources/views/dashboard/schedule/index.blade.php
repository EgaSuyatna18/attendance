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
        <th scope="col">Date</th>
        <th scope="col">Start Hour</th>
        <th scope="col">End Hour</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($schedules['data'] as $schedule)
          <tr>
            <td>{{ $loop->index + 1 }}</td>
            <td>{{ $schedule['name'] }}</td>
            <td>{{ $schedule['date'] }}</td>
            <td>{{ $schedule['start'] }}</td>
            <td>{{ $schedule['end'] }}</td>
            <td>
              <a href="/schedule/{{ $schedule['id'] }}/detail" class="btn btn-info"><i class="fa fa-info"></i></a>
              <form action="/schedule/{{ $schedule['id'] }}" method="post" class="d-inline">
                @csrf
                @method('delete')
                <button class="btn btn-danger" onclick="return confirm('Delete Schedule?')"><i class="fa fa-trash"></i></button>
              </form>
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
          <form action="/schedule" method="post" id="addForm">
            @csrf
            <div class="mb-3">
              <label>Name</label>
              <input type="text" class="form-control" name="name" placeholder="Schedule Name">
            </div>
            <div class="mb-3">
              <label>Date</label>
              <input type="date" class="form-control" name="date" placeholder="Schedule Date">
            </div>
            <div class="mb-3">
              <label>Start</label>
              <input type="time" class="form-control" name="start" placeholder="Schedule start hour">
            </div>
            <div class="mb-3">
              <label>end</label>
              <input type="time" class="form-control" name="end" placeholder="Schedule end hour">
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

@endsection