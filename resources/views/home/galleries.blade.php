@extends('layouts.home')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/galleries_page.css') }}">
@endpush


@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">
                <i class="bi bi-images me-3"></i>GALLERY
            </h1>
            <p class="hero-description">
                Explore our world-class manufacturing facilities, cutting-edge equipment, and quality production processes
            </p>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="filter-section">
        <div class="container">
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">
                    <i class="bi bi-grid-fill me-2"></i>All
                </button>
                <button class="filter-btn" data-filter="distribution-map">
                    <i class="bi bi-pin-map me-2"></i>Distribution Map
                </button>
                <button class="filter-btn" data-filter="certificates">
                    <i class="bi bi-gear-wide-connected me-2"></i>Certificates
                </button>
                <button class="filter-btn" data-filter="catalogue">
                    <i class="bi bi-box-seam me-2"></i>Catalogue
                </button>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallery-section">
        <div class="container">
            <div class="loading">
                <div class="spinner"></div>
                <p class="mt-3">Loading images...</p>
            </div>
            
            <div class="gallery-grid" id="galleryGrid">
                <!-- Facilities -->
                <div class="gallery-item" data-category="distribution-map">
                    <div class="gallery-image">
                        <img src="{{ asset('images/gallery/distribution-map.avif') }}" alt="Distribution Map">
                    </div>
                    <div class="zoom-icon"><i class="bi bi-zoom-in"></i></div>
                    <div class="gallery-overlay">
                        <div class="gallery-title">Distribution</div>
                        <div class="gallery-category">Distribution Map</div>
                    </div>
                </div>

                <div class="gallery-item" data-category="certificates">
                    <div class="gallery-image">
                        <img src="{{ asset('images/gallery/certificates-1.avif') }}" alt="Certificates">
                    </div>
                    <div class="zoom-icon"><i class="bi bi-zoom-in"></i></div>
                    <div class="gallery-overlay">
                        <div class="gallery-title">Standard Tolerance for Investment Casting</div>
                        <div class="gallery-category">Certificates</div>
                    </div>
                </div>
                <div class="gallery-item" data-category="certificates">
                    <div class="gallery-image">
                        <img src="{{ asset('images/gallery/certificates-2.avif') }}" alt="Certificates">
                    </div>
                    <div class="zoom-icon"><i class="bi bi-zoom-in"></i></div>
                    <div class="gallery-overlay">
                        <div class="gallery-title">To Whom It May Concern</div>
                        <div class="gallery-category">Certificates</div>
                    </div>
                </div>
                <div class="gallery-item" data-category="certificates">
                    <div class="gallery-image">
                        <img src="{{ asset('images/gallery/certificates-3.avif') }}" alt="Certificates">
                    </div>
                    <div class="zoom-icon"><i class="bi bi-zoom-in"></i></div>
                    <div class="gallery-overlay">
                        <div class="gallery-title">Certificate of Authority to use the Official API Monogram (License Number: 6D-1463)</div>
                        <div class="gallery-category">Certificates</div>
                    </div>
                </div>
                <div class="gallery-item" data-category="certificates">
                    <div class="gallery-image">
                        <img src="{{ asset('images/gallery/certificates-4.avif') }}" alt="Certificates">
                    </div>
                    <div class="zoom-icon"><i class="bi bi-zoom-in"></i></div>
                    <div class="gallery-overlay">
                        <div class="gallery-title">Certificate of Authority to use the Official API Monogram (License Number: 600-0204)</div>
                        <div class="gallery-category">Certificates</div>
                    </div>
                </div>
                
                <div class="gallery-item" data-category="certificates">
                    <div class="gallery-image">
                        <img src="{{ asset('images/gallery/certificates-5.avif') }}" alt="Certificates">
                    </div>
                    <div class="zoom-icon"><i class="bi bi-zoom-in"></i></div>
                    <div class="gallery-overlay">
                        <div class="gallery-title">Qualification Test Record</div>
                        <div class="gallery-category">Certificates</div>
                    </div>
                </div>

                

                <div class="gallery-item" data-category="catalogue">
                    <div class="gallery-image">
                        <i class="bi bi-robot"></i>
                    </div>
                    <div class="zoom-icon"><i class="bi bi-zoom-in"></i></div>
                    <div class="gallery-overlay">
                        <div class="gallery-title">Valve Cataologue</div>
                        <div class="gallery-category">Catalogue</div>
                    </div>
                </div>

            </div>

            <div class="empty-state" id="emptyState" style="display: none;">
                <i class="bi bi-inbox"></i>
                <h3>No images found</h3>
                <p>Try selecting a different category</p>
            </div>
        </div>
    </section>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content modal-content-gallery">
                <div class="modal-body modal-body-gallery">
                    <button class="btn-close-modal" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                    </button>
                    <div class="modal-image-container">
                        <img src="" id="modalImage" class="modal-image" alt="">
                        <div class="modal-info">
                            <div class="modal-title-text" id="modalTitle"></div>
                            <div class="modal-category-text" id="modalCategory"></div>
                            <a href="{{ asset('images/gallery/catalogue.pdf') }}" target="_blank" class="btn btn-success">See <i class="bi bi-box-arrow-in-up-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
   
@endsection

@push('scripts')
     <script>
        // Filter functionality
        const filterButtons = document.querySelectorAll('.filter-btn');
        const galleryItems = document.querySelectorAll('.gallery-item');
        const emptyState = document.getElementById('emptyState');
        const galleryGrid = document.getElementById('galleryGrid');

        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                const filter = button.getAttribute('data-filter');
                let visibleCount = 0;

                // Filter items
                galleryItems.forEach((item, index) => {
                    const category = item.getAttribute('data-category');
                    
                    if (filter === 'all' || category === filter) {
                        item.style.display = 'block';
                        item.style.animationDelay = `${index * 0.05}s`;
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Show/hide empty state
                if (visibleCount === 0) {
                    galleryGrid.style.display = 'none';
                    emptyState.style.display = 'block';
                } else {
                    galleryGrid.style.display = 'grid';
                    emptyState.style.display = 'none';
                }
            });
        });

        // Image modal
        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        
        galleryItems.forEach(item => {
            item.addEventListener('click', () => {
                const title = item.querySelector('.gallery-title').textContent;
                const category = item.querySelector('.gallery-category').textContent;
                const imgElement = item.querySelector('.gallery-image img');
                
                document.getElementById('modalTitle').textContent = title;
                document.getElementById('modalCategory').textContent = category;
                
                if (imgElement) {
                    document.getElementById('modalImage').src = imgElement.src;
                } else {
                    // If no image, show placeholder
                    document.getElementById('modalImage').src = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="800" height="600"%3E%3Crect fill="%23232526" width="800" height="600"/%3E%3Ctext fill="%23fff" font-family="Arial" font-size="48" x="50%25" y="50%25" text-anchor="middle" dominant-baseline="middle"%3EImage Preview%3C/text%3E%3C/svg%3E';
                }
                
                imageModal.show();
            });
        });

        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                imageModal.hide();
            }
        });
    </script>
@endpush