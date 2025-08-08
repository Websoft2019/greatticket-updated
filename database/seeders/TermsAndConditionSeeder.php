<?php

namespace Database\Seeders;

use App\Models\TermsCondition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TermsAndConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        TermsCondition::create([
            'title' => 'Terms & Conditions',
            'description' => '
                <p>Welcome to Great Ticket for online ticketing platform. Terms and conditions stated below apply to all visitors and users of <a href="https://greatticket.my/" target="_blank">https://greatticket.my/</a>. You are bound by these terms and conditions as long as you are on the website.</p>
                
                <h3>General</h3>
                <p>The content of terms and conditions may be changed, moved, or deleted at any time. Please note that Great Ticket has the rights to change the contents of the terms and conditions without notice. Any violation of rules and regulations will result in immediate actions against the offender(s).</p>
                
                <h3>Site Contents & Copyrights</h3>
                <p>Unless otherwise noted, all materials including images, illustrations, designs, icons, photographs, video clips, and written content are owned, controlled, or licensed by Great Ticket.</p>
                
                <h3>Comments and Feedback</h3>
                <p>All comments and feedback to Great Ticket will remain confidential. Users agree that comments submitted to the website will not violate any rights of third parties, and must not contain unlawful or abusive content.</p>
                
                <h3>Product Information</h3>
                <p>We cannot guarantee all products will appear exactly the same as shown on the monitor, due to differences in display settings.</p>
                
                <h3>Newsletter</h3>
                <p>Users agree that Great Ticket may send newsletters regarding the latest news, events, and promotions through email.</p>
                
                <h3>Indemnification</h3>
                <p>Users agree to indemnify Great Ticket from any claims, damages, costs, or expenses arising from their use of the site.</p>
                
                <h3>Link to Other Sites</h3>
                <p>Accessing third-party sites is at the user’s own risk. Great Ticket is not responsible for any loss or damage resulting from third-party sites.</p>
                
                <h3>Inaccuracy Information</h3>
                <p>Occasionally, there may be typographical errors or inaccuracies in the information on the site. Great Ticket reserves the right to correct these without prior notice.</p>
                
                <h3>Termination</h3>
                <p>This agreement remains in effect unless terminated by either the customer or Great Ticket. Great Ticket reserves the right to terminate the agreement without prior notice if users do not comply with these terms.</p>
                
                <h3>Payments</h3>
                <p>All purchases are subject to a one-time payment. Payment can be made via Visa, MasterCard, or other methods. Card payments are subject to validation checks.</p>
                
                <h3>Shipping, Cancellation, and Refund Policy</h3>
                <ul>
                    <li>Orders can be canceled before shipment without additional fees.</li>
                    <li>Refunds will be issued upon receipt of returned goods that comply with the return policy.</li>
                </ul>
                
                <h3>Privacy Policy</h3>
                <p>We respect your privacy and will not disclose your personal information to third parties, except as required to provide our services.</p>
            '
        ]);
    }
}
