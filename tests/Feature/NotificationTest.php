<?php

use App\Models\User;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('user can view notifications page', function () {
    $response = $this->get(route('notifications.index'));

    $response->assertStatus(200);
    $response->assertViewIs('notifications');
});

test('user can mark notification as read', function () {
    $notification = Notification::create([
        'user_id' => $this->user->id,
        'type' => 'savings',
        'title' => 'Test Notification',
        'message' => 'This is a test message',
        'is_read' => false,
    ]);

    expect($notification->is_read)->toBeFalse();

    $response = $this->post(route('notifications.read', $notification));

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'id' => $notification->id,
    ]);

    $notification->refresh();
    expect($notification->is_read)->toBeTrue();
});

test('user can delete notification', function () {
    $notification = Notification::create([
        'user_id' => $this->user->id,
        'type' => 'budget',
        'title' => 'Test Notification',
        'message' => 'This is a test message',
        'is_read' => false,
    ]);

    expect(Notification::find($notification->id))->not->toBeNull();

    $response = $this->delete(route('notifications.destroy', $notification));

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'id' => $notification->id,
    ]);

    expect(Notification::find($notification->id))->toBeNull();
});

test('user cannot mark another users notification as read', function () {
    $otherUser = User::factory()->create();
    $notification = Notification::create([
        'user_id' => $otherUser->id,
        'type' => 'savings',
        'title' => 'Other User Notification',
        'message' => 'This belongs to another user',
        'is_read' => false,
    ]);

    $response = $this->post(route('notifications.read', $notification));

    $response->assertStatus(403);
    $response->assertJson(['success' => false]);

    $notification->refresh();
    expect($notification->is_read)->toBeFalse();
});

test('user cannot delete another users notification', function () {
    $otherUser = User::factory()->create();
    $notification = Notification::create([
        'user_id' => $otherUser->id,
        'type' => 'savings',
        'title' => 'Other User Notification',
        'message' => 'This belongs to another user',
        'is_read' => false,
    ]);

    $response = $this->delete(route('notifications.destroy', $notification));

    $response->assertStatus(403);
    $response->assertJson(['success' => false]);

    expect(Notification::find($notification->id))->not->toBeNull();
});

test('notifications index shows only current users notifications', function () {
    // Create notification for current user
    $myNotification = Notification::create([
        'user_id' => $this->user->id,
        'type' => 'savings',
        'title' => 'My Notification',
        'message' => 'This is my notification',
        'is_read' => false,
    ]);

    // Create notification for another user
    $otherUser = User::factory()->create();
    $otherNotification = Notification::create([
        'user_id' => $otherUser->id,
        'type' => 'budget',
        'title' => 'Other Notification',
        'message' => 'This belongs to another user',
        'is_read' => false,
    ]);

    $response = $this->get(route('notifications.index'));

    $response->assertStatus(200);
    $response->assertSee('My Notification');
    $response->assertDontSee('Other Notification');
});

test('marking already read notification as read works', function () {
    $notification = Notification::create([
        'user_id' => $this->user->id,
        'type' => 'savings',
        'title' => 'Already Read',
        'message' => 'This notification is already read',
        'is_read' => true,
    ]);

    $response = $this->post(route('notifications.read', $notification));

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'id' => $notification->id,
    ]);

    $notification->refresh();
    expect($notification->is_read)->toBeTrue();
});

test('guest cannot access notifications', function () {
    auth()->logout();

    $response = $this->get(route('notifications.index'));

    $response->assertRedirect(route('login'));
});

test('guest cannot mark notification as read', function () {
    $notification = Notification::create([
        'user_id' => $this->user->id,
        'type' => 'savings',
        'title' => 'Test',
        'message' => 'Test message',
        'is_read' => false,
    ]);

    auth()->logout();

    $response = $this->post(route('notifications.read', $notification));

    $response->assertRedirect(route('login'));
});

test('guest cannot delete notification', function () {
    $notification = Notification::create([
        'user_id' => $this->user->id,
        'type' => 'savings',
        'title' => 'Test',
        'message' => 'Test message',
        'is_read' => false,
    ]);

    auth()->logout();

    $response = $this->delete(route('notifications.destroy', $notification));

    $response->assertRedirect(route('login'));
});
