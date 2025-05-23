@extends('layouts.app')

@section('content')
<section id="home">
    <h1 class="title">Top 10 Famous Authors</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Author</th>
                <th>Voter</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($topRatings as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item['author_name'] }}</td>
                <td>{{ $item['total_voters'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</section>
@endsection