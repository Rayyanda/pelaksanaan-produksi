<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT.METINCA PRIMA INDUSTRIAL WORKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/homepage.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/menu-modal.css') }}">
    @stack('styles')
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fs-5" href="#">
                PT. METINCA PRIMA INDUSTRIAL WORKS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home.main') ? 'active' : '' }}"
                            href="/home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/home#profile">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/home/divisions">Divisions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home.products') }}">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home.facilities') }}">Facilities</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home.gallery') }}">Gallery</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-bs-target="#metincaAppsModal" data-bs-toggle="modal" href="#"><i
                                class="bi bi-grid-3x3-gap-fill me-2"></i> Application</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>PT. METINCA PRIMA INDUSTRIAL WORKS</h5>
                    <p>
                        The #1 Precision Casting and Tooling Facility in Indonesia
                    </p>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Products</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#">Investment Casting</a></li>
                        <li class="mb-2"><a href="#">Sand Casting</a></li>
                        <li class="mb-2"><a href="#">Valve</a></li>

                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Contact</h5>

                    <p><i class="fas fa-phone me-2"></i>+62 21 1234 5678</p>
                    <p><i class="fas fa-envelope me-2"></i>info@metinca-prima.co.id</p>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,0.1);">
            <div class="text-center pt-3">
                <p>&copy; 2025 Metinca. All Rights Reserved.</p>
            </div>
        </div>
    </footer>
    <!-- Modal Menu-->
    <div class="modal fade" id="metincaAppsModal" tabindex="-1" aria-labelledby="metincaAppsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="metincaAppsModalLabel">
                        <i class="bi bi-app-indicator me-2"></i>Metinca Apps
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    {{-- TOp Management --}}

                    <div class="category-card top-management">
                        <div class="category-title">
                            <i class="bi bi-star-fill"></i>
                            Top Management
                        </div>

                        <!-- 8.1 - 8.3 (no sub-category, langsung menu) -->
                        <div class="menu-grid">
                            <a href="#" class="menu-item">
                                <div class="menu-icon">
                                    <i class="bi bi-shield-lock-fill"></i>
                                </div>
                                <div class="menu-text">Super Admin</div>
                            </a>
                            <a href="#" class="menu-item">
                                <div class="menu-icon">
                                    <i class="bi bi-speedometer2"></i>
                                </div>
                                <div class="menu-text">Dashboard Kinerja Pabrik</div>
                            </a>
                            <a href="#" class="menu-item">
                                <div class="menu-icon">
                                    <i class="bi bi-diagram-3-fill"></i>
                                </div>
                                <div class="menu-text">Monitoring Per Cabang</div>
                            </a>
                            <a href="#" class="menu-item">
                                <div class="menu-icon">
                                    <i class="bi bi-display-fill"></i>
                                </div>
                                <div class="menu-text">Monitoring Terpusat</div>
                            </a>
                        </div>
                    </div>

                    <!-- HR Category -->
                    <div class="category-card">
                        <div class="category-title">
                            <i class="bi bi-people-fill"></i>
                            Human Resources
                        </div>
                        <div class="menu-grid">
                            <a href="http://perekrutan.test/login" class="menu-item">
                                <div class="menu-icon"
                                    style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                                    <i class="bi bi-person-plus-fill"></i>
                                </div>
                                <div class="menu-text">Recruitment</div>
                            </a>
                            <a href="#" class="menu-item">
                                <div class="menu-icon"
                                    style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                                    <i class="bi bi-person-badge-fill"></i>
                                </div>
                                <div class="menu-text">Placement</div>
                            </a>
                            <a href="#" class="menu-item">
                                <div class="menu-icon"
                                    style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                                    <i class="bi bi-arrow-up-circle-fill"></i>
                                </div>
                                <div class="menu-text">Job Promotion</div>
                            </a>
                            <a href="http://training-karyawan.test/login" class="menu-item">
                                <div class="menu-icon"
                                    style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                                    <i class="bi bi-diagram-3-fill"></i>
                                </div>
                                <div class="menu-text">Development</div>
                            </a>
                            <a href="http://mutasi-app.test/login" class="menu-item">
                                <div class="menu-icon"
                                    style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                                    <i class="bi bi-arrow-left-right"></i>
                                </div>
                                <div class="menu-text">Mutation</div>
                            </a>
                            <a href="http://pension.test/login" class="menu-item">
                                <div class="menu-icon"
                                    style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                                <div class="menu-text">Pension</div>
                            </a>
                            <a href="http://leave.test/login" class="menu-item">
                                <div class="menu-icon"
                                    style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                                    <i class="bi bi-calendar-x-fill"></i>
                                </div>
                                <div class="menu-text">Leave</div>
                            </a>
                        </div>
                    </div>

                    <!-- General Affair Category -->
                    <div class="category-card" style="background: linear-gradient(135deg, #134e5e 0%, #71b280 100%);">
                        <div class="category-title">
                            <i class="bi bi-briefcase-fill"></i>
                            General Affair
                        </div>
                        <div class="menu-grid">
                            <a href="http://penugasan.test/login" class="menu-item">
                                <div class="menu-icon"
                                    style="background: linear-gradient(135deg, #134e5e 0%, #71b280 100%);">
                                    <i class="bi bi-briefcase"></i>
                                </div>
                                <div class="menu-text">Assignment</div>
                            </a>

                            <a href="http://perizinan.test/login" class="menu-item">
                                <div class="menu-icon"
                                    style="background: linear-gradient(135deg, #134e5e 0%, #71b280 100%);">
                                    <i class="bi bi-building-up"></i>
                                </div>
                                <div class="menu-text">Permission</div>
                            </a>
                        </div>
                    </div>


                    <div class="category-card" style="background: linear-gradient(135deg, #232526 0%, #414345 100%);">
                        <div class="category-title">
                            <i class="bi bi-file-earmark-text-fill"></i>
                            Administration
                        </div>
                        <div class="menu-grid">
                            <a href="http://attendance-app.test/login" class="menu-item">
                                <div class="menu-icon"
                                    style="background: linear-gradient(135deg, #232526 0%, #414345 100%);">
                                    <i class="bi bi-clock-fill"></i>
                                </div>
                                <div class="menu-text">Attendance System</div>
                            </a>

                            <a href="#" class="menu-item">
                                <div class="menu-icon"
                                    style="background: linear-gradient(135deg, #232526 0%, #414345 100%);">
                                    <i class="bi bi-alarm-fill"></i>
                                </div>
                                <div class="menu-text">Overtime System</div>
                            </a>

                            <a href="http://pengadaan-barang.test/login" class="menu-item">
                                <div class="menu-icon"
                                    style="background: linear-gradient(135deg, #232526 0%, #414345 100%);">
                                    <i class="bi bi-cart-fill"></i>
                                </div>
                                <div class="menu-text">Procurement of Goods & Operations</div>
                            </a>

                            <a href="http://payment-utility.test/login" target="_blank" class="menu-item">
                                <div class="menu-icon"
                                    style="background: linear-gradient(135deg, #232526 0%, #414345 100%);">
                                    <i class="bi bi-lightning-fill"></i>
                                </div>
                                <div class="menu-text">Utility System</div>
                            </a>
                        </div>
                    </div>

                    <!-- Commercial Division -->
                    <div class="category-card commercial">
                        <div class="category-title">
                            <i class="bi bi-briefcase-fill"></i>
                            Commercial & Business
                        </div>

                        <!-- Purchasing -->
                        <div class="sub-category">
                            <div class="sub-category-title">
                                <i class="bi bi-cart-check-fill"></i>
                                Purchasing
                            </div>
                            <div class="menu-grid">
                                <a href="http://pemilihan-supplier.test/login" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-shop"></i>
                                    </div>
                                    <div class="menu-text">Supplier Determination</div>
                                </a>
                            </div>
                        </div>

                        <!-- Sales -->
                        <div class="sub-category">
                            <div class="sub-category-title">
                                <i class="bi bi-graph-up-arrow"></i>
                                Sales
                            </div>
                            <div class="menu-grid">
                                <a href="http://sales.test/login" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-bag-check-fill"></i>
                                    </div>
                                    <div class="menu-text">Product Sales</div>
                                </a>
                            </div>
                        </div>

                        <!-- Marketing -->
                        <div class="sub-category">
                            <div class="sub-category-title">
                                <i class="bi bi-cloude-haze"></i>
                                Connecting
                            </div>
                            <div class="menu-grid">
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-hdd-network"></i>
                                    </div>
                                    <div class="menu-text">Interbranch Distribution</div>
                                </a>
                            </div>
                        </div>

                        <!-- Marketing -->
                        <div class="sub-category">
                            <div class="sub-category-title">
                                <i class="bi bi-megaphone-fill"></i>
                                Marketing
                            </div>
                            <div class="menu-grid">
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-megaphone"></i>
                                    </div>
                                    <div class="menu-text">Product Promotion</div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Maintenance Division -->
                    <div class="category-card maintenance">
                        <div class="category-title">
                            <i class="bi bi-tools"></i>
                            Maintenance
                        </div>
                        <div class="menu-grid">
                            <a href="http://permintaan-alat.test/login" class="menu-item">
                                <div class="menu-icon">
                                    <i class="bi bi-tools"></i>
                                </div>
                                <div class="menu-text">Tool Request</div>
                            </a>
                            <a href="http://perbaikan.test/login" class="menu-item">
                                <div class="menu-icon">
                                    <i class="bi bi-wrench-adjustable"></i>
                                </div>
                                <div class="menu-text">Repair</div>
                            </a>
                            <a href="http://maintenance-preventif.test/login" class="menu-item">
                                <div class="menu-icon">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <div class="menu-text">Preventive Maintenance</div>
                            </a>
                            <a href="http://stok-sparepart-mesin.test/login" class="menu-item">
                                <div class="menu-icon">
                                    <i class="bi bi-box-seam-fill"></i>
                                </div>
                                <div class="menu-text">Stock Sparepart Machine</div>
                            </a>
                            <a href="http://perencanaan-perbaikan-mesin.test/login" class="menu-item">
                                <div class="menu-icon">
                                    <i class="bi bi-person-badge-fill"></i>
                                </div>
                                <div class="menu-text">Personnel Determination</div>
                            </a>
                        </div>
                    </div>

                    <!-- Production & Warehouse Division -->
                    <div class="category-card production">
                        <div class="category-title">
                            <i class="bi bi-building"></i>
                            Production & Warehouse
                        </div>

                        <!-- PPIC -->
                        <div class="sub-category">
                            <div class="sub-category-title">
                                <i class="bi bi-clipboard-data-fill"></i>
                                Production Planning Inventory Control (PPIC)
                            </div>
                            <div class="menu-grid">
                                <a href="http://penjadwalan-produksi.test/login" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-calendar2-week-fill"></i>
                                    </div>
                                    <div class="menu-text">Production Scheduling</div>
                                </a>
                                <a href="http://pengadaan-material-produksi.test/login" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-cart-plus-fill"></i>
                                    </div>
                                    <div class="menu-text">Production Material Procurement</div>
                                </a>
                                <a href="http://persediaan-material.test/login" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-boxes"></i>
                                    </div>
                                    <div class="menu-text">Material Inventory</div>
                                </a>
                                <a href="http://target-produksi.test/login" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-bullseye"></i>
                                    </div>
                                    <div class="menu-text">Production Target</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-clipboard-data"></i>
                                    </div>
                                    <div class="menu-text">Distribution of Raw Material Purchases</div>
                                </a>
                            </div>
                        </div>

                        <!-- Gudang -->
                        <div class="sub-category">
                            <div class="sub-category-title">
                                <i class="bi bi-house-fill"></i>
                                Warehouse
                            </div>
                            <div class="menu-grid">
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-truck"></i>
                                    </div>
                                    <div class="menu-text">Goods Delivery</div>
                                </a>
                                <a href="http://pengembalian-material-produksi.test/login" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-arrow-return-left"></i>
                                    </div>
                                    <div class="menu-text">Production Material Return</div>
                                </a>
                                <a href="http://pengembalian-produk.test/login" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </div>
                                    <div class="menu-text">Product Return (NG)</div>
                                </a>
                                <a href="http://incoming-goods.test/login" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-arrow-left-right"></i>
                                    </div>
                                    <div class="menu-text">Goods In and Out Management</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-house-gear-fill"></i>
                                    </div>
                                    <div class="menu-text">Inter-Warehouse Transfer</div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Quality Management Division -->
                    <div class="category-card quality">
                        <div class="category-title">
                            <i class="bi bi-patch-check-fill"></i>
                            Quality Management
                        </div>

                        <!-- Quality Control -->
                        <div class="sub-category">
                            <div class="sub-category-title">
                                <i class="bi bi-search"></i>
                                Quality Control
                            </div>
                            <div class="menu-grid">
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-search"></i>
                                    </div>
                                    <div class="menu-text">Quality Control</div>
                                </a>
                                <a href="http://pemeriksaan-raw-material.test/login" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                    <div class="menu-text">Raw Material Inspection</div>
                                </a>
                                <a href="http://pemeriksaan-proses.test/login" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-gear-wide-connected"></i>
                                    </div>
                                    <div class="menu-text">Production Process Inspection</div>
                                </a>
                                <a href="http://qc.test/login" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-check2-square"></i>
                                    </div>
                                    <div class="menu-text">Finished Product Inspection</div>
                                </a>
                                <a href="http://dokumentasi-barang-rusak.test/login" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-file-earmark-text-fill"></i>
                                    </div>
                                    <div class="menu-text">Documentation & Reporting</div>
                                </a>
                                <a href="http://perbaikan-ketidaksesuaian.test/login" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                    </div>
                                    <div class="menu-text">Nonconformity Follow-up</div>
                                </a>
                            </div>
                        </div>

                        <!-- Healthy, Safety environment (K3)-->
                        <div class="sub-category">
                            <div class="sub-category-title">
                                <i class="bi bi-hospital"></i>
                                Health, Safety environment (K3)
                            </div>
                            <div class="menu-grid">
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-shield-check"></i>
                                    </div>
                                    <div class="menu-text">Health and Safety Regulations</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-bar-chart"></i>
                                    </div>
                                    <div class="menu-text">Occupational Risk Analysis</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="menu-text">Employee Communication & Training</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-clipboard-data"></i>
                                    </div>
                                    <div class="menu-text">Health and Safety Documentation & Reporting</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-alarm"></i>
                                    </div>
                                    <div class="menu-text">Emergency Response</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-book"></i>
                                    </div>
                                    <div class="menu-text">Health and Safety Training and Awareness</div>
                                </a>
                            </div>
                        </div>

                        <!-- Quality Assurance -->
                        <div class="sub-category">
                            <div class="sub-category-title">
                                <i class="bi bi-shield-fill-check"></i>
                                Quality Assurance
                            </div>
                            <div class="menu-grid">
                                <a href="http://perencanaan-kualitas.test/login" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-diagram-3-fill"></i>
                                    </div>
                                    <div class="menu-text">Quality Planning</div>
                                </a>
                                <a href="http://pencatatan-penyimpangan.test/login" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-eye-fill"></i>
                                    </div>
                                    <div class="menu-text">Process Monitoring</div>
                                </a>
                                <a href="http://quality-testing.test/login" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </div>
                                    <div class="menu-text">Testing and Validation</div>
                                </a>
                                <a href="http://quality-documentation.test/login" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-journal-text"></i>
                                    </div>
                                    <div class="menu-text">Documentation</div>
                                </a>
                                <a href="http://qc-report.test/login" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-arrow-up-right-circle-fill"></i>
                                    </div>
                                    <div class="menu-text">Improvement and Development</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-arrows-angle-contract"></i>
                                    </div>
                                    <div class="menu-text">Testing of Production Output at Other Branches</div>
                                </a>
                                <a href="http://penilaian-kompetensi-pengetahauan.test/login" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                    <div class="menu-text">Training and Socialization</div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Production Engineering Division -->
                    <div class="category-card engineering">
                        <div class="category-title">
                            <i class="bi bi-cpu-fill"></i>
                            Production Engineering
                        </div>

                        <!-- Manajemen Produksi -->
                        <div class="sub-category">
                            <div class="sub-category-title">
                                <i class="bi bi-kanban-fill"></i>
                                Production Management
                            </div>
                            <div class="menu-grid">
                                <a href="http://perencanaan-produksi.test/login" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-clipboard-check-fill"></i>
                                    </div>
                                    <div class="menu-text">Production Planning</div>
                                </a>
                                <a href="http://pengorganisasian-produksi.test/login" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-diagram-2-fill"></i>
                                    </div>
                                    <div class="menu-text">Organization</div>
                                </a>
                                <a href="http://pelaksanaan-produksi.test/login" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-play-circle-fill"></i>
                                    </div>
                                    <div class="menu-text">Production Execution</div>
                                </a>
                                <a href="http://pengendalian-produksi.test/login" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-speedometer2"></i>
                                    </div>
                                    <div class="menu-text">Production Control</div>
                                </a>
                            </div>
                        </div>

                        <!-- Development Engineering -->
                        <div class="sub-category">
                            <div class="sub-category-title">
                                <i class="bi bi-lightbulb-fill"></i>
                                Development Engineering
                            </div>
                            <div class="menu-grid">
                                <a href="http://pengembangan-produk.test/login" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-box2-heart-fill"></i>
                                    </div>
                                    <div class="menu-text">Product Development</div>
                                </a>
                                <a href="http://pengembangan-proses.test/login" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-bezier2"></i>
                                    </div>
                                    <div class="menu-text">Process Development</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-rocket-takeoff-fill"></i>
                                    </div>
                                    <div class="menu-text">Technology Development</div>
                                </a>
                            </div>
                        </div>
                    </div>


                    {{-- Accounting --}}
                    <div class="category-card accounting">
                        <div class="category-title">
                            <i class="bi bi-calculator-fill"></i>
                            Accounting
                        </div>

                        <!-- 6.1 Pembayaran -->
                        <div class="sub-category">
                            <div class="sub-category-title">
                                <i class="bi bi-cash-stack"></i>
                                Pembayaran
                            </div>
                            <div class="menu-grid">
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-lightning-charge-fill"></i>
                                    </div>
                                    <div class="menu-text">Utilitas</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-box-seam-fill"></i>
                                    </div>
                                    <div class="menu-text">Pengadaan Bahan Baku</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-bag-fill"></i>
                                    </div>
                                    <div class="menu-text">Pengadaan Barang Operasional</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                    <div class="menu-text">Penggajian</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <div class="menu-text">Lembur</div>
                                </a>
                            </div>
                        </div>

                        <!-- 6.2 Perpajakan -->
                        <div class="sub-category">
                            <div class="sub-category-title">
                                <i class="bi bi-file-earmark-ruled-fill"></i>
                                Perpajakan
                            </div>
                            <div class="menu-grid">
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-receipt-cutoff"></i>
                                    </div>
                                    <div class="menu-text">Manajemen PPN Produksi & Penjualan</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-arrow-left-right"></i>
                                    </div>
                                    <div class="menu-text">Rekonsiliasi Pajak & Akuntansi Produksi</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-building-fill"></i>
                                    </div>
                                    <div class="menu-text">Perhitungan & Pelaporan PPh Badan Industri</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-truck-front-fill"></i>
                                    </div>
                                    <div class="menu-text">Pajak Impor Bahan Baku (Bea Masuk & PDRI)</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-graph-up-arrow"></i>
                                    </div>
                                    <div class="menu-text">Monitoring Insentif Pajak Industri</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-file-earmark-check-fill"></i>
                                    </div>
                                    <div class="menu-text">e-Faktur Internal Terintegrasi ERP</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-calculator"></i>
                                    </div>
                                    <div class="menu-text">Simulasi Perencanaan Pajak (Tax Planning Simulator)</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-search"></i>
                                    </div>
                                    <div class="menu-text">Audit Pajak Internal Manufaktur</div>
                                </a>
                            </div>
                        </div>
                    </div>


                    {{-- Exim --}}
                    <div class="category-card exim">
                        <div class="category-title">
                            <i class="bi bi-globe-americas"></i>
                            Exim
                        </div>

                        <!-- 7.1 Export -->
                        <div class="sub-category">
                            <div class="sub-category-title">
                                <i class="bi bi-box-arrow-up-right"></i>
                                Export
                            </div>
                            <div class="menu-grid">
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-file-earmark-text-fill"></i>
                                    </div>
                                    <div class="menu-text">Manajemen Dokumen Ekspor</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-percent"></i>
                                    </div>
                                    <div class="menu-text">Perhitungan Pajak Ekspor</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </div>
                                    <div class="menu-text">Tracking & Shipment Monitoring</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-bar-chart-fill"></i>
                                    </div>
                                    <div class="menu-text">Analisis Kinerja Ekspor</div>
                                </a>
                            </div>
                        </div>

                        <!-- 7.2 Import -->
                        <div class="sub-category">
                            <div class="sub-category-title">
                                <i class="bi bi-box-arrow-in-down-left"></i>
                                Import
                            </div>
                            <div class="menu-grid">
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-bank2"></i>
                                    </div>
                                    <div class="menu-text">Perhitungan Bea Masuk & Pajak Impor</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-currency-exchange"></i>
                                    </div>
                                    <div class="menu-text">Landed Cost Calculation</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-file-earmark-medical-fill"></i>
                                    </div>
                                    <div class="menu-text">Manajemen Dokumen Impor</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-shield-check"></i>
                                    </div>
                                    <div class="menu-text">Kontrol Kepatuhan & Risiko</div>
                                </a>
                                <a href="#" class="menu-item">
                                    <div class="menu-icon">
                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                    </div>
                                    <div class="menu-text">Risiko Impor</div>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
    @stack('scripts')
</body>

</html>
