<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword = $request->query('status');
        $tasks = $request->user()
            ->tasks()
            ->filter($keyword)
            ->paginate(10);

        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        try {
            $task = Task::create([
                ...$request->validated(),
                'user_id' => $request->user()->id,
            ]);

            return $this->success('Task successfully created!', new TaskResource($task), 201);
        } catch (Exception $ex) {
            Log::error('Task creation failed', [
                'user_id' => $request->user()->id,
                'error' => $ex->getMessage(),
            ]);

            return $this->error('Failed to create tasks Please try again', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        try {
            $this->authorize('view', $task);

            return new TaskResource($task);
        } catch (AuthorizationException $ex) {
            return $this->error('You are not authorized', 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        try {
            $this->authorize('update', $task);
            $updated = $task->update($request->validated());
            if (! $updated) {
                return $this->error('Failed to update task. Please try again.', 500);
            }

            return $this->success('Task successfully updated!', new TaskResource($task->refresh()));
        } catch (AuthorizationException $ex) {
            return $this->error('You are not authorized', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        try {
            // policy
            $this->authorize('delete', $task);

            $task->delete();

            return $this->ok('Task successfully deleted');
        } catch (AuthorizationException $ex) {
            return $this->error('You are not authorized', 401);
        }

    }
}
