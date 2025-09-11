@extends('layouts.app')

@section('title', 'Terms of Service - Marketplace')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Terms of Service</h1>
            <p class="text-xl text-gray-600">
                Last updated: {{ date('F d, Y') }}
            </p>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <div class="prose prose-lg max-w-none">
                
                <h2>1. Acceptance of Terms</h2>
                <p>By accessing and using this marketplace platform ("Service"), you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.</p>

                <h2>2. Description of Service</h2>
                <p>Our marketplace is a platform that connects buyers and sellers of mobile phones and accessories. We facilitate transactions but are not a party to the actual sale between users. We provide tools and services to help users:</p>
                <ul>
                    <li>List items for sale</li>
                    <li>Search and browse available items</li>
                    <li>Communicate with other users</li>
                    <li>Complete transactions safely</li>
                </ul>

                <h2>3. User Accounts</h2>
                <h3>3.1 Registration</h3>
                <p>To use our Service, you must create an account by providing accurate and complete information. You are responsible for maintaining the confidentiality of your account credentials.</p>
                
                <h3>3.2 Verification</h3>
                <p>We may require you to verify your identity through email, phone number, or other means. Providing false information may result in account suspension or termination.</p>
                
                <h3>3.3 Account Security</h3>
                <p>You are responsible for all activities that occur under your account. Notify us immediately of any unauthorized use of your account.</p>

                <h2>4. User Conduct</h2>
                <h3>4.1 Prohibited Activities</h3>
                <p>You agree not to:</p>
                <ul>
                    <li>Post false, misleading, or fraudulent information</li>
                    <li>List items that are illegal, stolen, or counterfeit</li>
                    <li>Harass, abuse, or harm other users</li>
                    <li>Spam or send unsolicited communications</li>
                    <li>Attempt to circumvent our security measures</li>
                    <li>Use automated systems to access the Service</li>
                    <li>Violate any applicable laws or regulations</li>
                </ul>

                <h3>4.2 Content Standards</h3>
                <p>All content you post must be:</p>
                <ul>
                    <li>Accurate and truthful</li>
                    <li>Appropriate for all audiences</li>
                    <li>Your own original content or properly licensed</li>
                    <li>Compliant with our community guidelines</li>
                </ul>

                <h2>5. Transactions and Payments</h2>
                <h3>5.1 Transaction Responsibility</h3>
                <p>Users are solely responsible for their transactions. We are not a party to any sale and do not guarantee the quality, safety, or legality of items listed.</p>
                
                <h3>5.2 Payment Processing</h3>
                <p>We may provide payment processing services through third-party providers. You agree to their terms and conditions when using these services.</p>
                
                <h3>5.3 Disputes</h3>
                <p>We encourage users to resolve disputes directly. We may provide dispute resolution tools but are not obligated to mediate or resolve disputes.</p>

                <h2>6. Safety and Security</h2>
                <h3>6.1 Meeting Safety</h3>
                <p>Users are responsible for their own safety when meeting in person. We recommend meeting in public places and following our safety guidelines.</p>
                
                <h3>6.2 Item Inspection</h3>
                <p>Buyers should thoroughly inspect items before payment. We are not responsible for items that don't match their description.</p>
                
                <h3>6.3 Reporting</h3>
                <p>Users should report suspicious activity, harassment, or safety concerns immediately through our reporting system.</p>

                <h2>7. Privacy and Data Protection</h2>
                <p>Your privacy is important to us. Please review our Privacy Policy to understand how we collect, use, and protect your information. We comply with applicable data protection laws, including GDPR.</p>

                <h2>8. Intellectual Property</h2>
                <h3>8.1 Our Content</h3>
                <p>All content on our platform, including text, graphics, logos, and software, is our property or licensed to us and protected by copyright and other intellectual property laws.</p>
                
                <h3>8.2 User Content</h3>
                <p>You retain ownership of content you post but grant us a license to use, display, and distribute it in connection with our Service.</p>

                <h2>9. Service Availability</h2>
                <p>We strive to maintain service availability but cannot guarantee uninterrupted access. We may temporarily suspend the Service for maintenance, updates, or other reasons.</p>

                <h2>10. Limitation of Liability</h2>
                <p>To the maximum extent permitted by law, we shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including but not limited to loss of profits, data, or other intangible losses resulting from your use of the Service.</p>

                <h2>11. Indemnification</h2>
                <p>You agree to indemnify and hold us harmless from any claims, damages, or expenses arising from your use of the Service or violation of these Terms.</p>

                <h2>12. Termination</h2>
                <h3>12.1 By You</h3>
                <p>You may terminate your account at any time by contacting us or using the account deletion feature.</p>
                
                <h3>12.2 By Us</h3>
                <p>We may suspend or terminate your account if you violate these Terms or engage in prohibited activities.</p>

                <h2>13. Governing Law</h2>
                <p>These Terms are governed by the laws of the Republic of Serbia. Any disputes will be resolved in the courts of Belgrade, Serbia.</p>

                <h2>14. Changes to Terms</h2>
                <p>We may update these Terms from time to time. We will notify users of significant changes via email or through the Service. Continued use after changes constitutes acceptance of the new Terms.</p>

                <h2>15. Contact Information</h2>
                <p>If you have questions about these Terms, please contact us at:</p>
                <ul>
                    <li>Email: legal@marketplace.rs</li>
                    <li>Address: Knez Mihailova 1, 11000 Belgrade, Serbia</li>
                    <li>Phone: +381 11 123 4567</li>
                </ul>

                <h2>16. Severability</h2>
                <p>If any provision of these Terms is found to be unenforceable, the remaining provisions will remain in full force and effect.</p>

                <h2>17. Entire Agreement</h2>
                <p>These Terms, together with our Privacy Policy and other policies referenced herein, constitute the entire agreement between you and us regarding the use of our Service.</p>

            </div>
        </div>

        <!-- Footer -->
        <div class="mt-12 text-center">
            <p class="text-gray-600">
                By using our Service, you acknowledge that you have read, understood, and agree to be bound by these Terms of Service.
            </p>
        </div>
    </div>
</div>
@endsection
