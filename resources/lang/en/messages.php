<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Message Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */
    'order' => [
        'order_placed' => 'Your order has been placed.',
    ],


    'welcome' => 'Welcome to our application!',
    'goodbye' => 'Thank you for using our application!',
    'hello' => 'Hello!',
    'hello_user' => 'Hello, :name!',
    'regards' => 'Regards',
    'no_tax' => 'No tax',
    'and_more' => 'and more',
    'order_success' => 'Order has been placed successfully and waiting for payment confirmation!',
    'design_team' => 'Design team creates and finalizes mockups.',
    'printing_preparations' => 'Printing preparations are underway.',
    'confirm_cancellation' => 'You can choose to confirm cancellation or move the order to production.',
    'cancellation_fee' => '$50 will be deducted for design and printing services.',
    'production_progress' => 'Production is in progress.',
    'complete_payment' => 'Complete your payment for the remaining balance.',
    'default_footer' => '© ' . date('Y') . ' ' . config('app.company_name') . '. All rights reserved.',

    'company_slogan' => 'Bringing Vision to Life',
    'inquiry_success' => 'Your inquiry has been submitted successfully. We will get back to you soon.',


    'order' => [
        'not_found' => 'We couldn\'t find the order you\'re looking for.',
        'unauthorized_access' => 'You don\'t have permission to view this order.',
        'retrieval_error' => 'We encountered an issue while retrieving the order. Please try again later.',
        'stage_updated' => 'Order stage updated successfully.',
        'stage_update_failed' => 'Failed to update order stage. Please try again.',
        'remark_deleted' => 'Remark deleted successfully.',
        'remark_deletion_failed' => 'Failed to delete remark. Please try again.',
        'user_linked' => 'User linked to order successfully.',
        'user_linking_failed' => 'Failed to link user to order. Please try again.',
    ],



];
