@extends('layouts.app')

@section('content')
<section id="addRating">
    <h1 class="title">Add Rating</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('rating.store') }}" method="post">
        @csrf
        <div>
            <label for="author">Book Author</label>
            <input type="text" id="authorInputTrigger" list="author-list" placeholder="Type or select the author name">
            <input type="hidden" name="author" id="authorInput">
            <datalist id="author-list">
                @foreach ($authors as $author)
                <option id="{{ $author['name'] }}" value="{{ $author['name'] }}" data-author-id="{{ $author['id'] }}" />
                @endforeach
            </datalist>
        </div>

        <div>
            <label for="name">Book Name</label>
            <select name="book" id="bookLists" required>
                <option value="" disabled selected>-- select book --</option>
            </select>
        </div>

        <div>
            <label for="rating">Rating</label>
            <input type="number" name="rating" min="0" max="10" placeholder="Add rating from 0 to 10">
        </div>

        <button type="submit">Submit</button>
    </form>
</section>
@endsection

@section('script')
<script>
    document.getElementById('authorInputTrigger').addEventListener('keyup', function() {
        // reset value first
        document.getElementById("authorInput").value = "";
        let bookListsEl = document.getElementById('bookLists');
        let bookListsDefaultEl = bookListsEl.firstElementChild;
        bookListsEl.innerHTML = "";
        bookListsEl.append(bookListsDefaultEl)
        
        const authorLists = document.getElementById("author-list");
        const selectedEl = authorLists.options.namedItem(this.value);
        
        if (selectedEl == null || selectedEl == undefined) return;

        document.getElementById("authorInput").value = selectedEl.dataset.authorId;
        
        const authorId = document.getElementById("authorInput").value; 
        fetch(`{{ url('api') }}/authors/${authorId}/books`)
            .then(res => res.json())
            .then(data => {
                data.data.forEach(book => {
                    const optEl = document.createElement('option');
                    optEl.value = book.id;
                    optEl.innerText = book.name;

                    bookListsEl.append(optEl)
                });
            })
    })
</script>
@endsection