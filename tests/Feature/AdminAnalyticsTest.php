<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\StartupProfile;
use App\Models\Task;
use App\Models\Application;
use App\Models\HiringOffer;
use App\Models\Transaction;
use App\Models\Escrow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;
    private User $studentUser;
    private User $startupUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@interngrowth.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create student
        $this->studentUser = User::create([
            'name' => 'Student Alice',
            'email' => 'alice@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);
        StudentProfile::create([
            'user_id' => $this->studentUser->id,
            'primary_domain' => 'Software Development',
            'preferred_role' => 'Backend Developer',
            'is_verified' => true,
        ]);

        // Create startup
        $this->startupUser = User::create([
            'name' => 'Startup Bob',
            'email' => 'bob@example.com',
            'password' => bcrypt('password'),
            'role' => 'startup',
        ]);
        StartupProfile::create([
            'user_id' => $this->startupUser->id,
            'company_name' => 'Growth Corp',
            'is_verified' => true,
            'wallet_balance' => 1000.00,
        ]);
    }

    public function test_non_admin_cannot_access_admin_dashboard()
    {
        $response = $this->actingAs($this->studentUser)
            ->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_dashboard_with_analytics()
    {
        // Seed some platform transactions
        Transaction::create([
            'user_type' => 'platform',
            'user_id' => 0,
            'type' => 'credit',
            'amount' => 199.00,
            'description' => 'Platform fee',
            'reference_id' => 'task_1',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
        $response->assertViewHas('data');

        $data = $response->viewData('data');
        $this->assertArrayHasKey('health_score', $data);
        $this->assertArrayHasKey('kpis', $data);
        $this->assertArrayHasKey('revenue', $data);
        $this->assertArrayHasKey('escrow', $data);
        $this->assertArrayHasKey('funnel', $data);
        $this->assertArrayHasKey('alerts', $data);
    }

    public function test_export_revenue_csv()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.analytics.export.revenue'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        
        $contentDisposition = $response->headers->get('Content-Disposition');
        $this->assertStringStartsWith('attachment; filename=revenue_analytics_', $contentDisposition);
        $this->assertStringEndsWith('.csv', $contentDisposition);
        $this->assertStringContainsString('Month', $response->streamedContent());
    }

    public function test_export_hiring_csv()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.analytics.export.hiring'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('Offer ID', $response->streamedContent());
    }

    public function test_export_users_csv()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.analytics.export.users'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('User ID', $response->streamedContent());
    }
}
