<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{

    public function __construct(
        private TaskService $taskService
    ) {

    }

    /**
     * Lista todas as tarefas.
     * Retorna 200 OK com uma coleção de tarefas.
     * Retorna 500 se houver erro ao listar as tarefas.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $tasks = $this->taskService->getAllTasks();
        return TaskResource::collection($tasks);
    }

    /**
     * Cria uma nova tarefa.
     * Retorna 201 Created com a tarefa criada.
     * Retorna 422 se houver erro de validação.
     * Retorna 500 se houver erro ao criar a tarefa.
     *
     * @param StoreTaskRequest $request
     * @return TaskResource
     */
    public function store(StoreTaskRequest $request): TaskResource
    {
        $task = $this->taskService->createTask($request->validated());
        return (new TaskResource($task))->response()->setStatusCode(201);
    }

    /**
     * Exibe uma tarefa específica.
     * Retorna 200 OK com a tarefa encontrada.
     * Retorna 404 se a tarefa não for encontrada.
     * Retorna 500 se houver erro ao buscar a tarefa.
     *
     * @param string $id
     * @return TaskResource
     */
    public function show(string $id): TaskResource
    {
        $task = $this->taskService->getTaskById($id);
        return new TaskResource($task);
    }

    /**
     * Atualiza uma tarefa existente.
     * Retorna 200 OK com a tarefa atualizada.
     * Retorna 404 se a tarefa não for encontrada.
     * Retorna 500 se houver erro ao atualizar a tarefa.
     *
     * @param UpdateTaskRequest $request
     * @param string $id
     * @return TaskResource
     */
    public function update(UpdateTaskRequest $request, string $id): TaskResource
    {
        $task = $this->taskService->updateTask($id, $request->validated());
        return new TaskResource($task);
    }

    /**
     * Remove uma tarefa.
     * Retorna 204 No Content se a tarefa for removida com sucesso.
     * Retorna 500 se houver erro ao remover a tarefa.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id): \Illuminate\Http\Response
    {
        $this->taskService->deleteTask($id);
        return response()->noContent();
    }
}
