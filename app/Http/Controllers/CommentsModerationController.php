<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentsModerationController extends Controller
{
    public function index(){
        $comments = Comment::orderBy('created_at', 'desc')->get();
        return view('commentsEdit', compact('comments'));
    }

    public function changeComment(Request $request){
        $input = $request->all();
        Comment::updateOrCreate([
            'id' => $input['id']
        ], [
            'comment' => $input['comment']
        ]);
        return response($input['id']);
    }

    public function deleteComment(Request $request){
        $input = $request->all();
        Comment::where('id', '=', $input['id'])->delete();
        return response('deleted');
    }
}
