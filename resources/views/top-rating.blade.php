@extends('layouts.app')

@section('content')
<section id="home">
    <h1 class="title">Top 10 Famous Authors</h1>
    <table>
        <thead>
            <tr>
                <th style="width: 20px">No</th>
                <th style="text-align: left; width: 320px">Author Name</th>
                <th>Total Voters</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($topRatings as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td style="text-align: left">{{ $item['author_name'] }}</td>
                <td>{{ $item['total_voters'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</section>
@endsection