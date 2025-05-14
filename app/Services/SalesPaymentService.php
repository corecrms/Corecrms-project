<?php

// app/Services/SalesPaymentService.php

namespace App\Services;

use App\Models\SalesInvoice;
use App\Models\SalesInvoicePayment;
use Illuminate\Support\Facades\DB;

class SalesPaymentService
{
    public function storePayment(SalesInvoice $salesInvoice, array $data)
    {
        try {
            DB::beginTransaction();

            $data['created_by'] = auth()->user()->id;

            $salesInvoicePayment = $salesInvoice->salesInvoicePayments()->create($data);

            $this->updateSalesInvoiceStatus($salesInvoice, $data['amount']);

            if ($salesInvoice->paid_amount > $salesInvoice->net_amount) {
                $this->createSalesInvoiceCreditNote($salesInvoice, $data);
            }

            DB::commit();
            return ['success' => true, 'message' => 'Payment added successfully.'];
        } catch (\Exception $e) {
            DB::rollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function destroyPayment(SalesInvoicePayment $salesInvoicePayment)
    {
        try {
            DB::beginTransaction();

            $salesInvoice = $salesInvoicePayment->salesInvoice;


            $salesInvoicePayment->delete();

            $salesInvoicePayment->deleted_by = auth()->user()->id;
            $salesInvoicePayment->update();

            if ($salesInvoicePayment->salesInvoiceCreditNotes()->exists()) {
                $salesInvoicePayment->salesInvoiceCreditNotes()->delete();
            }

            $this->updateSalesInvoiceStatus($salesInvoice, $salesInvoicePayment->amount, true);

            DB::commit();
            return ['success' => true, 'message' => 'Payment deleted successfully.'];
        } catch (\Exception $e) {
            \Log::error($e);
            DB::rollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    protected function updateSalesInvoiceStatus(SalesInvoice $salesInvoice, $amount, $isDelete = false)
    {
        if ($isDelete) {
            $salesInvoice->paid_amount -= $amount;
        } else {
            $salesInvoice->paid_amount += $amount;
        }

        if ($salesInvoice->paid_amount >= $salesInvoice->net_amount) {
            $salesInvoice->status = 'Paid';
        } elseif ($salesInvoice->paid_amount == 0) {
            $salesInvoice->status = 'Draft';
        } else {
            $salesInvoice->status = 'Partially Paid';
        }

        $salesInvoice->update();
    }

    protected function createSalesInvoiceCreditNote(SalesInvoice $salesInvoice, array $data)
    {
        $salesInvoice->salesInvoiceCreditNotes()->create([
            'amount' => $salesInvoice->paid_amount - $salesInvoice->net_amount,
            'credit_date' => $data['payment_date'],
            'reference_number' => $salesInvoice->reference_number,
            'description' => $data['description'],
            'sales_invoice_id' => $salesInvoice->id,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
    }
}
