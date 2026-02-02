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
     * Retorna uma coleção de tarefas com paginação opcional.
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
     * Código legado mantido para refatoração na Parte 2 da avaliação.
     */
    public function store(Request $request)
    {
        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->completed = false;
        $task->save();
        return response()->json($task);
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
