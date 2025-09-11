@extends('layouts.app')

@section('title', 'Cookie Policy - Marketplace')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Cookie Policy</h1>
            <p class="text-xl text-gray-600">
                Last updated: {{ date('F d, Y') }}
            </p>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <div class="prose prose-lg max-w-none">
                
                <h2>1. What Are Cookies?</h2>
                <p>Cookies are small text files that are stored on your device when you visit our website. They help us provide you with a better experience by remembering your preferences and enabling certain functionality.</p>

                <h2>2. How We Use Cookies</h2>
                <p>We use cookies and similar technologies to:</p>
                <ul>
                    <li>Remember your login status and preferences</li>
                    <li>Analyze how you use our website</li>
                    <li>Improve our services and user experience</li>
                    <li>Provide personalized content and advertisements</li>
                    <li>Ensure website security and prevent fraud</li>
                </ul>

                <h2>3. Types of Cookies We Use</h2>
                
                <h3>3.1 Essential Cookies</h3>
                <p>These cookies are necessary for our website to function properly. They cannot be disabled and include:</p>
                <ul>
                    <li><strong>Session Cookies:</strong> Keep you logged in during your visit</li>
                    <li><strong>Security Cookies:</strong> Protect against fraud and ensure security</li>
                    <li><strong>Load Balancing Cookies:</strong> Distribute website traffic efficiently</li>
                </ul>

                <h3>3.2 Functional Cookies</h3>
                <p>These cookies enhance your experience by remembering your preferences:</p>
                <ul>
                    <li><strong>Language Preferences:</strong> Remember your language choice</li>
                    <li><strong>Theme Settings:</strong> Remember your display preferences</li>
                    <li><strong>Search Filters:</strong> Remember your search preferences</li>
                    <li><strong>Location Settings:</strong> Remember your location for local listings</li>
                </ul>

                <h3>3.3 Analytics Cookies</h3>
                <p>These cookies help us understand how visitors use our website:</p>
                <ul>
                    <li><strong>Google Analytics:</strong> Track website usage and performance</li>
                    <li><strong>Heat Mapping:</strong> Understand user behavior on pages</li>
                    <li><strong>Performance Monitoring:</strong> Identify and fix technical issues</li>
                </ul>

                <h3>3.4 Marketing Cookies</h3>
                <p>These cookies are used to deliver relevant advertisements:</p>
                <ul>
                    <li><strong>Advertising Networks:</strong> Show relevant ads based on your interests</li>
                    <li><strong>Social Media Integration:</strong> Enable sharing and social features</li>
                    <li><strong>Retargeting:</strong> Show ads for products you've viewed</li>
                </ul>

                <h2>4. Third-Party Cookies</h2>
                <p>We may use third-party services that set their own cookies:</p>
                
                <h3>4.1 Google Services</h3>
                <ul>
                    <li><strong>Google Analytics:</strong> Website analytics and performance tracking</li>
                    <li><strong>Google Ads:</strong> Advertising and conversion tracking</li>
                    <li><strong>Google Maps:</strong> Location services and mapping</li>
                </ul>

                <h3>4.2 Social Media</h3>
                <ul>
                    <li><strong>Facebook:</strong> Social sharing and advertising</li>
                    <li><strong>Twitter:</strong> Social sharing and analytics</li>
                    <li><strong>LinkedIn:</strong> Professional networking features</li>
                </ul>

                <h3>4.3 Payment Processors</h3>
                <ul>
                    <li><strong>Stripe:</strong> Payment processing and fraud prevention</li>
                    <li><strong>PayPal:</strong> Alternative payment method</li>
                </ul>

                <h2>5. Cookie Duration</h2>
                <p>Cookies have different lifespans:</p>
                <ul>
                    <li><strong>Session Cookies:</strong> Deleted when you close your browser</li>
                    <li><strong>Persistent Cookies:</strong> Remain on your device for a set period</li>
                    <li><strong>First-Party Cookies:</strong> Set by our website directly</li>
                    <li><strong>Third-Party Cookies:</strong> Set by external services we use</li>
                </ul>

                <h2>6. Managing Your Cookie Preferences</h2>
                
                <h3>6.1 Browser Settings</h3>
                <p>You can control cookies through your browser settings:</p>
                <ul>
                    <li><strong>Chrome:</strong> Settings > Privacy and Security > Cookies and other site data</li>
                    <li><strong>Firefox:</strong> Options > Privacy & Security > Cookies and Site Data</li>
                    <li><strong>Safari:</strong> Preferences > Privacy > Manage Website Data</li>
                    <li><strong>Edge:</strong> Settings > Cookies and site permissions</li>
                </ul>

                <h3>6.2 Our Cookie Consent Tool</h3>
                <p>When you first visit our website, you'll see a cookie consent banner where you can:</p>
                <ul>
                    <li>Accept all cookies</li>
                    <li>Reject non-essential cookies</li>
                    <li>Customize your preferences</li>
                    <li>Learn more about each cookie category</li>
                </ul>

                <h3>6.3 Opt-Out Links</h3>
                <p>You can opt out of specific third-party cookies:</p>
                <ul>
                    <li><strong>Google Analytics:</strong> <a href="https://tools.google.com/dlpage/gaoptout" target="_blank" class="text-blue-600 hover:underline">Google Analytics Opt-out</a></li>
                    <li><strong>Facebook Ads:</strong> <a href="https://www.facebook.com/settings?tab=ads" target="_blank" class="text-blue-600 hover:underline">Facebook Ad Preferences</a></li>
                    <li><strong>Google Ads:</strong> <a href="https://adssettings.google.com/" target="_blank" class="text-blue-600 hover:underline">Google Ad Settings</a></li>
                </ul>

                <h2>7. Impact of Disabling Cookies</h2>
                <p>If you disable cookies, some features of our website may not work properly:</p>
                <ul>
                    <li>You may need to log in repeatedly</li>
                    <li>Your preferences may not be saved</li>
                    <li>Some pages may not load correctly</li>
                    <li>Personalized content may not be available</li>
                    <li>Analytics data may be incomplete</li>
                </ul>

                <h2>8. Mobile Apps</h2>
                <p>If you use our mobile app, we may use similar technologies:</p>
                <ul>
                    <li><strong>App Analytics:</strong> Track app usage and performance</li>
                    <li><strong>Push Notifications:</strong> Send relevant updates and alerts</li>
                    <li><strong>Device Identifiers:</strong> Identify your device for security purposes</li>
                </ul>

                <h2>9. Updates to This Policy</h2>
                <p>We may update this Cookie Policy from time to time to reflect changes in our practices or applicable laws. We will notify you of any material changes by:</p>
                <ul>
                    <li>Posting the updated policy on our website</li>
                    <li>Sending you an email notification</li>
                    <li>Displaying a notice in our app or website</li>
                </ul>

                <h2>10. Contact Us</h2>
                <p>If you have questions about our use of cookies, please contact us:</p>
                <ul>
                    <li><strong>Email:</strong> privacy@marketplace.rs</li>
                    <li><strong>Address:</strong> Knez Mihailova 1, 11000 Belgrade, Serbia</li>
                    <li><strong>Phone:</strong> +381 11 123 4567</li>
                </ul>

                <h2>11. Legal Basis</h2>
                <p>Our use of cookies is based on:</p>
                <ul>
                    <li><strong>Consent:</strong> For non-essential cookies, we obtain your explicit consent</li>
                    <li><strong>Legitimate Interest:</strong> For essential cookies necessary for website functionality</li>
                    <li><strong>Contract Performance:</strong> For cookies required to provide our services</li>
                </ul>

            </div>
        </div>

        <!-- Cookie Settings -->
        <div class="mt-12 bg-blue-50 rounded-lg p-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Manage Your Cookie Preferences</h2>
            <p class="text-gray-600 mb-6">You can update your cookie preferences at any time using the button below:</p>
            <button onclick="showCookieSettings()" 
                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Cookie Settings
            </button>
        </div>
    </div>
</div>

<script>
function showCookieSettings() {
    // This would typically show a cookie consent modal
    alert('Cookie settings modal would open here. In a real implementation, this would show a detailed cookie consent interface.');
}
</script>
@endsection
