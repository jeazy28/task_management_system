<?php

namespace App\Http\Controllers;

use App\Http\Resources\TasksResource;
use App\Models\Files;
use App\Models\Tasks;
use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        //
        $task = Tasks::with('TaskStatus')
            ->where('user_id', Auth::id())
            ->where('status', '=', $request->status)
            ->when($request->draft, function ($sql) {
                return $sql->where('is_published', '=', 0);
            })
            ->when($request->title, function ($sql) use ($request) {
                return $sql->where('title', 'like', "%{$request->title}%");
            })
            ->select(['id', 'title', 'status', 'created_at'])
            ->orderBy($request->sortName, $request->sortBy)
            ->cursorPaginate($request->render)
            ->withQueryString();

        return TasksResource::collection($task);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:tasks,title|max:100|string',
            'content' => 'required',
            'task_file' => 'image|max:4000'
        ], [
            'title.required' => 'Title is required',
            'title.unique' => 'Title is already exist',
            'title.max' => 'Title is allowed only 100 characters',
            'title.alpha' => 'Title only allowed letters',
            'task_file.image' => 'The File must be an Image',
            'task_file.max' => 'The Image must not exceeds 4MB.',
            'content.required' => 'Content is required',
        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->all();
            return response($firstError, 422);
        }

        $insert = [
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => Auth::id(),
            'is_published' => $request->type
        ];

        if ($request->file('task_file')) {
            $insert['has_file'] = 1;
        }

        $task = Tasks::create($insert);

        if ($request->file('task_file')) {
            $data = [
                'task_id' => $task->id,
                'orig_name' => $request->file('task_file')->getClientOriginalName(),
                'size' => $request->file('task_file')->getSize(),
                'mime_type' => $request->file('task_file')->getMimeType(),
            ];

            $this->saveFile($data, $request->file('task_file'));
        }

        return response('Task Creation Success!');

    }

    protected function saveFile($data, $file)
    {
        $path = $file->store('photos', 'public');
        $data['path'] = $path;
        Files::create(attributes: $data);


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $file = [];
        $count = Tasks::where('id', $id)
            ->where('user_id', Auth::id())
            ->count();

        if ($count) {
            $task = Tasks::with('TaskStatus')
                ->find($id);
            $status = TaskStatus::all();

            if ($task->has_file) {
                $file = Files::where('task_id', $task->id)
                    ->select(['path'])
                    ->first();
            }


            return view('task_info', compact('task', 'status', 'file'));
        }

        return view('404');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        if ($request->publish) {
            $task = Tasks::find($id);
            $task->is_published = 1;
            $task->save();

            return response('Success!');
        }

        $validator = Validator::make($request->all(), [
            'title' => "required|unique:tasks,title,{$id}|max:100|string",
            'content' => 'required',

        ], [
            'title.required' => 'Title is required',
            'title.unique' => 'Title is already exist',
            'title.max' => 'Title is allowed only 100 characters',
            'title.alpha' => 'Title only allowed letters',
            'content.required' => 'Content is required',
        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->all();
            return response($firstError, 422);
        }

        $task = Tasks::find($id);
        $task->status = $request->status;
        $task->title = $request->title;
        $task->content = $request->content;
        $task->save();

        return response('Task has been updated.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Tasks::find($id)->delete();

    }
}
