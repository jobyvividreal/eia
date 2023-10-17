<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Events\UserCommented;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = \Validator::make($request->all(), 
            [ 'comment' => 'required'],
            [ 'comment.required' => 'Please enter Comment']);

        if ($validator->passes()) {
            $input                  = $request->all();
            $input['commented_by']  = auth()->user()->id;
            $comment                = Comment::create($input);

            if($comment) {
                $html = $this->lastCommentHTML($comment->id);
            }

            event(new UserCommented($comment));


            return ['flagError' => false, 'html' => $html, 'message' => "Comment added successfully"];
        }
        return ['flagError' => true, 'message' => "Errors Occurred. Please check !",  'error' => $validator->errors()->all()];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }

    /**
     * Return HTML data.
     *
     */
    public function lastCommentHTML($commentId) 
    {
        $html       = '';
        $comment    = Comment::find($commentId);
            $html .= '<div class="app-email" id="docCommentsDiv'.$comment->id.'">';
            $html .= '<div class="content-area"><div class="app-wrapper"><div class="card card card-default scrollspy border-radius-6 fixed-width">';
            $html .= '<div class="card-content p-0 pb-2"><div class="collection email-collection"><div class="email-brief-info collection-item animate fadeUp ">';
            $html .= '<a class="list-content" href="javascript:"><div class="list-title-area"><div class="user-media">';
            $html .= '<img src="'.$comment->commentedBy->profile_url.'" alt="" class="circle z-depth-2 responsive-img avtar">';
            $html .= '<div class="list-title">You</div>';
            $html .= '</div></div><div class="list-desc">'.$comment->comment.'</div></a>';
            $html .= '<div class="list-right"><div class="list-date">'.$comment->created_at->format('M d, h:i A').'</div></div>';
            $html .= '</div></div></div></div></div></div></div>';
        return $html;
    }
}