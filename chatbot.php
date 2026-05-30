<?php
$message = strtolower(trim($_POST['message']));

$faqs = [
    "delivery" => "We deliver within 2-5 business days depending on your location.",
    "payment" => "You can pay via credit card, debit card, or online wallets.",
    "vegan" => "Yes! We have a variety of vegan snacks available.",
    "return" => "You can return products within 7 days of delivery."
];

// Default response
$response = "Sorry, I don't understand. Try asking about delivery, payment, vegan snacks, or returns.";

foreach($faqs as $key => $answer){
    if(strpos($message, $key) !== false){
        $response = $answer;
        break;
    }
}

// Simulate typing delay
usleep(800000); // 0.8 seconds
echo $response;
