<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::orderBy('created_at', 'ASC')->get();

        // dd($todos);

        return response()->json($todos, 200);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $todo = new Todo;

            $todo->title = $request['title'];
            $todo->body = $request['body'];
            $todo->completed = $request['completed'];

            $todo->save();

            DB::commit();

            return response()->json(['message' => 'Saved'], 201);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;

            return response()->json(['message' => 'Unable to save'], 500);
        }

        dd($request);
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $todo = Todo::findOrFail($id);

            $todo->delete();

            DB::commit();

            return response()->json(['message' => 'Deleted'], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;

            return response()->json(['message' => 'Unable to delete'], 500);
        }
    }
}
