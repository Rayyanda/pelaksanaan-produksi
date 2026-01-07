@extends('layouts.home')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/products_page.css') }}">
@endpush

@section('content')
    <div class="container my-5">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="bi bi-box-seam-fill me-2"></i>Our Products</h1>
            <p>Select the category to see detail</p>
        </div>

        <!-- Categories Container -->
        <div class="categories-container">
            <!-- Category 1: Investment Casting -->
            <div class="product-category-card category-1" id="product-category-card-1" data-bs-toggle="modal" data-bs-target="#modalCategory1">
                <div class="category-icon">
                    <i class="bi bi-gear-wide-connected"></i>
                </div>
                <div class="product-count">
                    <i class="bi bi-box me-1"></i>16 Products
                </div>
                <h2 class="product-category-title">Investment Casting</h2>
                <p class="category-description">
                    Investment casting is a precision casting process that produces complex metal components with high
                    dimensional accuracy and smooth surface finishes.
                </p>
                <button class="btn-view-products" id="btn-ic">
                    <i class="bi bi-eye-fill me-2"></i>See Products
                </button>
            </div>

            <!-- Category 2: Spare Parts -->
            <div class="product-category-card category-2" id="product-category-card-2" data-bs-toggle="modal" data-bs-target="#modalCategory2">
                <div class="category-icon">
                    <i class="bi bi-tools"></i>
                </div>
                <div class="product-count">
                    <i class="bi bi-box me-1"></i>10 Products
                </div>
                <h2 class="product-category-title">Sand Casting</h2>
                <p class="category-description">
                    Sand casting is a manufacturing process that pours molten metal into a sand mold to create a component or workpiece.
                </p>
                <button class="btn-view-products" id="btn-sc">
                    <i class="bi bi-eye-fill me-2"></i>See Products
                </button>
            </div>

            <!-- Category 3: Valve -->
            <div class="product-category-card category-3" id="product-category-card-3" data-bs-toggle="modal" data-bs-target="#modalCategory3">
                <div class="category-icon">
                    <i class="bi bi-boxes"></i>
                </div>
                <div class="product-count">
                    <i class="bi bi-box me-1"></i>3 Products
                </div>
                <h2 class="product-category-title">Valve</h2>
                <p class="category-description">
                    a mechanical device that controls the flow of fluid (liquid or gas) by opening, closing, or regulating the flow gap
                </p>
                <button class="btn-view-products" id="btn-v">
                    <i class="bi bi-eye-fill me-2"></i>See Products
                </button>
            </div>
        </div>
    </div>


    <!-- Modal Category 1: Investment Casting -->
    <div class="modal fade" id="modalCategory1" tabindex="-1" aria-labelledby="modalCategory1Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header modal-header-1">
                    <h5 class="modal-title" id="modalCategory1Label">
                        <i class="bi bi-gear-wide-connected me-2"></i>Investment Casting
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Search Box -->
                    <div class="search-box position-relative">
                        <input type="text" class="form-control" placeholder="Search product..."
                            onkeyup="searchProducts(this, 'products1')">
                        <i class="bi bi-search search-icon"></i>
                    </div>

                    <!-- Products Grid -->
                    <div class="products-grid" id="products1">
                       
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Category 2: Sand Casting -->
    <div class="modal fade" id="modalCategory2" tabindex="-1" aria-labelledby="modalCategory2Label"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header modal-header-2">
                    <h5 class="modal-title" id="modalCategory2Label">
                        <i class="bi bi-tools me-2"></i>Sand Casting
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Search Box -->
                    <div class="search-box position-relative">
                        <input type="text" class="form-control" placeholder="Cari produk..."
                            onkeyup="searchProducts(this, 'products2')">
                        <i class="bi bi-search search-icon"></i>
                    </div>

                    <!-- Products Grid -->
                    <div class="products-grid" id="products2">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Category 3: Valve -->
    <div class="modal fade" id="modalCategory3" tabindex="-1" aria-labelledby="modalCategory3Label"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header modal-header-3">
                    <h5 class="modal-title" id="modalCategory3Label">
                        <i class="bi bi-boxes me-2"></i>Valve
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Search Box -->
                    <div class="search-box position-relative">
                        <input type="text" class="form-control" placeholder="Cari produk..."
                            onkeyup="searchProducts(this, 'products3')">
                        <i class="bi bi-search search-icon"></i>
                    </div>

                    <!-- Products Grid -->
                    <div class="products-grid" id="products3">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            var investmentCasting = [
                {
                    "image": "{{ asset('images/products/investment-casting/investment-casting-1.avif') }}",
                    'name': '',
                    'dimension': '509 mm X 375 mm X 96 mm',
                    'material': 'Special Stainless Steel',
                    'weight': '83 Kg'
                }, 
                {
                    "image": "{{ asset('images/products/investment-casting/investment-casting-2.avif') }}",
                    'name': '',
                    'dimension': '478 mm X 257 mm',
                    'material': 'Special Stainless Steel',
                    'weight': '37 Kg'
                },
                {
                    "image": "{{ asset('images/products/investment-casting/investment-casting-3.png') }}",
                    'name': '',
                    'dimension': '536 mm X 219 mm',
                    'material': 'Special Stainless Steel',
                    'weight': '70 Kg'
                },
                {
                    "image": "{{ asset('images/products/investment-casting/investment-casting-4.png') }}",
                    'name': '',
                    'dimension': '547,5 mm X 300 mm',
                    'material': 'Special Stainless Steel',
                    'weight': '34,4 Kg'
                },
                {
                    "image": "{{ asset('images/products/investment-casting/investment-casting-5.png') }}",
                    'name': '',
                    'dimension': '12.32 mm X 4.6 mm',
                    'material': 'Special Stainless Steel',
                    'weight': '34,4 Kg'
                },
                {
                    "image": "{{ asset('images/products/investment-casting/investment-casting-6.png') }}",
                    'name': '',
                    'dimension': '526 X 178 mm',
                    'material': 'Special Stainless Steel',
                    'weight': '47 Kg'
                },
                {
                    "image": "{{ asset('images/products/investment-casting/investment-casting-7.png') }}",
                    'name': '',
                    'dimension': '736.6 mm X 276 mm',
                    'material': 'Special Stainless Steel',
                    'weight': '60 Kg'
                },
                {
                    "image": "{{ asset('images/products/investment-casting/investment-casting-8.png') }}",
                    'name': '',
                    'dimension': '160 mm X 120 mm',
                    'material': 'Copper Base',
                    'weight': '13 Kg'
                },
                {
                    "image": "{{ asset('images/products/investment-casting/investment-casting-9.avif') }}",
                    'name': '',
                    'dimension': '308 mm X 284 mm',
                    'material': 'Duplex',
                    'weight': '7.9 Kg'
                },
                {
                    "image": "{{ asset('images/products/investment-casting/investment-casting-10.png') }}",
                    'name': '',
                    'dimension': '293 mm X 125 mm',
                    'material': 'Duplex Material',
                    'weight': '18.6 Kg'
                },
                {
                    "image": "{{ asset('images/products/investment-casting/investment-casting-11.avif') }}",
                    'name': '',
                    'dimension': '444 mm X 302 mm',
                    'material': 'Heat Resisting Material',
                    'weight': '21 Kg'
                },
                {
                    "image": "{{ asset('images/products/investment-casting/investment-casting-12.avif') }}",
                    'name': '',
                    'dimension': '11.4 mm X 21 mm',
                    'material': 'Duplex',
                    'weight': '1.4 Kg'
                },
                {
                    "image": "{{ asset('images/products/investment-casting/investment-casting-13.avif') }}",
                    'name': '',
                    'dimension': '253 mm X 83 mm',
                    'material': 'Super Duplex',
                    'weight': '7 Kg'
                },
                {
                    "image": "{{ asset('images/products/investment-casting/investment-casting-14.avif') }}",
                    'name': '',
                    'dimension': '100 mm X 6.5 mm',
                    'material': 'Copper Base',
                    'weight': '0.8 Kg'
                },
                {
                    "image": "{{ asset('images/products/investment-casting/investment-casting-15.avif') }}",
                    'name': '',
                    'dimension': '31 mm X 20.3 mm',
                    'material': 'Duplex',
                    'weight': '7 Kg'
                },
                {
                    "image": "{{ asset('images/products/investment-casting/investment-casting-16.avif') }}",
                    'name': '',
                    'dimension': '290 mm X 142 mm',
                    'material': 'Heat Resisting Material',
                    'weight': '29 Kg'
                },
            ];

            var sandCasting = [
                {
                    "image": "{{ asset('images/products/sand-casting/sand-casting-1.avif') }}",
                    'name': '',
                    'dimension': '1250mm X 250mm X 50mm',
                    'material': 'BS 3100',
                    'weight': '141 Kg'
                }, 
                {
                    "image": "{{ asset('images/products/sand-casting/sand-casting-2.avif') }}",
                    'name': '',
                    'dimension': '750mm X 280mm',
                    'material': 'Super Duplex',
                    'weight': '200 Kg'
                },
                {
                    "image": "{{ asset('images/products/sand-casting/sand-casting-3.avif') }}",
                    'name': '',
                    'dimension': '600mm X 300mm X 300mm',
                    'material': 'Alloy Steel',
                    'weight': '95 Kg'
                },
                {
                    "image": "{{ asset('images/products/sand-casting/sand-casting-4.avif') }}",
                    'name': '',
                    'dimension': '300mm X 250mm X 30mm',
                    'material': 'Alloy Steel',
                    'weight': '35 Kg'
                },
                {
                    "image": "{{ asset('images/products/sand-casting/sand-casting-5.avif') }}",
                    'name': '',
                    'dimension': '850mm X 290mm',
                    'material': 'Ni Hard/27 Hcr',
                    'weight': '500 Kg'
                },
                {
                    "image": "{{ asset('images/products/sand-casting/sand-casting-6.avif') }}",
                    'name': '',
                    'dimension': '500mm X 300 mm X 200mm',
                    'material': 'ScSiMn1H',
                    'weight': '45 Kg'
                },
                {
                    "image": "{{ asset('images/products/sand-casting/sand-casting-7.avif') }}",
                    'name': '',
                    'dimension': '650mm X 400mm X 200mm',
                    'material': 'ScSiMn1H',
                    'weight': '60 Kg'
                },
                {
                    "image": "{{ asset('images/products/sand-casting/sand-casting-8.avif') }}",
                    'name': '',
                    'dimension': '800mm X 700mm X 60mm',
                    'material': 'Gs30Mn5',
                    'weight': '157 Kg'
                },
                {
                    "image": "{{ asset('images/products/sand-casting/sand-casting-9.avif') }}",
                    'name': '',
                    'dimension': '',
                    'material': 'A 216 WCB',
                    'weight': '45 Kg'
                },
                {
                    "image": "{{ asset('images/products/sand-casting/sand-casting-10.avif') }}",
                    'name': '',
                    'dimension': '',
                    'material': 'A 216 WCB',
                    'weight': '35 Kg'
                },
            ]

            var valve = [
                {
                    "image": "{{ asset('images/products/valve/valve-1.avif') }}",
                    'name': 'Check Valve',
                    'dimension': '6" X Class 300',
                    'material': 'A 216 WCB',
                    'weight': '135 Kg',
                    'size' : '2"-12"',
                    'class' : '150, 300, 600, 900'
                }, 
                {
                    "image": "{{ asset('images/products/valve/valve-2.avif') }}",
                    'name': 'Gate Valve',
                    'dimension': '3" x Class 600',
                    'material': 'A 216 WCB',
                    'weight': '71 Kg',
                    'size' : '2"-12"',
                    'class' : '150, 300, 600, 900'
                },
                {
                    "image": "{{ asset('images/products/valve/valve-3.avif') }}",
                    'name': 'Globe Valve',
                    'dimension': '4" X Class 150',
                    'material': 'A 216 WCB',
                    'weight': '80 Kg',
                    'size' : '2"-12"',
                    'class' : '150, 300, 600, 900'
                }
            ];

            var product1Container = document.getElementById('products1');
            var product2Container = document.getElementById('products2');
            var products3Container = document.getElementById('products3');

            
            document.getElementById('btn-ic').addEventListener('click', investmentCastingShow);
            document.getElementById('product-category-card-1').addEventListener('click', investmentCastingShow);

            function investmentCastingShow(){
                product1Container.innerHTML = '';
                investmentCasting.forEach(function(product) {
                    var pd = `
                        <div class="product-card">
                            <img class="product-image" src="${product.image}" alt="">
                            <div class="product-name">${product.name}</div>
                            <div class="product-code">${product.dimension}</div>
                            <div class="product-description">
                                Material: ${product.material} <br>
                                Weight: ${product.weight}
                            </div>
                        </div>
                    `;
                    product1Container.innerHTML += pd;
                });
            }

            // Load Sand Casting Products
            document.getElementById('btn-sc').addEventListener('click', sandCastingShow);
            document.getElementById('product-category-card-2').addEventListener('click', sandCastingShow);

            function sandCastingShow(){
                product2Container.innerHTML = '';
                sandCasting.forEach(function(product) {
                    var pd = `
                        <div class="product-card">
                            <img class="product-image" src="${product.image}" alt="">
                            <div class="product-name">${product.name}</div>
                            <div class="product-code">${product.dimension}</div>
                            <div class="product-description">
                                Material: ${product.material} <br>
                                Weight: ${product.weight}
                            </div>
                        </div>
                    `;
                    product2Container.innerHTML += pd;
                });
            }

            // Load Valve Products
            document.getElementById('btn-v').addEventListener('click', valveShow);
            document.getElementById('product-category-card-3').addEventListener('click', valveShow);

            function valveShow(){
                products3Container.innerHTML = '';
                valve.forEach(function(product) {
                    var pd = `
                        <div class="product-card">
                            <img class="product-image" src="${product.image}" alt="">
                            <div class="product-name">${product.name}</div>
                            <div class="product-code">${product.dimension}</div>
                            <div class="product-description">
                                Material: ${product.material} <br>
                                Weight: ${product.weight} <br>
                                size : ${product.size} <br>
                                class : ${product.class}
                            </div>
                        </div>
                    `;
                    products3Container.innerHTML += pd;
                });
            }

        });


        // Search Products Function
        function searchProducts(input, containerId) {
            const searchText = input.value.toLowerCase();
            const container = document.getElementById(containerId);
            const products = container.getElementsByClassName('product-card');

            let visibleCount = 0;

            for (let product of products) {
                const productName = product.querySelector('.product-name').textContent.toLowerCase();
                const productCode = product.querySelector('.product-code').textContent.toLowerCase();
                const productDesc = product.querySelector('.product-description').textContent.toLowerCase();

                if (productName.includes(searchText) || productCode.includes(searchText) || productDesc.includes(
                    searchText)) {
                    product.style.display = 'block';
                    visibleCount++;
                } else {
                    product.style.display = 'none';
                }
            }

            // Show empty state if no results
            let emptyState = container.querySelector('.empty-state');
            if (visibleCount === 0 && !emptyState) {
                emptyState = document.createElement('div');
                emptyState.className = 'empty-state';
                emptyState.innerHTML = `
                    <i class="bi bi-search"></i>
                    <p>No pruduct found</p>
                `;
                container.appendChild(emptyState);
            } else if (visibleCount > 0 && emptyState) {
                emptyState.remove();
            }
        }

        // View Product Detail Function
        function viewProductDetail(productName) {
            alert(`Melihat detail produk: ${productName}\n\nFungsi ini akan dihubungkan dengan halaman detail produk.`);
        }
    </script>
@endpush
