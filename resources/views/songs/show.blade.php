@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2> Show Song</h2>
                <div class="form-group">
                    <strong>Title:</strong>
                    {{ $song->title }}
                </div>
                <div class="form-group">
                    <strong>Artist:</strong>
                    {{ $song->artist }}
                </div>
                <div class="form-group">
                    <strong>Album:</strong>
                    {{ $song->album }}
                </div>
                <div class="form-group">
                    <strong>Year:</strong>
                    {{ $song->year }}
                </div>
            </div>
        </div>
    </div>
@endsection
