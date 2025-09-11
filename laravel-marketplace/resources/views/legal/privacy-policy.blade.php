@extends('layouts.app')

@section('title', 'Privacy Policy - Marketplace')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Privacy Policy</h1>
            <p class="text-xl text-gray-600">
                Last updated: {{ date('F d, Y') }}
            </p>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <div class="prose prose-lg max-w-none">
                
                <h2>1. Introduction</h2>
                <p>This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our marketplace platform. We are committed to protecting your privacy and ensuring the security of your personal data in accordance with applicable data protection laws, including the General Data Protection Regulation (GDPR).</p>

                <h2>2. Information We Collect</h2>
                <h3>2.1 Information You Provide</h3>
                <p>We collect information you voluntarily provide to us, including:</p>
                <ul>
                    <li><strong>Account Information:</strong> Name, email address, phone number, profile photo</li>
                    <li><strong>Listing Information:</strong> Item descriptions, photos, prices, location</li>
                    <li><strong>Communication Data:</strong> Messages sent through our platform</li>
                    <li><strong>Payment Information:</strong> Billing address, payment method details (processed securely by third parties)</li>
                    <li><strong>Support Requests:</strong> Information provided when contacting our support team</li>
                </ul>

                <h3>2.2 Information We Collect Automatically</h3>
                <p>We automatically collect certain information when you use our Service:</p>
                <ul>
                    <li><strong>Usage Data:</strong> Pages visited, features used, time spent on the platform</li>
                    <li><strong>Device Information:</strong> IP address, browser type, operating system, device identifiers</li>
                    <li><strong>Location Data:</strong> General location based on IP address or GPS (with your permission)</li>
                    <li><strong>Cookies and Similar Technologies:</strong> See our Cookie Policy for details</li>
                </ul>

                <h3>2.3 Information from Third Parties</h3>
                <p>We may receive information from third-party services, including:</p>
                <ul>
                    <li>Social media platforms (if you choose to connect your account)</li>
                    <li>Payment processors</li>
                    <li>Analytics providers</li>
                    <li>Fraud prevention services</li>
                </ul>

                <h2>3. How We Use Your Information</h2>
                <p>We use your information for the following purposes:</p>
                
                <h3>3.1 Service Provision</h3>
                <ul>
                    <li>Create and maintain your account</li>
                    <li>Process transactions and payments</li>
                    <li>Facilitate communication between users</li>
                    <li>Provide customer support</li>
                    <li>Display listings and search results</li>
                </ul>

                <h3>3.2 Safety and Security</h3>
                <ul>
                    <li>Verify user identities</li>
                    <li>Detect and prevent fraud</li>
                    <li>Investigate suspicious activity</li>
                    <li>Enforce our Terms of Service</li>
                    <li>Protect user safety</li>
                </ul>

                <h3>3.3 Communication</h3>
                <ul>
                    <li>Send service-related notifications</li>
                    <li>Respond to your inquiries</li>
                    <li>Send marketing communications (with your consent)</li>
                    <li>Provide important updates about our Service</li>
                </ul>

                <h3>3.4 Analytics and Improvement</h3>
                <ul>
                    <li>Analyze usage patterns and trends</li>
                    <li>Improve our Service and user experience</li>
                    <li>Develop new features</li>
                    <li>Conduct research and analytics</li>
                </ul>

                <h2>4. Legal Basis for Processing (GDPR)</h2>
                <p>We process your personal data based on the following legal grounds:</p>
                <ul>
                    <li><strong>Contract Performance:</strong> To provide our Service and fulfill our obligations to you</li>
                    <li><strong>Legitimate Interest:</strong> To improve our Service, prevent fraud, and ensure safety</li>
                    <li><strong>Consent:</strong> For marketing communications and optional features</li>
                    <li><strong>Legal Obligation:</strong> To comply with applicable laws and regulations</li>
                </ul>

                <h2>5. Information Sharing and Disclosure</h2>
                <h3>5.1 Public Information</h3>
                <p>Some information is publicly visible on our platform:</p>
                <ul>
                    <li>Your username and profile information (as you choose to make public)</li>
                    <li>Listings you create (including photos and descriptions)</li>
                    <li>Public reviews and ratings</li>
                </ul>

                <h3>5.2 Service Providers</h3>
                <p>We share information with trusted third-party service providers who help us operate our platform:</p>
                <ul>
                    <li>Payment processors</li>
                    <li>Email service providers</li>
                    <li>Analytics services</li>
                    <li>Cloud hosting providers</li>
                    <li>Customer support tools</li>
                </ul>

                <h3>5.3 Legal Requirements</h3>
                <p>We may disclose your information if required by law or to:</p>
                <ul>
                    <li>Comply with legal obligations</li>
                    <li>Respond to lawful requests from authorities</li>
                    <li>Protect our rights and property</li>
                    <li>Ensure user safety</li>
                    <li>Prevent fraud or illegal activities</li>
                </ul>

                <h2>6. Data Security</h2>
                <p>We implement appropriate technical and organizational measures to protect your personal data:</p>
                <ul>
                    <li>Encryption of data in transit and at rest</li>
                    <li>Regular security assessments and updates</li>
                    <li>Access controls and authentication</li>
                    <li>Employee training on data protection</li>
                    <li>Incident response procedures</li>
                </ul>

                <h2>7. Data Retention</h2>
                <p>We retain your personal data for as long as necessary to:</p>
                <ul>
                    <li>Provide our Service to you</li>
                    <li>Comply with legal obligations</li>
                    <li>Resolve disputes</li>
                    <li>Enforce our agreements</li>
                </ul>
                <p>When we no longer need your data, we will securely delete or anonymize it.</p>

                <h2>8. Your Rights (GDPR)</h2>
                <p>Under GDPR, you have the following rights regarding your personal data:</p>
                <ul>
                    <li><strong>Right of Access:</strong> Request copies of your personal data</li>
                    <li><strong>Right to Rectification:</strong> Correct inaccurate or incomplete data</li>
                    <li><strong>Right to Erasure:</strong> Request deletion of your personal data</li>
                    <li><strong>Right to Restrict Processing:</strong> Limit how we use your data</li>
                    <li><strong>Right to Data Portability:</strong> Receive your data in a structured format</li>
                    <li><strong>Right to Object:</strong> Object to processing based on legitimate interests</li>
                    <li><strong>Right to Withdraw Consent:</strong> Withdraw consent for consent-based processing</li>
                </ul>

                <h2>9. Cookies and Tracking Technologies</h2>
                <p>We use cookies and similar technologies to enhance your experience. For detailed information about our use of cookies, please see our Cookie Policy.</p>

                <h2>10. International Data Transfers</h2>
                <p>Your data may be transferred to and processed in countries outside the European Economic Area. We ensure appropriate safeguards are in place for such transfers, including:</p>
                <ul>
                    <li>Standard Contractual Clauses</li>
                    <li>Adequacy decisions by the European Commission</li>
                    <li>Other appropriate safeguards as required by law</li>
                </ul>

                <h2>11. Children's Privacy</h2>
                <p>Our Service is not intended for children under 16 years of age. We do not knowingly collect personal information from children under 16. If we become aware that we have collected personal data from a child under 16, we will take steps to delete such information.</p>

                <h2>12. Changes to This Privacy Policy</h2>
                <p>We may update this Privacy Policy from time to time. We will notify you of any material changes by:</p>
                <ul>
                    <li>Posting the new Privacy Policy on our website</li>
                    <li>Sending you an email notification</li>
                    <li>Displaying a notice on our Service</li>
                </ul>

                <h2>13. Contact Information</h2>
                <p>If you have questions about this Privacy Policy or our data practices, please contact us:</p>
                <ul>
                    <li><strong>Data Protection Officer:</strong> dpo@marketplace.rs</li>
                    <li><strong>General Inquiries:</strong> privacy@marketplace.rs</li>
                    <li><strong>Address:</strong> Knez Mihailova 1, 11000 Belgrade, Serbia</li>
                    <li><strong>Phone:</strong> +381 11 123 4567</li>
                </ul>

                <h2>14. Supervisory Authority</h2>
                <p>You have the right to lodge a complaint with a supervisory authority if you believe we have violated data protection laws. In Serbia, the relevant authority is the Commissioner for Information of Public Importance and Personal Data Protection.</p>

            </div>
        </div>

        <!-- Footer -->
        <div class="mt-12 text-center">
            <p class="text-gray-600">
                This Privacy Policy is effective as of {{ date('F d, Y') }} and will remain in effect except with respect to any changes in its provisions in the future.
            </p>
        </div>
    </div>
</div>
@endsection
