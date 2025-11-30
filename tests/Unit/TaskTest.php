<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->category = Category::factory()->create(['user_id' => $this->user->id]);
    }

    public function test_task_belongs_to_user(): void
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
        ]);

        $this->assertInstanceOf(User::class, $task->user);
        $this->assertEquals($this->user->id, $task->user->id);
    }

    public function test_task_belongs_to_category(): void
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
        ]);

        $this->assertInstanceOf(Category::class, $task->category);
        $this->assertEquals($this->category->id, $task->category->id);
    }

    public function test_task_can_be_marked_as_completed(): void
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'is_completed' => false,
        ]);

        $task->markAsCompleted();

        $this->assertTrue($task->is_completed);
        $this->assertNotNull($task->completed_at);
        $this->assertEquals(Task::STATUS_COMPLETED, $task->status);
    }

    public function test_task_can_be_marked_as_pending(): void
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'is_completed' => true,
            'completed_at' => now(),
        ]);

        $task->markAsPending();

        $this->assertFalse($task->is_completed);
        $this->assertNull($task->completed_at);
        $this->assertEquals(Task::STATUS_PENDING, $task->status);
    }

    public function test_task_can_be_archived(): void
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
        ]);

        $task->archive();

        $this->assertTrue($task->is_archived);
        $this->assertNotNull($task->archived_at);
    }

    public function test_task_can_be_unarchived(): void
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'is_archived' => true,
            'archived_at' => now(),
        ]);

        $task->unarchive();

        $this->assertFalse($task->is_archived);
        $this->assertNull($task->archived_at);
    }

    public function test_task_scope_active_filters_archived(): void
    {
        Task::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'is_archived' => false,
        ]);

        Task::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'is_archived' => true,
        ]);

        $activeTasks = Task::active()->count();

        $this->assertEquals(1, $activeTasks);
    }

    public function test_task_scope_completed(): void
    {
        Task::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'is_completed' => true,
        ]);

        Task::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'is_completed' => false,
        ]);

        $completedTasks = Task::completed()->count();

        $this->assertEquals(1, $completedTasks);
    }

    public function test_task_is_overdue_accessor(): void
    {
        $overdueTask = Task::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'due_date' => now()->subDay(),
            'is_completed' => false,
        ]);

        $onTimeTask = Task::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'due_date' => now()->addDay(),
            'is_completed' => false,
        ]);

        $this->assertTrue($overdueTask->is_overdue);
        $this->assertFalse($onTimeTask->is_overdue);
    }

    public function test_task_priority_color_accessor(): void
    {
        $highTask = Task::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'priority' => 'high',
        ]);

        $this->assertEquals('red', $highTask->priority_color);
    }
}
