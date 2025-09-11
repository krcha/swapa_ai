<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SupportController extends Controller
{
    public function helpCenter()
    {
        $faqs = [
            [
                'category' => 'Getting Started',
                'questions' => [
                    [
                        'question' => 'How do I create an account?',
                        'answer' => 'Click "Register" in the top right corner, fill in your details, verify your email, and you\'re ready to start buying and selling!'
                    ],
                    [
                        'question' => 'How do I verify my phone number?',
                        'answer' => 'Go to your profile settings, enter your Serbian phone number (+381...), and we\'ll send you a verification code via SMS.'
                    ],
                    [
                        'question' => 'What can I sell on this platform?',
                        'answer' => 'We specialize in phones and accessories - smartphones, chargers, earphones, screen protectors, and cases. All items must be in good working condition.'
                    ]
                ]
            ],
            [
                'category' => 'Buying',
                'questions' => [
                    [
                        'question' => 'How do I search for items?',
                        'answer' => 'Use our advanced search with filters for brand, model, condition, price range, and location. You can also browse by categories.'
                    ],
                    [
                        'question' => 'How do I contact a seller?',
                        'answer' => 'Click "Contact Seller" on any listing to start a conversation. You can also call or email directly if the seller has provided contact details.'
                    ],
                    [
                        'question' => 'What payment methods are accepted?',
                        'answer' => 'We support cash on delivery, bank transfers, and secure online payments through our payment partners.'
                    ],
                    [
                        'question' => 'How do I know if a seller is trustworthy?',
                        'answer' => 'Check their verification status, read reviews from other buyers, and look for our trust badges. Always meet in public places for safety.'
                    ]
                ]
            ],
            [
                'category' => 'Selling',
                'questions' => [
                    [
                        'question' => 'How do I create a listing?',
                        'answer' => 'Click "Sell Item" in the top menu, select your category, add photos, describe the condition, set your price, and publish your listing.'
                    ],
                    [
                        'question' => 'What photos should I include?',
                        'answer' => 'Take clear photos from multiple angles, show any wear or damage, include the serial number, and ensure good lighting.'
                    ],
                    [
                        'question' => 'How do I price my item?',
                        'answer' => 'Check similar listings, consider the condition and age of your device, and use our pricing suggestions as a guide.'
                    ],
                    [
                        'question' => 'How do I handle inquiries?',
                        'answer' => 'Respond promptly to messages, answer questions honestly, and be available for potential buyers to view the item.'
                    ]
                ]
            ],
            [
                'category' => 'Safety & Security',
                'questions' => [
                    [
                        'question' => 'How do I stay safe when meeting buyers/sellers?',
                        'answer' => 'Always meet in public places, bring a friend if possible, verify the item works before payment, and trust your instincts.'
                    ],
                    [
                        'question' => 'What if I receive a fake or damaged item?',
                        'answer' => 'Report the issue immediately through our reporting system. We have buyer protection policies and will investigate the matter.'
                    ],
                    [
                        'question' => 'How do I report suspicious activity?',
                        'answer' => 'Use the "Report" button on any listing or user profile, or contact our support team directly with details of the issue.'
                    ]
                ]
            ],
            [
                'category' => 'Account & Billing',
                'questions' => [
                    [
                        'question' => 'How do I update my profile?',
                        'answer' => 'Go to your dashboard, click "Edit Profile", and update your information. Remember to verify any changes to contact details.'
                    ],
                    [
                        'question' => 'How do I change my password?',
                        'answer' => 'In your account settings, click "Change Password", enter your current password and new password, then save changes.'
                    ],
                    [
                        'question' => 'What are the subscription plans?',
                        'answer' => 'We offer free and premium plans. Free users can list up to 3 items per month, while premium users get unlimited listings and additional features.'
                    ]
                ]
            ]
        ];

        return view('support.help-center', compact('faqs'));
    }

    public function contactUs()
    {
        return view('support.contact-us');
    }

    public function submitContact(Request $request)
    {
        // Handle contact form submission
        return redirect()->back()->with('success', 'Message sent successfully!');
    }

    public function safetyTips()
    {
        $tips = [
            [
                'category' => 'Before Meeting',
                'icon' => 'shield-check',
                'tips' => [
                    'Always verify the seller\'s identity and contact information',
                    'Check the item\'s condition and authenticity through photos',
                    'Agree on the meeting location and time in advance',
                    'Bring a friend or family member if possible',
                    'Inform someone about your meeting plans'
                ]
            ],
            [
                'category' => 'Meeting Safely',
                'icon' => 'map-marker',
                'tips' => [
                    'Meet in public places with good lighting and people around',
                    'Popular meeting spots: shopping centers, cafes, bank lobbies',
                    'Avoid meeting at private residences or isolated locations',
                    'Arrive on time and don\'t keep the other person waiting',
                    'Trust your instincts - if something feels wrong, leave'
                ]
            ],
            [
                'category' => 'Inspecting Items',
                'icon' => 'search',
                'tips' => [
                    'Test the device thoroughly before payment',
                    'Check for physical damage, scratches, or wear',
                    'Verify the device turns on and functions properly',
                    'Test all buttons, cameras, speakers, and charging port',
                    'Ask about the device\'s history and any repairs'
                ]
            ],
            [
                'category' => 'Payment Safety',
                'icon' => 'credit-card',
                'tips' => [
                    'Use secure payment methods when possible',
                    'Avoid large cash transactions in public',
                    'Consider bank transfers for high-value items',
                    'Get a receipt or proof of purchase',
                    'Never pay before inspecting the item'
                ]
            ],
            [
                'category' => 'Red Flags to Watch',
                'icon' => 'exclamation-triangle',
                'tips' => [
                    'Seller refuses to meet in person',
                    'Price seems too good to be true',
                    'Seller pressures you to make quick decisions',
                    'Item description doesn\'t match photos',
                    'Seller asks for payment before showing the item'
                ]
            ],
            [
                'category' => 'After the Transaction',
                'icon' => 'check-circle',
                'tips' => [
                    'Keep all communication records and receipts',
                    'Test the item thoroughly at home',
                    'Report any issues immediately',
                    'Leave honest feedback for the seller',
                    'Update your listing status if you\'re the seller'
                ]
            ]
        ];

        return view('support.safety-tips', compact('tips'));
    }

    public function howItWorks()
    {
        $steps = [
            [
                'step' => 1,
                'title' => 'Create Your Account',
                'description' => 'Sign up with your email and phone number. Verify your identity to build trust with other users.',
                'icon' => 'user-plus',
                'details' => [
                    'Register with email and Serbian phone number',
                    'Verify your email address',
                    'Complete SMS verification',
                    'Add profile information and photo'
                ]
            ],
            [
                'step' => 2,
                'title' => 'Browse or List Items',
                'description' => 'Search for phones and accessories, or create your own listing to sell items.',
                'icon' => 'search',
                'details' => [
                    'Use advanced search filters',
                    'Browse by categories and brands',
                    'Create detailed listings with photos',
                    'Set competitive prices'
                ]
            ],
            [
                'step' => 3,
                'title' => 'Connect with Users',
                'description' => 'Message sellers or buyers directly through our secure messaging system.',
                'icon' => 'message-circle',
                'details' => [
                    'Start conversations about items',
                    'Ask questions and negotiate prices',
                    'Arrange meeting times and locations',
                    'Use our safety guidelines'
                ]
            ],
            [
                'step' => 4,
                'title' => 'Meet Safely',
                'description' => 'Meet in public places to inspect items and complete transactions safely.',
                'icon' => 'shield-check',
                'details' => [
                    'Choose safe meeting locations',
                    'Bring a friend if possible',
                    'Test items before payment',
                    'Follow safety guidelines'
                ]
            ],
            [
                'step' => 5,
                'title' => 'Complete Transaction',
                'description' => 'Exchange payment and items, then leave feedback to help the community.',
                'icon' => 'handshake',
                'details' => [
                    'Use secure payment methods',
                    'Get receipts and proof of purchase',
                    'Leave honest feedback',
                    'Update listing status'
                ]
            ]
        ];

        return view('support.how-it-works', compact('steps'));
    }
}
