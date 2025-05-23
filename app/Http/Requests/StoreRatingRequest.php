<?php

namespace App\Http\Requests;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StoreRatingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'author' => [
                'required',
                'integer',
                Rule::exists(Author::class, 'id')
            ],
            'book' => [
                'required',
                'integer',
                Rule::exists(Book::class, 'id')->where('author_id', $this->input('author'))
            ],
            'rating' => ['required', 'integer', 'min:1', 'max:10']
        ];
    }

    // public function withValidator(Validator $validator)
    // {
    //     $validator->after(function (Validator $validator) {
    //         $authorId = $this->input('author');
    //         $bookId = $this->input('book');

    //         if ($authorId && $bookId)
    //         {
    //             $exists = Book::where(['author_id' => $authorId, 'id' => $bookId])->exists();

    //             if (false === $exists)
    //                 $validator->errors()->add('book', 'Selected book is not belongs to the author.');
    //         }
    //     });
    // }
}
