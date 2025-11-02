use Illuminate\Http\Request;
use Illuminate\Support\Str;

public function charge(Request $req) {
    // Fetch the order
    $order = $this->findOrder($req->order_id);
    if (!$order) {
        return $this->jsonResponse(['message' => 'Order not found'], 404);
    }

    // Process payment simulation
    $success = $this->simulateCardCharge($req->card_number ?? '');

    if ($success) {
        return $this->jsonResponse([
            'status' => 'success',
            'transaction_id' => Str::uuid()
        ], 200);
    } else {
        return $this->jsonResponse([
            'status' => 'failed',
            'reason' => 'card declined (dummy)'
        ], 402);
    }
}

/**
 * Find order by ID
 */
private function findOrder($orderId) {
    return Order::find($orderId);
}

/**
 * Simulate card processing
 * Accepts card if last digit is even
 */
private function simulateCardCharge(string $cardNumber): bool {
    $last = substr($cardNumber, -1);
    return is_numeric($last) && ((int)$last % 2 === 0);
}

/**
 * Standardized JSON response
 */
private function jsonResponse(array $data, int $status) {
    return response()->json($data, $status);
}
