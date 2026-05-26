<?php

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Reset permission cache
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // Create permissions
    $permissions = [
        'view articles',
        'create articles',
        'edit articles',
        'delete articles',
        'publish articles',
    ];

    foreach ($permissions as $perm) {
        Permission::create(['name' => $perm]);
    }

    // Create roles
    Role::create(['name' => 'admin'])->givePermissionTo(Permission::all());
    Role::create(['name' => 'editor'])->givePermissionTo([
        'view articles',
        'create articles',
        'edit articles',
        'publish articles',
    ]);
    Role::create(['name' => 'viewer'])->givePermissionTo(['view articles']);

    // Create users
    $this->admin = User::factory()->create()->assignRole('admin');
    $this->editor = User::factory()->create()->assignRole('editor');
    $this->viewer = User::factory()->create()->assignRole('viewer');
});

// ============================================================
// Index (requires: view articles)
// ============================================================

test('admin can view article list', function () {
    $response = $this->actingAs($this->admin)->get(route('articles.index'));
    $response->assertStatus(200);
});

test('editor can view article list', function () {
    $response = $this->actingAs($this->editor)->get(route('articles.index'));
    $response->assertStatus(200);
});

test('viewer can view article list', function () {
    $response = $this->actingAs($this->viewer)->get(route('articles.index'));
    $response->assertStatus(200);
});

test('unauthenticated user is redirected to login', function () {
    $response = $this->get(route('articles.index'));
    $response->assertRedirect(route('login'));
});

// ============================================================
// Create (requires: create articles)
// ============================================================

test('admin can access create form', function () {
    $response = $this->actingAs($this->admin)->get(route('articles.create'));
    $response->assertStatus(200);
});

test('editor can access create form', function () {
    $response = $this->actingAs($this->editor)->get(route('articles.create'));
    $response->assertStatus(200);
});

test('viewer cannot access create form', function () {
    $response = $this->actingAs($this->viewer)->get(route('articles.create'));
    $response->assertStatus(403);
});

test('admin can store an article', function () {
    $response = $this->actingAs($this->admin)->post(route('articles.store'), [
        'title' => 'Admin Article',
        'content' => 'Content by admin.',
        'status' => 'published',
    ]);

    $response->assertRedirect(route('articles.index'));
    $this->assertDatabaseHas('articles', ['title' => 'Admin Article']);
});

test('viewer cannot store an article', function () {
    $response = $this->actingAs($this->viewer)->post(route('articles.store'), [
        'title' => 'Viewer Article',
        'content' => 'This should fail.',
        'status' => 'draft',
    ]);

    $response->assertStatus(403);
    $this->assertDatabaseMissing('articles', ['title' => 'Viewer Article']);
});

// ============================================================
// Edit (requires: edit articles)
// ============================================================

test('admin can edit an article', function () {
    $article = Article::factory()->create(['user_id' => $this->admin->id]);

    $response = $this->actingAs($this->admin)->get(route('articles.edit', $article));
    $response->assertStatus(200);
});

test('editor can edit an article', function () {
    $article = Article::factory()->create(['user_id' => $this->editor->id]);

    $response = $this->actingAs($this->editor)->get(route('articles.edit', $article));
    $response->assertStatus(200);
});

test('viewer cannot edit an article', function () {
    $article = Article::factory()->create(['user_id' => $this->admin->id]);

    $response = $this->actingAs($this->viewer)->get(route('articles.edit', $article));
    $response->assertStatus(403);
});

// ============================================================
// Delete (requires: delete articles)
// ============================================================

test('admin can delete an article', function () {
    $article = Article::factory()->create(['user_id' => $this->admin->id]);

    $response = $this->actingAs($this->admin)->delete(route('articles.destroy', $article));

    $response->assertRedirect(route('articles.index'));
    $this->assertDatabaseMissing('articles', ['id' => $article->id]);
});

test('editor cannot delete an article', function () {
    $article = Article::factory()->create(['user_id' => $this->editor->id]);

    $response = $this->actingAs($this->editor)->delete(route('articles.destroy', $article));
    $response->assertStatus(403);

    $this->assertDatabaseHas('articles', ['id' => $article->id]);
});

test('viewer cannot delete an article', function () {
    $article = Article::factory()->create(['user_id' => $this->admin->id]);

    $response = $this->actingAs($this->viewer)->delete(route('articles.destroy', $article));
    $response->assertStatus(403);
});
