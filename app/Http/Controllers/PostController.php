<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Library\HttpRequest;
use Log;

class PostController extends Controller
{
    CONST ENDPOINT = 'https://jsonplaceholder.typicode.com/';
    CONST POSTS_API = 'posts';
    CONST AUTHORS_API = 'users';
    CONST COMMENTS_API = 'comments';

    public function index(Request $request) {

        $status = self::STATUS_OK;

        try 
        {
            $start = intval($request->start);
            $size = intval($request->size);
            $posts = [];
            $authors = [];
            $comments = [];

            //-- Get posts
            $resultPosts = HttpRequest::get(self::ENDPOINT . self::POSTS_API);
            if($resultPosts['success']) 
            {
                $posts = $resultPosts['data'];

                if(count($posts) < $start) 
                {
                    return response()->json(['success' => false, 'message' => 'No results'], self::STATUS_NOT_FOUND);
                }
            }
            else 
            {
                return response()->json([], self::STATUS_INTERNAL_SERVER_ERROR);
            }

            //-- Get authors
            $resultAuthors = HttpRequest::get(self::ENDPOINT . self::AUTHORS_API);
            if($resultAuthors['success']) {
                $authors = $resultAuthors['data'];
            }
            else 
            {
                return response()->json([], self::STATUS_INTERNAL_SERVER_ERROR);
            }

            $paginatedList = array_slice($posts, ($start + 1), $size);
            $result = [];

            foreach($paginatedList as $post) 
            {                
                $author = $this->getAuthorById($post['userId'], $authors);
                $urlComments = self::ENDPOINT . 'posts/'.$post['id'].'/comments';
                $comments = [];

                $resultComments = HttpRequest::get($urlComments);
                if($resultComments['success']) {
                    $comments = $resultComments['data'];
                }
                else 
                {
                    return response()->json([], self::STATUS_INTERNAL_SERVER_ERROR);
                }

                $post['author'] = $author;
                $post['comments'] = $comments;
                $result[] = $post;
            }

            $this->_response = [
                'success' => true,
                'total' => count($posts),
                'per_page' => count($result),
                'data' => $result,
                'start' => $start,
                'size' => $size
            ];
        } 
        catch (Exception $e) 
        {
            //Write error in log
            Log::error($e->getMessage() . ' line: ' . $e->getLine() . ' file: ' . $e->getFile());
            return response()->json([], self::STATUS_INTERNAL_SERVER_ERROR);
        }

        return response()->json($this->_response, $status);
    }

    public function getAuthorById($id, $authors) {
        foreach($authors as $author) {
            if($author['id'] == $id) {
                return $author;
            }
        }

        return null;
    }
}
