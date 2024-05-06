<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorPNG;
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

        $book = Book::create([

            'title' => $data['title'],
            'author' => $data['author'],
            'isbn' => $data['isbn'],
            'gender' => $data['gender'],
        ]);

        $barcodePath = $this->saveBarcode($book->id);

        $book->update(['barcode' => $barcodePath]);
    }

    private function saveBarcode($bookId){
        $url = 'http://127.0.0.1:8000/api/books/' . $bookId;
        $generator = new BarcodeGeneratorPNG();
        $generator->useImagick();
        $barcode = $generator->getBarcode($url, $generator::TYPE_CODE_128);
        $output_file = 'public/barcode/' . time() . '.png';
        Storage::disk('local')->put($output_file, $barcode); 
        return $output_file;
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
