<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    /**
     * Display a listing of the resource
     */
    public function index(Request $request)
    {
        $query = Listing::where('status', 'active')->with(['user', 'brand', 'category']);
        
        // Apply brand filter (case-insensitive match)
        if ($request->filled('brand') && $request->brand !== 'all') {
            $query->whereHas('brand', function($q) use ($request) {
                $q->where('name', 'LIKE', $request->brand);
            });
        }
        
        // Apply category filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        // Apply carrier status filter
        if ($request->filled('carrier_status') && $request->carrier_status !== 'all') {
            if ($request->carrier_status === 'unlocked') {
                $query->whereNull('carrier');
            } elseif ($request->carrier_status === 'locked') {
                $query->whereNotNull('carrier');
            }
        }
        
        // Apply color filter
        if ($request->filled('color') && $request->color !== 'all') {
            $query->where('color', 'LIKE', '%' . $request->color . '%');
        }
        
        // Apply storage filter
        if ($request->filled('storage') && $request->storage !== 'all') {
            $query->where('storage', $request->storage);
        }
        
        // Apply condition filter
        if ($request->filled('condition') && $request->condition !== 'all') {
            $query->where('condition', $request->condition);
        }
        
        // Apply accessory-specific filters
        if ($request->filled('accessory_type') && $request->accessory_type !== 'all') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->accessory_type);
            });
        }
        
        // Apply price range filter for accessories
        if ($request->filled('price_range') && $request->price_range !== 'all') {
            switch ($request->price_range) {
                case '0-50':
                    $query->whereBetween('price', [0, 50]);
                    break;
                case '50-100':
                    $query->whereBetween('price', [50, 100]);
                    break;
                case '100-200':
                    $query->whereBetween('price', [100, 200]);
                    break;
                case '200+':
                    $query->where('price', '>', 200);
                    break;
            }
        }
        
        // Apply warranty filter
        if ($request->filled('warranty')) {
            // For now, we'll assume all listings have warranty - this can be enhanced later
            $query->where('status', 'active'); // Placeholder logic
        }
        
        // Apply credit cards filter
        if ($request->filled('credit_cards')) {
            // For now, we'll assume all sellers accept credit cards - this can be enhanced later
            $query->where('status', 'active'); // Placeholder logic
        }
        
        // Apply exclude businesses filter
        if ($request->filled('exclude_businesses')) {
            // For now, we'll assume all users are individuals - this can be enhanced later
            $query->where('status', 'active'); // Placeholder logic
        }
        
        // Price range filters
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float)$request->min_price);
        }
        
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float)$request->max_price);
        }
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('brand', function($brandQuery) use ($search) {
                      $brandQuery->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('category', function($categoryQuery) use ($search) {
                      $categoryQuery->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // Sort options
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        // Validate sort direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }
        
        // Always prioritize priority listings first
        $query->orderBy('has_priority_listing', 'desc');
        
        switch ($sortBy) {
            case 'price':
                $query->orderBy('price', $sortDirection);
                break;
            case 'title':
                $query->orderBy('title', $sortDirection);
                break;
            case 'condition':
                $query->orderByRaw("CASE 
                    WHEN condition = 'like_new' THEN 1 
                    WHEN condition = 'excellent' THEN 2 
                    WHEN condition = 'good' THEN 3 
                    WHEN condition = 'fair' THEN 4 
                    ELSE 5 END " . $sortDirection);
                break;
            case 'battery_health':
                $query->orderBy('battery_health', $sortDirection);
                break;
            case 'storage':
                $query->orderBy('storage', $sortDirection);
                break;
            case 'color':
                $query->orderBy('color', $sortDirection);
                break;
            case 'carrier':
                $query->orderBy('carrier', $sortDirection);
                break;
            case 'created_at':
            default:
                $query->orderBy('created_at', $sortDirection);
                break;
        }
        
        $listings = $query->paginate(12)->withQueryString();
        
        // Get CLEAN brand and category lists (just the names)
        $brands = Listing::where('status', 'active')
            ->whereNotNull('brand_id')
            ->with('brand')
            ->get()
            ->pluck('brand.name')
            ->filter()
            ->unique()
            ->sort()
            ->values();
            
        $categories = Listing::where('status', 'active')
            ->whereNotNull('category_id')
            ->with('category')
            ->get()
            ->pluck('category.name')
            ->filter()
            ->unique()
            ->sort()
            ->values();
    
        return view('listings.index', compact('listings', 'brands', 'categories'));
    }

    public function stepFilter(Request $request)
    {
        $step = (int) $request->get('step', 1);
        $carrierStatus = $request->get('carrier_status');
        $carrier = $request->get('carrier');
        $brand = $request->get('brand');
        $model = $request->get('model');

        // Serbian carriers data
        $serbianCarriers = [
            [
                'code' => 'mts',
                'name' => 'MTS',
                'logo' => asset('images/carriers/mts.png')
            ],
            [
                'code' => 'a1',
                'name' => 'A1',
                'logo' => asset('images/carriers/a1.png')
            ],
            [
                'code' => 'yettel',
                'name' => 'Yettel',
                'logo' => asset('images/carriers/yettel.png')
            ],
            [
                'code' => 'other',
                'name' => 'Other',
                'logo' => asset('images/carriers/other.png')
            ]
        ];

        // Brands for unlocked phones
        $brands = [
            [
                'code' => 'apple',
                'name' => 'Apple',
                'logo' => asset('images/brands/apple.png')
            ],
            [
                'code' => 'samsung',
                'name' => 'Samsung',
                'logo' => asset('images/brands/samsung.png')
            ],
            [
                'code' => 'xiaomi',
                'name' => 'Xiaomi',
                'logo' => asset('images/brands/xiaomi.png')
            ],
            [
                'code' => 'google',
                'name' => 'Google',
                'logo' => asset('images/brands/google.png')
            ],
            [
                'code' => 'oneplus',
                'name' => 'OnePlus',
                'logo' => asset('images/brands/oneplus.png')
            ],
            [
                'code' => 'other',
                'name' => 'Other',
                'logo' => asset('images/brands/other.png')
            ]
        ];

        // Get models that have actual listings for the selected brand
        $topModels = $this->getModelsWithListings($brand, $carrierStatus, $carrier);

        // Step 4: Get filtered results
        if ($step == 4) {
            // For unlocked phones, show results directly
            if ($carrierStatus === 'unlocked') {
                // Get actual listings from database
                $query = Listing::where('status', 'active')
                    ->with(['user', 'brand', 'category', 'images'])
                    ->whereHas('brand', function($q) {
                        $q->where('is_active', true);
                    });

                // Filter for unlocked phones (no carrier or empty carrier)
                $query->where(function($q) {
                    $q->whereNull('carrier')->orWhere('carrier', '');
                });

                // Filter by category - only show phones (category_id = 1)
                $query->where('category_id', 1);

                // Filter by brand
                if ($brand && $brand !== 'other') {
                    $query->whereHas('brand', function($q) use ($brand) {
                        $q->where('name', 'LIKE', '%' . ucfirst($brand) . '%');
                    });
                }

                // Filter by model (search in title)
                if ($model && $model !== 'other') {
                    // Convert model code to searchable format
                    $searchModel = str_replace('-', ' ', $model);
                    $searchModel = ucwords($searchModel);
                    // Handle special cases like iPhone, iPad, etc.
                    $searchModel = str_replace('Iphone', 'iPhone', $searchModel);
                    $searchModel = str_replace('Ipad', 'iPad', $searchModel);
                    $query->where('title', 'LIKE', '%' . $searchModel . '%');
                }

                // Additional filters for step-filter results
                if ($request->filled('condition')) {
                    $query->where('condition', $request->condition);
                }
                
                if ($request->filled('min_price')) {
                    $query->where('price', '>=', (float)$request->min_price);
                }
                
                if ($request->filled('max_price')) {
                    $query->where('price', '<=', (float)$request->max_price);
                }

                // Sort options
                $sortBy = $request->get('sort', 'created_at');
                $sortDirection = $request->get('direction', 'desc');
                
                // Validate sort direction
                if (!in_array($sortDirection, ['asc', 'desc'])) {
                    $sortDirection = 'desc';
                }
                
                // Always prioritize priority listings first
                $query->orderBy('has_priority_listing', 'desc');
                
                switch ($sortBy) {
                    case 'price':
                        $query->orderBy('price', $sortDirection);
                        break;
                    case 'title':
                        $query->orderBy('title', $sortDirection);
                        break;
                    case 'condition':
                        $query->orderByRaw("CASE 
                            WHEN condition = 'like_new' THEN 1 
                            WHEN condition = 'excellent' THEN 2 
                            WHEN condition = 'good' THEN 3 
                            WHEN condition = 'fair' THEN 4 
                            ELSE 5 END " . $sortDirection);
                        break;
                    case 'battery_health':
                        $query->orderBy('battery_health', $sortDirection);
                        break;
                    case 'storage':
                        $query->orderBy('storage', $sortDirection);
                        break;
                    case 'color':
                        $query->orderBy('color', $sortDirection);
                        break;
                    case 'carrier':
                        $query->orderBy('carrier', $sortDirection);
                        break;
                    case 'created_at':
                    default:
                        $query->orderBy('created_at', $sortDirection);
                        break;
                }

                $listings = $query->paginate(12);
                
                // Calculate price range
                $minPrice = $listings->min('price') ?? 0;
                $maxPrice = $listings->max('price') ?? 0;
                
                return view('listings.step-filter-clean', compact(
                    'step', 'carrierStatus', 'carrier', 'brand', 'model', 
                    'serbianCarriers', 'brands', 'topModels', 'listings', 
                    'minPrice', 'maxPrice'
                ));
            } else {
                // For locked phones, show model selection
                return view('listings.step-filter-clean', compact(
                    'step', 'carrierStatus', 'carrier', 'brand', 'model', 
                    'serbianCarriers', 'brands', 'topModels'
                ));
            }
        }

        // Step 5: Get filtered results for locked phones
        if ($step == 5) {
            // Get actual listings from database
            $query = Listing::where('status', 'active')
                ->with(['user', 'brand', 'category', 'images'])
                ->whereHas('brand', function($q) {
                    $q->where('is_active', true);
                });

            // Apply filters based on selections
            if ($carrierStatus === 'locked' && $carrier) {
                $query->where('carrier', 'LIKE', $carrier);
            }

            // Filter by category - only show phones (category_id = 1)
            $query->where('category_id', 1);

            // Filter by brand
            if ($brand && $brand !== 'other') {
                $query->whereHas('brand', function($q) use ($brand) {
                    $q->where('name', 'LIKE', '%' . ucfirst($brand) . '%');
                });
            }

            // Filter by model (search in title)
            if ($model && $model !== 'other') {
                // Convert model code to searchable format
                $searchModel = str_replace('-', ' ', $model);
                $searchModel = ucwords($searchModel);
                // Handle special cases like iPhone, iPad, etc.
                $searchModel = str_replace('Iphone', 'iPhone', $searchModel);
                $searchModel = str_replace('Ipad', 'iPad', $searchModel);
                $query->where('title', 'LIKE', '%' . $searchModel . '%');
            }

            // Additional filters for step-filter results
            if ($request->filled('condition')) {
                $query->where('condition', $request->condition);
            }
            
            if ($request->filled('min_price')) {
                $query->where('price', '>=', (float)$request->min_price);
            }
            
            if ($request->filled('max_price')) {
                $query->where('price', '<=', (float)$request->max_price);
            }

            // Sort options
            $sortBy = $request->get('sort', 'created_at');
            $sortDirection = $request->get('direction', 'desc');
            
            // Validate sort direction
            if (!in_array($sortDirection, ['asc', 'desc'])) {
                $sortDirection = 'desc';
            }
            
            // Always prioritize priority listings first
            $query->orderBy('has_priority_listing', 'desc');
            
            switch ($sortBy) {
                case 'price':
                    $query->orderBy('price', $sortDirection);
                    break;
                case 'title':
                    $query->orderBy('title', $sortDirection);
                    break;
                case 'condition':
                    $query->orderByRaw("CASE 
                        WHEN condition = 'like_new' THEN 1 
                        WHEN condition = 'excellent' THEN 2 
                        WHEN condition = 'good' THEN 3 
                        WHEN condition = 'fair' THEN 4 
                        ELSE 5 END " . $sortDirection);
                    break;
                case 'battery_health':
                    $query->orderBy('battery_health', $sortDirection);
                    break;
                case 'storage':
                    $query->orderBy('storage', $sortDirection);
                    break;
                case 'color':
                    $query->orderBy('color', $sortDirection);
                    break;
                case 'carrier':
                    $query->orderBy('carrier', $sortDirection);
                    break;
                case 'created_at':
                default:
                    $query->orderBy('created_at', $sortDirection);
                    break;
            }

            $listings = $query->paginate(12);
            
            // Calculate price range
            $minPrice = $listings->min('price') ?? 0;
            $maxPrice = $listings->max('price') ?? 0;
            
            return view('listings.step-filter-clean', compact(
                'step', 'carrierStatus', 'carrier', 'brand', 'model', 
                'serbianCarriers', 'brands', 'topModels', 'listings', 
                'minPrice', 'maxPrice'
            ));
        }

        return view('listings.step-filter-clean', compact(
            'step', 'carrierStatus', 'carrier', 'brand', 'model', 
            'serbianCarriers', 'brands', 'topModels'
        ));
    }

    /**
     * Get models for selected brand
     */
    public function getModelsForBrand($brand)
    {
        // Get approved models from database
        $approvedModels = \App\Models\ApprovedPhoneModel::getByBrand(ucfirst($brand));
        
        $models = [];
        foreach ($approvedModels as $model) {
            $models[] = [
                'code' => $model->model_slug,
                'name' => $model->model_name,
                'description' => $this->getModelDescription($model->model_name),
                'image' => asset('images/models/' . $model->model_slug . '.png'),
                'price_range' => $this->getModelPriceRange($model->model_name)
            ];
        }
        
        // Add "Other" option to the end of the list
        $models[] = [
            'code' => 'other',
            'name' => 'Other',
            'description' => 'Other model',
            'image' => asset('images/models/other.png'),
            'price_range' => 'Varies'
        ];
        
        return $models;
    }

    /**
     * Get models that have actual listings, ordered from newest to oldest
     */
    private function getModelsWithListings($brand, $carrierStatus, $carrier)
    {
        // Build query for listings
        $query = Listing::where('status', 'active')
            ->with(['brand'])
            ->where('category_id', 1) // Only count phone listings
            ->whereHas('brand', function($q) use ($brand) {
                $q->where('name', 'LIKE', '%' . ucfirst($brand) . '%');
            });

        // Apply carrier filter
        if ($carrierStatus === 'locked' && $carrier) {
            $query->where('carrier', 'LIKE', $carrier);
        } elseif ($carrierStatus === 'unlocked') {
            $query->where(function($q) {
                $q->whereNull('carrier')->orWhere('carrier', '');
            });
        }

        // Get models with listing counts
        $listings = $query->get();
        
        // Group by model and count listings
        $modelCounts = [];
        foreach ($listings as $listing) {
            $title = $listing->title;
            
            // Extract model name from title (e.g., "iPhone 14 Pro" from "iPhone 14 Pro 128GB")
            $modelName = $this->extractModelName($title);
            
            if (!isset($modelCounts[$modelName])) {
                $modelCounts[$modelName] = [
                    'name' => $modelName,
                    'count' => 0,
                    'min_price' => $listing->price,
                    'max_price' => $listing->price
                ];
            }
            
            $modelCounts[$modelName]['count']++;
            $modelCounts[$modelName]['min_price'] = min($modelCounts[$modelName]['min_price'], $listing->price);
            $modelCounts[$modelName]['max_price'] = max($modelCounts[$modelName]['max_price'], $listing->price);
        }

        // Convert to array and sort by model name (newest first)
        $models = array_values($modelCounts);
        
        // Sort by model name (newest first) - iPhone 15 > iPhone 14 > iPhone 13, etc.
        usort($models, function($a, $b) {
            return $this->compareModelNames($b['name'], $a['name']);
        });

        // Format the models
        $formattedModels = [];
        foreach ($models as $model) {
            $formattedModels[] = [
                'code' => strtolower(str_replace(' ', '-', $model['name'])),
                'name' => $model['name'],
                'description' => $model['count'] . ' phone' . ($model['count'] !== 1 ? 's' : '') . ' listed',
                'price_range' => '$' . number_format($model['min_price']) . '-' . number_format($model['max_price']),
                'count' => $model['count']
            ];
        }

        return $formattedModels;
    }

    /**
     * Extract model name from listing title
     */
    private function extractModelName($title)
    {
        // Common patterns for iPhone models
        $patterns = [
            '/iPhone\s+\d+\s+Pro\s+Max/i',
            '/iPhone\s+\d+\s+Pro/i',
            '/iPhone\s+\d+\s+Plus/i',
            '/iPhone\s+\d+\s+mini/i',
            '/iPhone\s+\d+/i'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $title, $matches)) {
                return trim($matches[0]);
            }
        }

        // Fallback: return first part of title
        $parts = explode(' ', $title);
        return $parts[0] . ' ' . ($parts[1] ?? '');
    }

    /**
     * Compare model names for sorting (newest first)
     */
    private function compareModelNames($a, $b)
    {
        // Extract numbers from model names
        preg_match('/(\d+)/', $a, $matchesA);
        preg_match('/(\d+)/', $b, $matchesB);
        
        $numA = isset($matchesA[1]) ? (int)$matchesA[1] : 0;
        $numB = isset($matchesB[1]) ? (int)$matchesB[1] : 0;
        
        // Sort by number (newest first)
        if ($numA !== $numB) {
            return $numA - $numB;
        }
        
        // If same number, sort by full name
        return strcmp($a, $b);
    }

    private function getModelDescription($modelName)
    {
        // Generate descriptions based on model name patterns
        if (str_contains($modelName, 'iPhone')) {
            if (str_contains($modelName, 'Pro Max')) {
                return 'Apple flagship with largest display';
            } elseif (str_contains($modelName, 'Pro')) {
                return 'Apple flagship with Pro features';
            } elseif (str_contains($modelName, 'Plus')) {
                return 'Apple device with larger display';
            } else {
                return 'Apple smartphone';
            }
        } elseif (str_contains($modelName, 'Galaxy')) {
            if (str_contains($modelName, 'Ultra')) {
                return 'Samsung flagship with S Pen';
            } elseif (str_contains($modelName, 'Note')) {
                return 'Samsung device with S Pen';
            } else {
                return 'Samsung Galaxy smartphone';
            }
        } elseif (str_contains($modelName, 'Huawei')) {
            if (str_contains($modelName, 'Mate')) {
                return 'Huawei Mate series device';
            } elseif (str_contains($modelName, 'P')) {
                return 'Huawei P series device';
            } else {
                return 'Huawei smartphone';
            }
        } elseif (str_contains($modelName, 'Mi')) {
            return 'Xiaomi smartphone';
        } elseif (str_contains($modelName, 'Find')) {
            return 'OPPO Find series device';
        } elseif (str_contains($modelName, 'Pixel')) {
            return 'Google Pixel device';
        }
        
        return 'Premium smartphone';
    }

    private function getModelPriceRange($modelName)
    {
        // Generate price ranges based on model name patterns
        if (str_contains($modelName, 'iPhone')) {
            if (str_contains($modelName, '17') || str_contains($modelName, '16')) {
                return '$800-1300';
            } elseif (str_contains($modelName, '15')) {
                return '$700-1200';
            } elseif (str_contains($modelName, '14')) {
                return '$600-1100';
            } elseif (str_contains($modelName, '13')) {
                return '$500-900';
            } elseif (str_contains($modelName, '12')) {
                return '$400-800';
            } elseif (str_contains($modelName, '11')) {
                return '$300-700';
            } else {
                return '$200-600';
            }
        } elseif (str_contains($modelName, 'Galaxy')) {
            if (str_contains($modelName, 'S25') || str_contains($modelName, 'S24')) {
                return '$700-1200';
            } elseif (str_contains($modelName, 'S23')) {
                return '$600-1100';
            } elseif (str_contains($modelName, 'S22')) {
                return '$500-900';
            } elseif (str_contains($modelName, 'S21')) {
                return '$400-800';
            } elseif (str_contains($modelName, 'S20')) {
                return '$300-700';
            } else {
                return '$200-600';
            }
        } elseif (str_contains($modelName, 'Huawei')) {
            if (str_contains($modelName, 'P60') || str_contains($modelName, 'Mate 60')) {
                return '$500-900';
            } elseif (str_contains($modelName, 'P50') || str_contains($modelName, 'Mate 50')) {
                return '$400-800';
            } elseif (str_contains($modelName, 'P40') || str_contains($modelName, 'Mate 40')) {
                return '$300-700';
            } else {
                return '$200-600';
            }
        } elseif (str_contains($modelName, 'Mi')) {
            if (str_contains($modelName, '15')) {
                return '$400-800';
            } elseif (str_contains($modelName, '14')) {
                return '$350-700';
            } elseif (str_contains($modelName, '13')) {
                return '$300-600';
            } else {
                return '$200-500';
            }
        } elseif (str_contains($modelName, 'Find')) {
            if (str_contains($modelName, 'X7')) {
                return '$600-1000';
            } elseif (str_contains($modelName, 'X6')) {
                return '$500-900';
            } elseif (str_contains($modelName, 'X5')) {
                return '$400-800';
            } else {
                return '$300-700';
            }
        } elseif (str_contains($modelName, 'Pixel')) {
            if (str_contains($modelName, '10') || str_contains($modelName, '9')) {
                return '$600-1000';
            } elseif (str_contains($modelName, '8')) {
                return '$500-900';
            } elseif (str_contains($modelName, '7')) {
                return '$400-800';
            } else {
                return '$200-600';
            }
        }
        
        return '$300-800';
    }

    private function getOldModels()
    {
        $allModels = [
            'apple' => [
                [
                    'code' => 'iphone-15-pro',
                    'name' => 'iPhone 15 Pro',
                    'description' => 'Latest Apple flagship with titanium',
                    'image' => asset('images/models/iphone-15-pro.png'),
                    'price_range' => '$900-1300'
                ],
                [
                    'code' => 'iphone-14-pro',
                    'name' => 'iPhone 14 Pro',
                    'description' => 'Apple flagship with Dynamic Island',
                    'image' => asset('images/models/iphone-14-pro.png'),
                    'price_range' => '$800-1200'
                ],
                [
                    'code' => 'iphone-13',
                    'name' => 'iPhone 13',
                    'description' => 'Popular Apple model',
                    'image' => asset('images/models/iphone-13.png'),
                    'price_range' => '$500-800'
                ],
                [
                    'code' => 'iphone-12',
                    'name' => 'iPhone 12',
                    'description' => 'Reliable Apple device',
                    'image' => asset('images/models/iphone-12.png'),
                    'price_range' => '$400-600'
                ],
                [
                    'code' => 'iphone-se',
                    'name' => 'iPhone SE',
                    'description' => 'Compact Apple phone',
                    'image' => asset('images/models/iphone-se.png'),
                    'price_range' => '$300-500'
                ]
            ],
            'samsung' => [
                [
                    'code' => 'samsung-s24-ultra',
                    'name' => 'Samsung Galaxy S24 Ultra',
                    'description' => 'Latest Samsung flagship',
                    'image' => asset('images/models/samsung-s24-ultra.png'),
                    'price_range' => '$800-1200'
                ],
                [
                    'code' => 'samsung-s23-ultra',
                    'name' => 'Samsung Galaxy S23 Ultra',
                    'description' => 'Premium Android device',
                    'image' => asset('images/models/samsung-s23-ultra.png'),
                    'price_range' => '$700-1000'
                ],
                [
                    'code' => 'samsung-s22',
                    'name' => 'Samsung Galaxy S22',
                    'description' => 'Compact Samsung flagship',
                    'image' => asset('images/models/samsung-s22.png'),
                    'price_range' => '$500-800'
                ],
                [
                    'code' => 'samsung-a54',
                    'name' => 'Samsung Galaxy A54',
                    'description' => 'Mid-range Samsung device',
                    'image' => asset('images/models/samsung-a54.png'),
                    'price_range' => '$300-500'
                ],
                [
                    'code' => 'samsung-note-20',
                    'name' => 'Samsung Galaxy Note 20',
                    'description' => 'Samsung with S Pen',
                    'image' => asset('images/models/samsung-note-20.png'),
                    'price_range' => '$400-700'
                ]
            ],
            'xiaomi' => [
                [
                    'code' => 'xiaomi-14-pro',
                    'name' => 'Xiaomi 14 Pro',
                    'description' => 'Latest Xiaomi flagship',
                    'image' => asset('images/models/xiaomi-14-pro.png'),
                    'price_range' => '$600-900'
                ],
                [
                    'code' => 'xiaomi-13-pro',
                    'name' => 'Xiaomi 13 Pro',
                    'description' => 'High-end Android',
                    'image' => asset('images/models/xiaomi-13-pro.png'),
                    'price_range' => '$400-600'
                ],
                [
                    'code' => 'xiaomi-12',
                    'name' => 'Xiaomi 12',
                    'description' => 'Premium Xiaomi device',
                    'image' => asset('images/models/xiaomi-12.png'),
                    'price_range' => '$350-550'
                ],
                [
                    'code' => 'xiaomi-redmi-note-12',
                    'name' => 'Xiaomi Redmi Note 12',
                    'description' => 'Budget-friendly Xiaomi',
                    'image' => asset('images/models/xiaomi-redmi-note-12.png'),
                    'price_range' => '$200-350'
                ],
                [
                    'code' => 'xiaomi-poco-x5',
                    'name' => 'Xiaomi POCO X5',
                    'description' => 'Gaming-focused Xiaomi',
                    'image' => asset('images/models/xiaomi-poco-x5.png'),
                    'price_range' => '$250-400'
                ]
            ],
            'google' => [
                [
                    'code' => 'google-pixel-8-pro',
                    'name' => 'Google Pixel 8 Pro',
                    'description' => 'Latest Google flagship',
                    'image' => asset('images/models/google-pixel-8-pro.png'),
                    'price_range' => '$700-1000'
                ],
                [
                    'code' => 'google-pixel-7',
                    'name' => 'Google Pixel 7',
                    'description' => 'Pure Android experience',
                    'image' => asset('images/models/google-pixel-7.png'),
                    'price_range' => '$450-650'
                ],
                [
                    'code' => 'google-pixel-6a',
                    'name' => 'Google Pixel 6a',
                    'description' => 'Budget Google device',
                    'image' => asset('images/models/google-pixel-6a.png'),
                    'price_range' => '$300-450'
                ],
                [
                    'code' => 'google-pixel-5',
                    'name' => 'Google Pixel 5',
                    'description' => 'Compact Google phone',
                    'image' => asset('images/models/google-pixel-5.png'),
                    'price_range' => '$250-400'
                ],
                [
                    'code' => 'google-pixel-4a',
                    'name' => 'Google Pixel 4a',
                    'description' => 'Affordable Google device',
                    'image' => asset('images/models/google-pixel-4a.png'),
                    'price_range' => '$200-350'
                ]
            ],
            'oneplus' => [
                [
                    'code' => 'oneplus-11',
                    'name' => 'OnePlus 11',
                    'description' => 'Latest OnePlus flagship',
                    'image' => asset('images/models/oneplus-11.png'),
                    'price_range' => '$600-900'
                ],
                [
                    'code' => 'oneplus-10-pro',
                    'name' => 'OnePlus 10 Pro',
                    'description' => 'Premium OnePlus device',
                    'image' => asset('images/models/oneplus-10-pro.png'),
                    'price_range' => '$500-750'
                ],
                [
                    'code' => 'oneplus-9',
                    'name' => 'OnePlus 9',
                    'description' => 'Flagship OnePlus phone',
                    'image' => asset('images/models/oneplus-9.png'),
                    'price_range' => '$400-600'
                ],
                [
                    'code' => 'oneplus-nord-3',
                    'name' => 'OnePlus Nord 3',
                    'description' => 'Mid-range OnePlus',
                    'image' => asset('images/models/oneplus-nord-3.png'),
                    'price_range' => '$300-500'
                ],
                [
                    'code' => 'oneplus-8t',
                    'name' => 'OnePlus 8T',
                    'description' => 'Reliable OnePlus device',
                    'image' => asset('images/models/oneplus-8t.png'),
                    'price_range' => '$250-450'
                ]
            ]
        ];

        // Return models for selected brand, or all models if no brand selected
        if ($brand && isset($allModels[$brand])) {
            $models = $allModels[$brand];
            // Add "Other" option to the end of the list
            $models[] = [
                'code' => 'other',
                'name' => 'Other',
                'description' => 'Other model',
                'image' => asset('images/models/other.png'),
                'price_range' => 'Varies'
            ];
            return $models;
        }

        // If no brand selected, return a mix of popular models
        return [
            [
                'code' => 'iphone-14-pro',
                'name' => 'iPhone 14 Pro',
                'description' => 'Latest Apple flagship',
                'image' => asset('images/models/iphone-14-pro.png'),
                'price_range' => '$800-1200'
            ],
            [
                'code' => 'samsung-s23-ultra',
                'name' => 'Samsung Galaxy S23 Ultra',
                'description' => 'Premium Android device',
                'image' => asset('images/models/samsung-s23-ultra.png'),
                'price_range' => '$700-1000'
            ],
            [
                'code' => 'iphone-13',
                'name' => 'iPhone 13',
                'description' => 'Popular Apple model',
                'image' => asset('images/models/iphone-13.png'),
                'price_range' => '$500-800'
            ],
            [
                'code' => 'xiaomi-13-pro',
                'name' => 'Xiaomi 13 Pro',
                'description' => 'High-end Android',
                'image' => asset('images/models/xiaomi-13-pro.png'),
                'price_range' => '$400-600'
            ],
            [
                'code' => 'google-pixel-7',
                'name' => 'Google Pixel 7',
                'description' => 'Pure Android experience',
                'image' => asset('images/models/google-pixel-7.png'),
                'price_range' => '$450-650'
            ],
            [
                'code' => 'other',
                'name' => 'Other',
                'description' => 'Other model',
                'image' => asset('images/models/other.png'),
                'price_range' => 'Varies'
            ]
        ];
    }

    /**
     * Show the form for creating a new listing
     */
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $approvedModels = \App\Models\ApprovedPhoneModel::getModelsGroupedByBrand();
        
        return view('listings.create', compact('categories', 'brands', 'approvedModels'));
    }

    /**
     * Store a newly created listing
     */
    public function store(Request $request)
    {
        // Get category to determine validation rules
        $categoryId = $request->input('category_id');
        $isPhoneCategory = $categoryId == 1; // Assuming category 1 is phones
        
        $validationRules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'condition' => 'required|in:new,like_new,good,fair',
            'images' => 'required|array|min:3|max:5',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
        
        // Add phone-specific validation rules
        if ($isPhoneCategory) {
            $validationRules['battery_percentage'] = 'required|integer|min:0|max:100';
            $validationRules['model_name'] = 'required|string';
            $validationRules['model_code'] = 'required|string';
            $validationRules['carrier'] = 'nullable|string|in:telenor,vip,mts,yettel,unlocked';
            $validationRules['screen_condition'] = 'required|string|in:perfect,good,fair,poor';
            $validationRules['body_condition'] = 'required|string|in:perfect,good,fair,poor';
            $validationRules['functionality'] = 'required|string|in:fully_working,mostly_working,partially_working,needs_repair';
        }
        
        $request->validate($validationRules);

        // Validate that the model is approved (only for phones)
        if ($isPhoneCategory) {
            // Get the brand name from the selected brand
            $brand = \App\Models\Brand::find($request->input('brand_id'));
            $brandName = $brand ? $brand->name : 'Apple'; // Default to Apple if not found
            
            // Map brand names to match approved models table
            $brandMapping = [
                'Apple' => 'Apple iPhone',
                'Samsung' => 'Samsung Galaxy (S/Note)',
                'Google' => 'Google Pixel',
                'Huawei' => 'Huawei',
                'Xiaomi' => 'Xiaomi',
                'OPPO' => 'OPPO (Find series / flagship)',
                'OnePlus' => 'OPPO (Find series / flagship)'
            ];
            
            $mappedBrandName = $brandMapping[$brandName] ?? $brandName;
            
            $isApproved = \App\Models\ApprovedPhoneModel::isModelApproved(
                $mappedBrandName, 
                $request->input('model_code')
            );
            
            if (!$isApproved) {
                return redirect()->back()->withErrors(['model' => 'Selected model is not approved for listing.']);
            }
        }

        // Check if user can create listing (subscription check)
        if (!auth()->user()->canCreateListing()) {
            return redirect()->back()->with('error', 'You have reached your listing limit. Please upgrade your subscription.');
        }

        $listingData = [
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'condition' => $request->condition,
            'contact_preference' => 'both', // Default contact preference
            'expires_at' => now()->addDays(30), // 30-day expiration
            'status' => 'active', // Default status
        ];
        
        // Add phone-specific fields
        if ($isPhoneCategory) {
            $listingData['battery_health'] = $request->battery_percentage;
            $listingData['title'] = $request->title . ' (' . $request->model_name . ')';
            $listingData['screen_condition'] = $request->screen_condition;
            $listingData['body_condition'] = $request->body_condition;
            $listingData['functionality'] = $request->functionality;
            if ($request->carrier) {
                $listingData['carrier'] = $request->carrier;
            }
        }
        
        $listing = auth()->user()->listings()->create($listingData);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('listings', 'public');
                $imageUrl = asset('storage/' . $path);
                $listing->images()->create([
                    'image_path' => $path,
                    'image_url' => $imageUrl
                ]);
            }
        }

        return redirect()->route('home')
            ->with('success', 'Listing created successfully!');
    }

    /**
     * Display the specified listing
     */
    public function show(Listing $listing)
    {
        $listing->load(['user', 'category', 'brand', 'images']);
        
        return view('listings.show', compact('listing'));
    }
}
