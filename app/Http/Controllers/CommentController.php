<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use JsonResponse;
    //Api danh sach binh luan
    public function index()
    {
        $comments = Comment::with(['user', 'productVariant','productVariant.size','productVariant.color','productVariant.product','productVariant.product.images'])->get();
        return $this->successResponse($comments, 'CommentList');
    }
    public function delete($id)
    {
        $comments = Comment::all();
        $id = Comment::find($id);
        $id->delete();

        return $this->successResponse([$id, $comments], 'Success');
    }
}
