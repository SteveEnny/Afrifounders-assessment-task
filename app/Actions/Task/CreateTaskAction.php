<?php

declare(strict_types=1);

namespace App\Actions\Task;

use App\Http\Resources\TaskResource;
use App\Models\Task;

final class CreateTaskAction
{
    public function handle(array $data)
    {
        $data = array_merge($data, [
            'user_id' => auth()->id,
        ]);

        $task = Task::createOrFail($data);

        return new TaskResource($task);
    }
}
