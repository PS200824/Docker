@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2>Songs List</h2>
                <a class="btn btn-success" href="{{ route('songs.create') }}">Add New Song</a>
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                @endif
                <table class="table table-bordered">
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Artist</th>
                        <th>Album</th>
                        <th>Year</th>
                        <th width="280px">Action</th>
                    </tr>
                    @php $i = 0; @endphp
                    @foreach ($songs as $song)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $song->title }}</td>
                            <td>{{ $song->artist }}</td>
                            <td>{{ $song->album }}</td>
                            <td>{{ $song->year }}</td>
                            <td>
                                <form action="{{ route('songs.destroy', $song->id) }}" method="POST">
                                    <a class="btn btn-info" href="{{ route('songs.show', $song->id) }}">Show</a>
                                    <a class="btn btn-primary" href="{{ route('songs.edit', $song->id) }}">Edit</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
