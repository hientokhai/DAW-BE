<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //Api danh sach binh luan
    public function index()
    {
        $comments = Comment::with(['user', 'productVariant'])->get();
        return response()->json([
            'data' => $comments
        ]);
    }
    public function delete($id)
    {
        $comments = Comment::all();
        $id = Comment::find($id);
        $id->delete();

        return response()->json([
            'id'=> $id,
            'data'=>$comments
        ]);
    }
}
