<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class BookService
{
    public function list()
    {
        try {
            $books = Book::all();
            return json_encode($books);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to list books', 'message' => $e->getMessage()], 500);
        }
    }

    public function listOne($id)
    {
        try {
            $book = Book::findOrFail($id);
            return json_encode($book);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Book not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve book', 'message' => $e->getMessage()], 500);
        }
    }

    public function create($data)
    {
        try {
            $book = Book::create([
                'title' => $data['title'],
                'author' => $data['author'],
                'isbn' => $data['isbn'],
                'gender' => $data['gender'],
            ]);

            $qrPath = $this->saveQr($book->id);
            $book->update(['qr' => $qrPath]);

            return response()->json(['success' => 'Book created successfully', 'book' => $book], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create book', 'message' => $e->getMessage()], 500);
        }
    }

    private function saveQr($bookId)
    {
        try {
            $url = env('BASE_URL', 'http://127.0.0.1:8000') . '/books/show/' . $bookId;
            $image = QrCode::format('png')->generate($url);
            $output_file = 'public/qr/books/' . time() . '.png';
            Storage::disk('local')->put($output_file, $image);

            $prefix = 'public/';
            if (substr($output_file, 0, strlen($prefix)) == $prefix) {
                $output_file = substr($output_file, strlen($prefix));
            }

            return $output_file;
        } catch (Exception $e) {
            throw new Exception('Failed to generate QR code: ' . $e->getMessage());
        }
    }

    public function edit($data, $id)
    {
        try {
            $book = Book::findOrFail($id);
            $book->update([
                'title' => $data['title'],
                'author' => $data['author'],
                'isbn' => $data['isbn'],
                'gender' => $data['gender'],
            ]);

            return response()->json(['success' => 'Book updated successfully', 'book' => $book], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Book not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update book', 'message' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $book = Book::findOrFail($id);
            $book->delete();

            return response()->json(['success' => 'Book deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Book not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete book', 'message' => $e->getMessage()], 500);
        }
    }

    public function filter($title, $author, $gender)
    {
        try {
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

            $books = $query->get();
            return json_encode($books);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to filter books', 'message' => $e->getMessage()], 500);
        }
    }
}
