@extends('layouts.app')

@section('content')
<section id="home">
    @session('success')
    <p style="margin-bottom: 16px; color: green">{{ session('success') }}</p>
    @endsession

    <h1 class="title">Book Lists</h1>

    <div id="tableFilter">
        <form action="" method="get" id="search">
            <input value="{{ $_GET['search'] ?? '' }}" type="text" name="search"
                placeholder="Type book name or author.." autofocus>
            <button type="submit">Submit</button>
            @if (isset($_GET['search']))
            <a href="{{ route('home') }}">Reset</a>
            @endif
        </form>

        <div style="display: flex; align-items: center; gap: 10px">
            <div>
                List Shown :
            </div>
            <select name="sort" id="sortInput">
                <option value="10" <?=(isset($_GET['shown']) && $_GET['shown']==10) ? 'selected' : '' ?>>10</option>
                <option value="25" <?=(isset($_GET['shown']) && $_GET['shown']==25) ? 'selected' : '' ?>>25</option>
                <option value="50" <?=(isset($_GET['shown']) && $_GET['shown']==50) ? 'selected' : '' ?>>50</option>
                <option value="100" <?=(isset($_GET['shown']) && $_GET['shown']==100) ? 'selected' : '' ?>>100</option>
            </select>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Title</th>
                <th>Category</th>
                <th>Author</th>
                <th>Average Rating</th>
                <th>Voter</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($books['data'] as $book)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $book['book_name'] }}</td>
                <td>{{ $book['book_category_name'] }}</td>
                <td>{{ $book['author_name'] }}</td>
                <td>{{ $book['avg_rating'] }}</td>
                <td>{{ $book['total_voters'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6">Tidak ada data.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="display:flex; justify-content: space-between; margin-top: 30px">
        @if ($books['prev_page_url'])
        <a href="{{ $books['prev_page_url'] }}">Prev Page</a>
        @endif

        @if ($books['next_page_url'])
        <a href="{{ $books['next_page_url'] }}">Next Page</a>
        @endif
    </div>
</section>
@endsection

@section('script')
<script type="text/javascript">
    document.getElementById("sortInput").addEventListener('change', function() {
        const queryParams = new URL(window.location.href).searchParams;

        if (queryParams.has('shown')) queryParams.delete('shown');

        queryParams.append('shown', this.value);
        window.location = window.location.pathname + '?' + queryParams.toString();
    }); 
</script>
@endsection