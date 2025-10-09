<?php

use App\Constants\PaymentStatus;

return [
  'order_confirmation_subject' => 'Order Confirmation - :order_number',
  'order_confirmation_title' => 'Order Confirmation',
  'order_update_subject' => 'Update on Your Order',
  'order_confirmation_greeting' => 'Hello :name,',
  'order_confirmation_thank_you' => 'Thank you for your order.',
  'order_confirmation_order_number' => 'Your order number is: :order_number',
  'order_details' => 'Order Details',
  'product' => 'Product',
  'quantity' => 'Quantity',
  'price' => 'Price',
  'order_total' => 'Total: :total',
  'order_confirmation_closing' => 'We appreciate your business and hope you enjoy your purchase!',
  'no_shipping_address' => 'No shipping address provided.',
  'order_confirmation_header' => 'Order Confirmation',
  'total' => 'Total',
  'subtotal' => 'Subtotal',
  'shipping' => 'Shipping',
  'free_shipping' => 'Free Shipping',
  'discount' => 'Discount',
  'shipping_address' => 'Shipping Address',
  'order_confirmation_footer' => 'Thank you for shopping with :company!',
  'view_order' => 'View Order',


  'receipt_from' => 'Receipt from :company',
  'paid_date' => 'Paid :date',
  'order_date' => 'Order :date',
  'download_invoice' => 'Download invoice',
  'download_receipt' => 'Download receipt',
  'order_number' => 'Order number',
  'payment_method' => 'Payment method',
  'shipping_method' => 'Shipping method',
  'receipt' => 'Receipt',
  'qty' => 'Qty',
  'shipping_cost' => 'Shipping',
  'tax' => 'Tax',
  'wallet_amount' => 'Wallet Amount',
  'amount_paid' => 'Amount paid',
  'questions' => 'Questions? Visit our support site at',
  'or_contact' => 'or contact us at',

  'variations' => 'Variations',
  'customizations' => 'Customizations',


  'payment_details' => 'Payment Details',
  'payment_amount' => 'Payment Amount',
  'payment_status' => 'Payment Status',
  'payment_date' => 'Payment Date',
  'outstanding_balance' => 'Outstanding Balance',
  'payment_status_pending' => 'Pending',
  'payment_status_processing' => 'Processing',
  'payment_status_completed' => 'Completed',
  'payment_status_failed' => 'Failed',
  'payment_status_refunded' => 'Refunded',
  'payment_statuses' => [
    PaymentStatus::PENDING => 'Pending',
    PaymentStatus::PROCESSING => 'Processing',
    PaymentStatus::COMPLETED => 'Completed',
    PaymentStatus::FAILED => 'Failed',
    PaymentStatus::REFUNDED => 'Refunded',
  ],


  'payment_summary' => 'Payment Summary',
  'total_order_amount' => 'Total Order Amount',
  'split_payment_notice' => 'This order uses split payment (50% advance payment)',
  'total_outstanding' => 'Total Outstanding Balance',
  'pending_payments' => 'Pending Payments',
  'completed_payments' => 'Completed Payments',
  'advance_payment' => 'Advance Payment',
  'remaining_payment' => 'Remaining Payment',
  'payment_number' => 'Payment #:number',

  /** upn order completing information to user wallet benefit */

  'coupon_used_title' => 'Your Coupon Has Been Used',
  'coupon_used_heading' => 'Hello :name, Your Coupon Has Been Used',
  'potential_benefit' => 'Potential benefit: :amount',
  'referral_benefit_title' => 'Referral Benefit Received',
  'referral_benefit_heading' => 'Hello :name, Referral Benefit Received',
  'referral_benefit_message' => 'Your referral benefit for Order #:id has been processed.',
  'wallet_update' => 'Amount added to your wallet: :amount',
  'view_wallet' => 'View Wallet',

  'coupon_used_message' => 'Your coupon code :code has been used in an order.',
  'referral_benefit_title' => 'Referral Benefit Received',
  'referral_benefit_heading' => 'Hello :name, Referral Benefit Received',
  'referral_benefit_message' => 'Your referral benefit for Order #:id placed by :customer_name has been processed.',
  'wallet_update' => 'Amount added to your wallet: :amount',
  'view_wallet' => 'View Wallet',
  'coupon_code_used' => 'Coupon code used: :code',
  'order_details' => 'Order Details',
  'order_total' => 'Order Total: :total',
  'order_date' => 'Order Date: :date',

  'order_id' => 'Order ID: #:id',

  /** order submitted information coupon usage to user */
  'coupon_used_title' => 'Your Coupon Has Been Used',
  'coupon_used_heading' => 'Hello :name, Your Coupon Has Been Used',
  'potential_benefit' => 'Potential benefit: :amount',
  'benefit_completion_message' => 'You will receive this benefit once the order is completed.',



  // Welcome email
  'welcome_title' => 'Welcome to NewYou Sports',
  'welcome_heading' => 'Welcome to NewYou Sports, :name!',
  'welcome_message' => 'We\'re excited to have you join our community of sports enthusiasts!',
  'our_vision' => 'Our Vision',
  'vision_description' => "At NewYou Sports, we're dedicated to creating uniforms with enthusiasm, honesty, and a deep passion. We pay special attention to bringing your vision to life, believing these details will empower your team to perform at their best. Together, we're setting up your team to be champions!",
  'how_it_works' => 'How It Works',
  'workflow_step1' => 'Request a personalized design through our inquiry system',
  'workflow_step2' => 'Review and approve your custom design',
  'workflow_step3' => 'Submit your order with team details, player names, numbers, and sizes',
  'workflow_step4' => 'We produce and deliver your custom sportswear (typically 1-2 weeks)',
  'why_choose_us' => 'Why Choose NewYou Sports?',
  'benefit_fast_turnaround_title' => 'Fast Turnaround Times',
  'benefit_fast_turnaround_description' => 'We understand the importance of timely delivery. Our streamlined process ensures quick production and delivery, even during peak seasons, so you never miss a game or event.',
  'benefit_customizable_products_title' => 'Customizable Products',
  'benefit_customizable_products_description' => 'Our easy-to-use online platform allows you to design and order uniforms with your logos, colors, and player names. Plus, each user gets their own personalized store on our website to manage orders and designs effortlessly.',
  'benefit_referral_program_title' => 'Referral Program',
  'benefit_referral_program_description' => 'Love our service? Refer friends and earn rewards through our referral program! It\'s our way of saying thank you for spreading the word.',
  'benefit_wallet_system_title' => 'Wallet System',
  'benefit_wallet_system_description' => 'Our integrated wallet system makes payments seamless. Add funds, track balances, and enjoy hassle-free transactions for all your orders.',
  'benefit_flexible_ordering_title' => 'Flexible Ordering',
  'benefit_flexible_ordering_description' => 'Start with an initial quantity and add more later. We adapt to your team\'s needs.',
  'referral_program' => 'Refer and Earn!',
  'referral_description' => "If you refer another team or business to us, and their order is successfully delivered, you'll receive credit in your account to use towards future purchases. Plus, the referred team will get a discount on their first order!",
  'customer_reviews' => 'Hear from Our Customers',
  'review_description' => 'Check out what our customers are saying about us:',
  'watch_reviews' => 'Watch Customer Reviews',
  'closing_message' => "Whether you're outfitting a team, planning an event, or looking for fan gear, we've got you covered. Let's create something extraordinary together!",
  'request_design' => 'Request Your Custom Design',

  // Footer
  'questions' => 'If you have any questions, please visit',
  'or_contact' => 'or contact us at',
  'submit_inquiry' => 'Submit an Inquiry',
  'unsubscribe' => 'Unsubscribe',
  'default_footer' => '© ' . date('Y') . ' ' . config('app.company_name') . '. All rights reserved.',


  // mockups email
  'design_email_subject' => ':name, Your Custom Sportswear Design is Ready',
  'design_email_title' => 'Check Out Our Latest Designs',
  'design_email_heading' => 'Hello :name, Check Out Our Latest Designs!',
  'design_email_intro' => 'We hope this email finds you well, :name. We\'re excited to share our latest designs with you!',
  'design_email_order_guide' => 'To place an order, simply click on any design that catches your eye. You\'ll be taken to our website where you can customize your order and complete your purchase.',
  'design_email_vision' => 'At [Your Company Name], we\'re committed to bringing your vision to life. Each design can be customized to perfectly suit your needs and preferences.',
  'view_design' => 'View Design',
  'view_all_designs' => 'View All Designs',

  'user_design_email_cta' => 'Ready to make it yours? Place your order now!',
  'place_order' => 'Place Your Order',


  'design_email_title' => 'Your Custom Sportswear Design is Ready!',
  'user_design_email_heading' => 'Hello :name, Your Vision Has Come to Life!',
  'user_design_email_intro' => "We're thrilled to share something amazing with you, :name!",
  'user_design_email_vision' => 'At New You Sports, we believe in turning ideas into reality. We\'ve brought your vision to life, and we can\'t wait for you to see it!',
  'user_design_email_mockup_info' => 'We\'ve carefully crafted this design based on your requirements, ensuring every detail reflects your unique style and identity.',
  'user_design_email_why_choose' => 'Why Choose Us:',
  'user_design_email_seamless' => ' Seamless Workflow: From design to delivery, we make the process easy.',
  'user_design_email_customizable' => ' Customizable Options: Add logos, player names, and more.',
  'user_design_email_fast' => ' Fast Turnaround: We prioritize speed without compromising quality.',
  'user_design_email_questions' => 'Questions or need adjustments? We\'re here to help!',
  'view_your_design' => 'View Your Design',
  'contact_us' => 'Contact Us',
  'view_all_designs' => 'Explore More Designs',
  'single_price' => '$:price',
  'price_range' => '$:min - $:max',
  'pricing_varies' => 'Pricing varies by quantity',
  'and_more' => 'and :count more',
  'no_designs_message' => 'No designs available at the moment. Check back soon!',

  //Campaign template simple.blade.php

  'shop_by_category' => 'Shop by Category',
  'featured_products' => 'Featured Products',
  'no_image' => 'No Image',
  'view_product' => 'View Product',
  'explore_more' => 'Explore More',

  //Coupon
  'special_offers' => 'Special Offers',
  'code' => 'CODE',
  'discount_type' => 'Discount type',
  'discount' => 'Discount',
  'discount_type_fixed' => 'Fixed Amount',
  'discount_type_percentage' => 'Percentage',
  'discount_type_free_shipping' => 'Free Shipping',
  'discount_type_buy_x_get_y' => 'Buy X Get Y',
  'free_shipping' => 'Free Shipping',
  'min_order' => 'Min. Order',
  'expires' => 'Expires',
  'left' => 'left',
  'coupons_left' => ':count coupons left',
  'coupon_number' => '#:number',



];