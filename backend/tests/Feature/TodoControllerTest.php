<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\TodoNotification;
use App\Repositories\Eloquent\TodoRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TodoControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_stores_a_new_todo_and_sends_notification()
    {
        
        // Notification::fake();
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $data = [
            'title' => 'Test Todo',
            'description' => 'This is a test todo item.',
        ];
        
        // Send a POST request to the store method
        $response = $this->postJson(route('todos.store'), $data);
        
        $response->assertStatus(200)
        ->assertJson(['message' => 'saved_success_message']);
        
        $this->assertDatabaseHas('todos', [
            'title' => 'Test Todo',
            'description' => 'This is a test todo item.',
        ]);
    
        // Notification::assertSentTo($user, TodoNotification::class, function ($notification) use ($data) {
        //     return $notification->getTodo()->title === $data['title'] && $notification->getTodo()->action === 'created';
        // });
    }
    

    #[Test]
    public function it_returns_validation_errors_when_data_is_invalid()
    {
        $this->actingAs(User::factory()->create());
    
        $response = $this->postJson(route('todos.store'), []);
    
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'The given data is invalid',
                'errors' => [
                    'title' => [
                        'The title field is required.',
                    ],
                ],
                'status' => 422,
            ]);
    }
}
