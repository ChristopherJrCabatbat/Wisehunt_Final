@section('notification')
foreach ($productsss as $product) {
    if ($product->quantity <= 20) {

        // $user = UserAccount::first();
        // $user->notify(new SMSNotification);

        $notification = [
            'message' => $product->name . "'s quantity is too low!",
            'productId' => $product->id, // Assuming 'id' is the product's unique identifier
        ];
        $lowQuantityNotifications[] = $notification;
    }
}
@endsection

// // SMS
// $productsss = Products::all();
// $lowQuantityNotifications = [];

// foreach ($productsss as $product) {
//     if ($product->quantity <= 20) {
//         $notification = [
//             'message' => $product->name . "'s quantity is too low!",
//             'productId' => $product->id,
//         ];
//         $lowQuantityNotifications[] = $notification;

//         // Access user's phone number from the product's user relation
//         $phoneNumber = $product->user->phone_number; // Assuming a 'phone_number' field in the User model

//         // Call sendSMSNotification using $this
//         $this->sendSMSNotification($phoneNumber, $product->name);
//     }
// }
