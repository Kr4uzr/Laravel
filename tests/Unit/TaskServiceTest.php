<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    private TaskService $taskService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->taskService = new TaskService();
    }

    /**
     * Testa se consegue listar todas as tarefas ordenadas por data de criação.
     */
    public function test_can_get_all_tasks(): void
    {
        $task1 = Task::factory()->create(['created_at' => now()->subDays(2)]);
        $task2 = Task::factory()->create(['created_at' => now()->subDays(1)]);
        $task3 = Task::factory()->create(['created_at' => now()]);

        $tasks = $this->taskService->getAllTasks();

        $this->assertCount(3, $tasks);
        $this->assertEquals($task3->id, $tasks->first()->id); // Mais recente primeiro
        $this->assertEquals($task1->id, $tasks->last()->id); // Mais antiga por último
    }

    /**
     * Testa se consegue criar uma tarefa com sucesso.
     */
    public function test_can_create_task(): void
    {
        $data = [
            'title' => 'Nova Tarefa',
            'description' => 'Descrição da tarefa',
        ];

        $task = $this->taskService->createTask($data);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Nova Tarefa', $task->title);
        $this->assertEquals('Descrição da tarefa', $task->description);
        $this->assertFalse($task->completed);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Nova Tarefa',
            'completed' => false,
        ]);
    }

    /**
     * Testa se completed sempre inicia como false ao criar tarefa.
     */
    public function test_create_task_sets_completed_to_false_by_default(): void
    {
        $data = [
            'title' => 'Tarefa sem completed',
            'description' => 'Teste de completed padrão',
        ];

        $task = $this->taskService->createTask($data);

        $this->assertFalse($task->completed);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'completed' => false,
        ]);
    }

    /**
     * Testa se consegue buscar uma tarefa por ID existente.
     */
    public function test_can_get_task_by_id(): void
    {
        $task = Task::factory()->create([
            'title' => 'Tarefa de Teste',
            'description' => 'Descrição de teste',
        ]);

        $foundTask = $this->taskService->getTaskById((string) $task->id);

        $this->assertInstanceOf(Task::class, $foundTask);
        $this->assertEquals($task->id, $foundTask->id);
        $this->assertEquals('Tarefa de Teste', $foundTask->title);
        $this->assertEquals('Descrição de teste', $foundTask->description);
    }

    /**
     * Testa se lança ModelNotFoundException quando tarefa não é encontrada.
     */
    public function test_throws_model_not_found_exception_when_task_not_found(): void
    {
        $nonExistentId = '999';

        $this->expectException(ModelNotFoundException::class);

        $this->taskService->getTaskById($nonExistentId);
    }

    /**
     * Testa se consegue atualizar uma tarefa com sucesso.
     */
    public function test_can_update_task(): void
    {
        $task = Task::factory()->create([
            'title' => 'Tarefa Original',
            'description' => 'Descrição original',
            'completed' => false,
        ]);

        $updateData = [
            'title' => 'Tarefa Atualizada',
            'description' => 'Descrição atualizada',
            'completed' => true,
        ];

        $updatedTask = $this->taskService->updateTask((string) $task->id, $updateData);

        $this->assertInstanceOf(Task::class, $updatedTask);
        $this->assertEquals('Tarefa Atualizada', $updatedTask->title);
        $this->assertEquals('Descrição atualizada', $updatedTask->description);
        $this->assertTrue($updatedTask->completed);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Tarefa Atualizada',
            'completed' => true,
        ]);
    }

    /**
     * Testa se lança ModelNotFoundException ao tentar atualizar tarefa inexistente.
     */
    public function test_throws_model_not_found_exception_when_updating_non_existent_task(): void
    {
        $nonExistentId = '999';
        $updateData = ['title' => 'Tarefa Atualizada'];

        $this->expectException(ModelNotFoundException::class);

        $this->taskService->updateTask($nonExistentId, $updateData);
    }

    /**
     * Testa se consegue remover uma tarefa com sucesso.
     */
    public function test_can_delete_task(): void
    {
        $task = Task::factory()->create();

        $result = $this->taskService->deleteTask((string) $task->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    /**
     * Testa se lança ModelNotFoundException ao tentar remover tarefa inexistente.
     */
    public function test_throws_model_not_found_exception_when_deleting_non_existent_task(): void
    {
        $nonExistentId = '999';

        $this->expectException(ModelNotFoundException::class);

        $this->taskService->deleteTask($nonExistentId);
    }
}
