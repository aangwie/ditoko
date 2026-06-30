<?php
// Helper script untuk membuat chat views
// Part 1: Buyer View
$buyerContent = file_get_contents(__DIR__ . '/storage/app/buyer_template.txt');
file_put_contents(__DIR__ . '/resources/views/chat/buyer.blade.php', $buyerContent);
echo "Buyer view created\n";

// Part 2: Admin View  
$adminContent = file_get_contents(__DIR__ . '/storage/app/admin_template.txt');
file_put_contents(__DIR__ . '/resources/views/chat/admin.blade.php', $adminContent);
echo "Admin view created\n";
