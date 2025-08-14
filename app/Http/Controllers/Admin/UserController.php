<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Questionnaire;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('is_superadmin', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get quiz submissions and plan purchases
        $quizSubmissions = Questionnaire::pluck('email')->toArray();
        $planPurchases   = Payment::where('status', 'succeeded')
            ->orWhere('status', 'discount_applied')
            ->pluck('user_id')
            ->toArray();

        return view('backend.pages.user.index', compact('users', 'quizSubmissions', 'planPurchases'));
    }

    public function getUserDetails(Request $request)
    {
        $user = User::find($request->user_id);
        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ]);
        }

        return response()->json([
            'success' => true,
            'data'    => $user,
        ]);
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            // Check if user is not a superadmin
            if ($user->is_superadmin) {
                return redirect()->back()->with('error', 'Cannot delete superadmin user.');
            }

            // Delete related records
            Payment::where('user_id', $id)->delete();
            Questionnaire::where('email', $user->email)->delete();

            // Delete the user
            $user->delete();

            DB::commit();

            return redirect()->back()->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }
}
