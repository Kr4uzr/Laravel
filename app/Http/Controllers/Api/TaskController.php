<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{
    /**
     * Lista todas as tarefas.
     * Retorna 200 OK com uma coleção de tarefas.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $tasks = Task::latest()->get();

        return TaskResource::collection($tasks);
    }

    /**
     * Cria uma nova tarefa.
     * Retorna 201 Created com a tarefa criada.
     * Retorna 422 se houver erro de validação.
     *
     * @param StoreTaskRequest $request
     * @return TaskResource
     */
    public function store(StoreTaskRequest $request): TaskResource
    {
        $validated = $request->validated();

        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'completed' => false,
        ]);

        return (new TaskResource($task))->response()->setStatusCode(201);
    }

    /**
     * Exibe uma tarefa específica.
     * Retorna 404 se a tarefa não for encontrada.
     *
     * @param string $id
     * @return TaskResource
     */
    public function show(string $id): TaskResource
    {
        $task = Task::findOrFail($id);

        return new TaskResource($task);
    }

    /**
     * Atualiza uma tarefa existente.
     * Retorna 404 se a tarefa não for encontrada.
     * Retorna 422 se houver erro de validação.
     * Retorna 200 OK com a tarefa atualizada.
     *
     * @param UpdateTaskRequest $request
     * @param string $id
     * @return TaskResource
     */
    public function update(UpdateTaskRequest $request, string $id): TaskResource
    {
        $task = Task::findOrFail($id);
        $task->update($request->validated());

        return new TaskResource($task);
    }

    /**
     * Remove uma tarefa.
     * Retorna 404 se a tarefa não for encontrada.
     * Retorna 200 OK com a mensagem de sucesso.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'Tarefa removida com sucesso.'], 200);
    }
}
