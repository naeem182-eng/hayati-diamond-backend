<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Invoice;
use App\Models\InstallmentPlan;
use App\Services\InstallmentService;
use App\Exceptions\InstallmentAlreadyExistsException;
use PHPUnit\Framework\Attributes\Test;

class InstallmentServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_throws_exception_when_creating_duplicate_installment_plan()
    {
        $invoice = Invoice::factory()
            ->installment()
            ->create(['total_amount' => 50000]);

        InstallmentPlan::factory()->create([
            'invoice_id' => $invoice->id,
        ]);

        $service = app(InstallmentService::class);

        $this->expectException(
            InstallmentAlreadyExistsException::class
        );

        $service->createPlanFromInvoice($invoice, 5);
    }
}
