<?php

namespace App\Http\Controllers;

use App\Models\AboutContent;
use App\Models\Comment;
use Illuminate\Http\Request;
use stdClass;

class AboutController extends Controller
{
    public function index()
    {
        $comments = Comment::orderBy('created_at', 'desc')->get();
        $aboutEditorNet = AboutContent::where('id', 1)->first();
        $aboutEditorCust = AboutContent::where('id', 2)->first();
        $aboutEditorContacts = AboutContent::where('id', 3)->first();
        $aboutEditorAwards = AboutContent::where('id', 4)->first();
        $aboutEditorStaff = AboutContent::where('id', 5)->first();
        return view('about', compact('comments',
            'aboutEditorNet',
            'aboutEditorCust',
            'aboutEditorContacts',
            'aboutEditorAwards',
            'aboutEditorStaff'));
    }

    public function adminIndex()
    {
        $comments = Comment::orderBy('created_at', 'desc')->get();
        $aboutEditorNet = AboutContent::where('id', 1)->first();
        $aboutEditorCust = AboutContent::where('id', 2)->first();
        $aboutEditorContacts = AboutContent::where('id', 3)->first();
        $aboutEditorAwards = AboutContent::where('id', 4)->first();
        $aboutEditorStaff = AboutContent::where('id', 5)->first();
        return view('adminAboutEditor', compact('comments',
            'aboutEditorNet',
            'aboutEditorCust',
            'aboutEditorContacts',
            'aboutEditorAwards',
            'aboutEditorStaff'));
    }

    public function storeComment(Request $request)
    {
        $comment = new Comment;
        $comment->user_id = auth()->id();
        $comment->comment = $request->getContent();
        $comment->save();
        return response()->json([
            'username' => $comment->user->name,
            'comment' => $comment->comment,
            'created_at' => $comment->created_at->format("d-m-Y"),
        ]);
    }

    public function changeAboutPage(Request $request)
    {
        $input = $request->all();
        AboutContent::updateOrCreate([
            'id' => $request->id
        ], [
            'section' => $input['section'],
            'content' => $input['editor']
        ]);
        return response('saved');
    }

    public function storeImage(Request $request)
    {
        // Allowed extentions.
        $allowedExts = array("gif", "jpeg", "jpg", "png", 'webp');

        // Get filename.
        $temp = explode(".", $_FILES["image_param"]["name"]);

        // Get extension.
        $extension = end($temp);

        // An image check is being done in the editor but it is best to
        // check that again on the server side.
        // Do not use $_FILES["file"]["type"] as it can be easily forged.
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES["image_param"]["tmp_name"]);

        if ((($mime == "image/gif")
                || ($mime == "image/jpeg")
                || ($mime == "image/webp")
                || ($mime == "image/pjpeg")
                || ($mime == "image/x-png")
                || ($mime == "image/png"))
            && in_array($extension, $allowedExts)) {
            // Generate new random name.
            $name = sha1(microtime()) . "." . $extension;

            // Save file in the uploads folder.
            move_uploaded_file($_FILES["image_param"]["tmp_name"], getcwd() . "/imgs/aboutUploaded/" . $name);

            // Generate response.
            $response = new StdClass;
            $response->link = "/imgs/aboutUploaded/" . $name;
            echo stripslashes(json_encode($response));
        }
    }
}
