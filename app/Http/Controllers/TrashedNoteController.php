<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TrashedNoteController extends Controller
{
    public function index(){
        $notes = Note::whereBelongsTo(Auth::user())->onlyTrashed()->latest('updated_at')->paginate(7);
        return view('Notes.index')->with('notes',$notes);
    }

    public function show(Note $note){
        if(!$note->user->is(Auth::user())){
            abort(403);
        }
        return view('Notes.show')->with('note',$note);
    }

    public function update(Note $note){
        if(!$note->user->is(Auth::user())){
            abort(403);
        }

        $note->restore();
        return to_route('notes.show', $note)->with('success', 'Note Restore Successful');
    }

    public function destroy(Note $note){
        if(!$note->user->is(Auth::user())){
            abort(403);
        }
        $note->forceDelete();

        return to_route('trashed.index', $note)->with('success', 'Note Deleted Permanetly Successful');
    }
}
