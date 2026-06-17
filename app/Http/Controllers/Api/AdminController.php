<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function uploadCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('file');

        $handle = fopen($file->getRealPath(), 'r');

        $totalRecords = 0;
        $insertedRecords = 0;
        $duplicateRecords = 0;

        $processedEmails = [];

        DB::beginTransaction();

        try {

            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {

                if ($totalRecords === 0) {
                    $totalRecords++;
                    continue;
                }

                $name = trim($row[0]);
                $phone = trim($row[1]);
                $email = trim($row[2]);
                $amount = trim($row[3]);

                if (
                    Customer::where('email', $email)->exists() ||
                    in_array($email, $processedEmails)
                ) {
                    $duplicateRecords++;
                    continue;
                }

                Customer::create([
                    'name' => $name,
                    'phone_number' => $phone,
                    'email' => $email,
                    'payment_amount' => $amount,
                    'payment_status' => 'Pending'
                ]);

                $processedEmails[] = $email;

                $insertedRecords++;
                $totalRecords++;
            }

            fclose($handle);

            DB::commit();

            return response()->json([
                'success' => true,
                'total_records' => $totalRecords - 1,
                'inserted_records' => $insertedRecords,
                'duplicate_records' => $duplicateRecords
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
