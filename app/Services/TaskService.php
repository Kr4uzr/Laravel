<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class TaskService
{
    /**
     * Lista todas as tarefas ordenadas por data de criação.
     *
     * @return Collection
     * @throws \Exception
     */
    public function getAllTasks(): Collection
    {
        try {
            return Task::latest()->get();
        } catch (\Exception $e) {
            throw new \Exception('Erro ao listar tarefas: ' . $e->getMessage());
        }
    }

    /**
     * Cria uma nova tarefa.
     *
     * @param array $data
     * @return Task
     * @throws \Exception
     */
    public function createTask(array $data): Task
    {
        try {
            return Task::create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'completed' => false,
            ]);
        } catch (QueryException $e) {
            throw new \Exception('Erro ao salvar tarefa no banco de dados: ' . $e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception('Erro inesperado ao criar tarefa: ' . $e->getMessage());
        }
    }

    /**
     * Busca uma tarefa por ID.
     *
     * @param string $id
     * @return Task
     * @throws ModelNotFoundException
     * @throws \Exception
     */
    public function getTaskById(string $id): Task
    {
        try {
            return Task::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new \Exception('Erro ao buscar tarefa: ' . $e->getMessage());
        }
    }

    /**
     * Atualiza uma tarefa existente.
     *
     * @param string $id
     * @param array $data
     * @return Task
     * @throws ModelNotFoundException
     * @throws \Exception
     */
    public function updateTask(string $id, array $data): Task
    {
        try {
            $task = Task::findOrFail($id);
            $task->update($data);

            return $task->fresh();
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new \Exception('Erro ao atualizar tarefa no banco de dados: ' . $e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception('Erro inesperado ao atualizar tarefa: ' . $e->getMessage());
        }
    }

    /**
     * Remove uma tarefa.
     *
     * @param string $id
     * @return bool
     * @throws ModelNotFoundException
     * @throws \Exception
     */
    public function deleteTask(string $id): bool
    {
        try {
            $task = Task::findOrFail($id);
            return $task->delete();
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new \Exception('Erro ao remover tarefa do banco de dados: ' . $e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception('Erro inesperado ao remover tarefa: ' . $e->getMessage());
        }
    }
}
