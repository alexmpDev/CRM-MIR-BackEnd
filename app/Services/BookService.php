<?php

namespace App\Services;

use App\Models\Book;

class BookService
{
    public function list()
    {

        $books = Book::all();
        return json_encode($books);
    }

    public function listOne($id)
    {

        $book = Book::where('id', $id)->get();
        return json_encode($book);
    }

    public function create($data)
    {

        Book::create([

            'title' => $data['title'],
            'author' => $data['author'],
            'isbn' => $data['isbn'],
            'gender' => $data['gender'],
        ]);
    }

    public function edit($data, $id)
    {

        $book = Book::find($id);
        $book['title'] = $data['title'];
        $book['author'] = $data['author'];
        $book['isbn'] = $data['isbn'];
        $book['gender'] = $data['gender'];

        $book->save();
    }

    public function delete($id)
    {

        $book = Book::find($id);
        if (isset($book)) {
            $book->delete();
        } else {
            return 'No hay libro con esta id';
        }
    }

    public function filter($title, $author, $gender)
    {
        $query = Book::query();

        if (isset($title)) {
            $query->where('title', 'like', '%' . $title . '%');
        }

        if (isset($author)) {
            $query->where('author', 'like', '%' . $author . '%');
        }

        if (isset($gender)) {
            $query->where('gender', 'like', '%' . $gender . '%');
        }

        $book = $query->get();


        return json_encode($book);
    }
}
