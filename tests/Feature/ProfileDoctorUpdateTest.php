<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileDoctorUpdateTest extends TestCase
{
    public function test_doctor_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create(['role' => 'doctor']);

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'firstName' => 'John',
                'lastName' => 'Doe',
                'email' => 'john.doe@example.com',
                'age' => 40,
                'gender' => 'Male',
                'address' => '123 Medical St',
                'expertise' => 'Cardiology',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('john.doe@example.com', $user->email);

        $doctor = Doctor::where('user_id', $user->id)->first();
        $this->assertNotNull($doctor);
        $this->assertSame('John', $doctor->firstName);
        $this->assertSame('Cardiology', $doctor->expertise);
    }
}
