<?php

namespace Tests\Feature;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DoctorsTest extends TestCase
{
    /**
     * Test that doctors list is displayed correctly.
     */
    public function test_doctors_page_is_displayed_with_data(): void
    {
        $user = User::factory()->create();

        Doctor::factory()->count(3)->create();

        $response = $this
            ->actingAs($user)
            ->get('/doctors');

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Doctors')
            ->has('doctors', 3)
        );
    }
}
