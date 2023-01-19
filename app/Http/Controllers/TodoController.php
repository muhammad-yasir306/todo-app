<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Todo;
use App\Http\Controllers\Controller;
use App\Http\Resources\TodoResource;
use App\Http\Responses\ErrorResponse;
use App\Http\Resources\TodoCollection;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Responses\SuccessResponse;
use App\Http\Requests\UpdateTodoRequest;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $todos = Todo::where('user_id', auth()->user()->id)->paginate(10);
            return (new SuccessResponse())->toResponse('', ['todos' => $todos]);
            // If laravel's built-in pegination is not preferred then we can use the resource collection
            //return (new SuccessResponse())->toResponse('', ['todos' => new TodoCollection($todos)]);
        } catch (Exception $e) {
            return (new ErrorResponse())->toResponse($e->getMessage());
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTodoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTodoRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['user_id'] = auth()->user()->id;
            $todo = Todo::create($validated);
            return (new SuccessResponse())->toResponse('Todo created successfully', ['todo' => new TodoResource($todo)]);
        } catch (Exception $e) {
            return (new ErrorResponse())->toResponse($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        return (new SuccessResponse())->toResponse('', ['todo' => new TodoResource($todo)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTodoRequest  $request
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTodoRequest $request, Todo $todo)
    {
        try {
            $validated = $request->validated();
            $todo->update($validated);
            return (new SuccessResponse())->toResponse('Todo updated successfully', ['todo' => new TodoResource($todo)]);
        } catch (Exception $e) {
            return (new ErrorResponse())->toResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        try {
            if ($todo->user_id == auth()->user()->id) {
                $todo->delete();
                return (new SuccessResponse())->toResponse('Todo deleted successfully');
            } else {
                return (new ErrorResponse())->toResponse('You are not authorized to delete this Todo');
            }
        } catch (Exception $e) {
            return (new ErrorResponse())->toResponse($e->getMessage());
        }
    }
    
    /**
     * search
     *
     * @param  mixed $title
     * @return void
     */
    public function search($title)
    {
        $title = trim($title);
        if(empty($title)) {
            return (new ErrorResponse())->toResponse('Search title is required');
        }
        $todos = Todo::where('user_id', auth()->user()->id)
            ->where('title', 'like', '%'.$title.'%')
            ->paginate(10);
        return (new SuccessResponse())->toResponse('', ['todos' => $todos]);
    }
}
