<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function addComment(Request $request)
    {
        //$comment = Comments::where('product_id', $request->input('product_id'))->where('user_id', $request->input('user_id'))->get()->first();

        $comment = new Comments();
        if (Product::find($request->input('product_id')) == null || $request->input('text') == null)
            return response()->json(['message' => 'bad request'], 400);

        $comment->user_id = $request->User()['id'];
        $comment->product_id = $request->input('product_id');
        $comment->comment = $request->input('text');
        $comment->save();
        return response()->json(['message' => 'added successfuly']);
    }

    public function editComment(Request $request)
    {

        $comment = Comments::find($request->input('comment_id'));

        if ($comment == null)
            return response()->json(["message" => "bad request"], 400);
        if ($comment->user['id'] != $request->User()['id'])
            return response()->json(['connot edit this comment'], 400);

        if ($comment->User['id'] != $request->User()['id'])
            return response()->json(["message" => "bad request"], 400);

        $comment->comment = $request->input('text');
        $comment->save();
        return response()->json(['message' => 'edited successfuly']);
    }

    public function deleteComment(Request $request)
    {

        $comment = Comments::find($request->input('comment_id'));

        if ($comment == null)
            return response()->json(["message" => "bad request"], 400);

        if ($comment->user['id'] != $request->User()['id'])
            return response()->json(['connot edit this comment'], 400);

        if ($comment->User['id'] != $request->User()['id'])
            return response()->json(["message" => "bad request"], 400);

        Comments::destroy($comment['id']);
        return response()->json(['message' => 'deleted successfuly']);
    }
}
