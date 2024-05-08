<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorPNG;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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

      

        //version con qr
         $qrPath = $this->saveQr($book->id);

         $book->update(['qr' => $qrPath]);
 
    }

    private function saveQr($bookId){
        $url = 'http://127.0.0.1:8000/api/books/' . $bookId;
        $image = QrCode::format('png')->generate($url);
        $output_file = 'public/qr/books/' . time() . '.png';
        Storage::disk('local')->put($output_file, $image);
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
