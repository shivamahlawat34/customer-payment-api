<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CommunicationLog;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->search) {

            $query->where(function ($q) use ($request) {

                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%")
                    ->orWhere('phone_number', 'like', "%{$request->search}%");
            });
        }

        return response()->json(
            $query->paginate(10)
        );
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:Pending,Paid'
        ]);

        $customer = Customer::findOrFail($id);

        $customer->update([
            'payment_status' => $request->payment_status
        ]);

        return response()->json([
            'message' => 'Payment status updated successfully'
        ]);
    }

    public function sendNotification(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:email,whatsapp'
        ]);

        $customer = Customer::findOrFail($id);

        if ($customer->payment_status === 'Paid') {

            return response()->json([
                'message' => 'Customer already paid'
            ]);
        }

        CommunicationLog::create([
            'customer_id' => $customer->id,
            'user_id' => auth()->id(),
            'type' => $request->type,
            'sent_at' => now()
        ]);

        $report = [
            'total_customers' => Customer::count(),
            'paid_customers' => Customer::where('payment_status', 'Paid')->count(),
            'pending_customers' => Customer::where('payment_status', 'Pending')->count(),
            'emails_sent' => CommunicationLog::where('type', 'email')->count(),
            'whatsapp_sent' => CommunicationLog::where('type', 'whatsapp')->count()
        ];

        return response()->json([
            'message' => 'Notification sent successfully',
            'report' => $report
        ]);
    }
}
