@extends('layouts.home')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/divisions_page.css') }}">
@endpush

@section('content')


    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">
                <i class="bi bi-diagram-3 me-3"></i>DIVISIONS
            </h1>
            <p class="hero-description">
               Click on a division to explore our processes and capabilities
            </p>
        </div>
    </section>

    <!-- Divisions Section -->
    <section class="py-5" id="divisions">
        <div class="container">
            {{-- <div class="text-center mb-5">
                <h2 class="section-title">Our Divisions</h2>
                <p class="text-muted">Click on a division to explore our processes and capabilities</p>
            </div> --}}
            
            <!-- Division Cards -->
            <div class="row g-4 mb-5" role="tablist">
                <div class="col-md-3">
                    <div class="division-card division-1 text-center active" data-bs-toggle="tab" data-bs-target="#investment-casting-tab"
                        role="tab">
                        <div class="division-icon">
                            <i class="bi bi-box"></i>
                        </div>
                        <h4>Investment Casting</h4>
                        <p>High-precision casting process suitable for complex geometries and tight tolerances.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="division-card division-2 text-center" data-bs-toggle="tab" data-bs-target="#sand-casting-tab"
                        role="tab">
                        <div class="division-icon">
                            <i class="fa-solid fa-cubes-stacked"></i>
                        </div>
                        <h4>Sand Casting</h4>
                        <p>Versatile casting method ideal for large components and lower production volumes.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="division-card division-3 text-center" data-bs-toggle="tab" data-bs-target="#valve-manufacturing-tab"
                        role="tab">
                        <div class="division-icon">
                            <i class="fa-solid fa-gears"></i>
                        </div>
                        <h4>Valve Manufacturing</h4>
                        <p>Specialized in producing high-quality valves for various industrial applications.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="division-card division-4 text-center" data-bs-toggle="tab" data-bs-target="#permanent-mould-tab"
                        role="tab">
                        <div class="division-icon">
                            <i class="fa-solid fa-box"></i>
                        </div>
                        <h4>Permanent Mould</h4>
                        <p>Durable mould casting for high-volume production with excellent surface finish.</p>
                    </div>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="tab-content" id="divisionsTabContent">
                
                <!-- Investment Casting -->
                <div class="tab-pane fade show active" id="investment-casting-tab" role="tabpanel">
                    <div class="card border-0 shadow">
                        <div class="card-header text-white" style="background: linear-gradient(135deg, #134e5e 0%, #71b280 100%);">
                            <h3 class="mb-0"><i class="fa-solid fa-industry me-2"></i>Investment Casting Division</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="side-nav">
                                        <div class="list-group" id="investment-list">
                                            <a class="list-group-item list-group-item-action" href="#ic-wax-injection">
                                                <i class="fa-solid fa-syringe me-2"></i>Wax Injection
                                            </a>
                                            <a class="list-group-item list-group-item-action" href="#ic-pattern-assembling">
                                                <i class="fa-solid fa-puzzle-piece me-2"></i>Pattern Assembling
                                            </a>
                                            <a class="list-group-item list-group-item-action" href="#ic-ceramic">
                                                <i class="fa-solid fa-layer-group me-2"></i>Ceramic Investment
                                            </a>
                                            <a class="list-group-item list-group-item-action" href="#ic-dewaxing">
                                                <i class="fa-solid fa-fire me-2"></i>Dewaxing
                                            </a>
                                            <a class="list-group-item list-group-item-action" href="#ic-sintering">
                                                <i class="fa-solid fa-temperature-high me-2"></i>Sintering
                                            </a>
                                            <a class="list-group-item list-group-item-action" href="#ic-pouring">
                                                <i class="fa-solid fa-flask me-2"></i>Metal Pouring
                                            </a>
                                            <a class="list-group-item list-group-item-action" href="#ic-cutoff">
                                                <i class="fa-solid fa-scissors me-2"></i>Cut Off
                                            </a>
                                            <a class="list-group-item list-group-item-action" href="#ic-finishing">
                                                <i class="fa-solid fa-wand-magic-sparkles me-2"></i>Finishing
                                            </a>
                                            <a class="list-group-item list-group-item-action" href="#ic-inspection">
                                                <i class="fa-solid fa-magnifying-glass me-2"></i>Inspection
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <div data-bs-spy="scroll" data-bs-target="#investment-list" data-bs-smooth-scroll="true" 
                                         class="scrollspy-example" tabindex="0" style="max-height: 600px; overflow-y: auto;">
                                        
                                        <div id="ic-wax-injection" class="process-section">
                                            <h4>Wax Injection</h4>
                                            <p>Wax Injection Pattern</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/investment-casting/wax-injection-1.avif') }}" alt="Wax Injection Process 1" class="img-thumbnail">
                                                <img src="{{ asset('images/division/investment-casting/wax-injection-2.avif') }}" alt="Wax Injection Process 2" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div id="ic-pattern-assembling" class="process-section">
                                            <h4>Wax/Pattern Assembling</h4>
                                            <p>Assembling pattern waxes to become one mould unit.</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/investment-casting/wax-pattern-1.avif') }}" alt="Pattern Assembly 1" class="img-thumbnail">
                                                <img src="{{ asset('images/division/investment-casting/wax-pattern-2.avif') }}" alt="Pattern Assembly 2" class="img-thumbnail">
                                                <img src="{{ asset('images/division/investment-casting/wax-pattern-3.avif') }}" alt="Pattern Assembly 3" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div id="ic-ceramic" class="process-section">
                                            <h4>Ceramic Investment</h4>
                                            {{-- <p>The wax tree is repeatedly dipped in ceramic slurry and coated with refractory material to build a strong ceramic shell.</p> --}}
                                            <ul>
                                                <li>Coating : investing wax pattern cluster into a thin mixture of fine refractory material, followed by draining to create a uniform surface coating.</li>
                                                <li>Stuccoing : applying coarse ceramic grain by investing mould in fluidized bed.</li>
                                            </ul>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/investment-casting/ceramic-investment-1.avif') }}" alt="Ceramic Investment 1" class="img-thumbnail">
                                                <img src="{{ asset('images/division/investment-casting/ceramic-investment-2.avif') }}" alt="Ceramic Investment 2" class="img-thumbnail">
                                                <img src="{{ asset('images/division/investment-casting/ceramic-investment-3.avif') }}" alt="Ceramic Investment 3" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div id="ic-dewaxing" class="process-section">
                                            <h4>Dewaxing</h4>
                                            <p>Removal process from ceramic mould, through steam application (placing mould in a boiler/autoclave machine).</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/investment-casting/dewaxing-1.avif') }}" alt="Dewaxing Process" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div id="ic-sintering" class="process-section">
                                            <h4>Sintering</h4>
                                            <p>Sintering process is conducted in order to determine the actual strength of the ceramics.</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/investment-casting/sintering.avif') }}" alt="Sintering Process" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div id="ic-pouring" class="process-section">
                                            <h4>Metal Pouring</h4>
                                            <p>For more complex castings, Metinca uses roll over furnace.</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/investment-casting/metal-pouring-1.avif') }}" alt="Metal Pouring 1" class="img-thumbnail">
                                                <img src="{{ asset('images/division/investment-casting/metal-pouring-2.avif') }}" alt="Metal Pouring 2" class="img-thumbnail">
                                                <img src="{{ asset('images/division/investment-casting/metal-pouring-3.avif') }}" alt="Metal Pouring 3" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div id="ic-cutoff" class="process-section">
                                            <h4>Cut Off</h4>
                                            <p>Cutting castings from runner/gating system.</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/investment-casting/cut-off.avif') }}" alt="Cut Off Process" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div id="ic-finishing" class="process-section">
                                            <h4>Finishing</h4>
                                            <p>Removal process of ceramic from casting surface, such as fettling and shot blasting.</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/investment-casting/finishing-1.avif') }}" alt="Finishing 1" class="img-thumbnail">
                                                <img src="{{ asset('images/division/investment-casting/finishing-2.avif') }}" alt="Finishing 2" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div id="ic-inspection" class="process-section">
                                            <h4>Inspection</h4>
                                            <p>Final casting inspection, before being prepared for shipment..</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/investment-casting/inspection-1.avif') }}" alt="Inspection Process" class="img-thumbnail">
                                                <img src="{{ asset('images/division/investment-casting/inspection-2.avif') }}" alt="Inspection Process" class="img-thumbnail">
                                                <img src="{{ asset('images/division/investment-casting/inspection-3.avif') }}" alt="Inspection Process" class="img-thumbnail">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sand Casting -->
                <div class="tab-pane fade" id="sand-casting-tab" role="tabpanel">
                    <div class="card border-0 shadow">
                        <div class="card-header text-white" style="background: linear-gradient(135deg, #000428 0%, #004e92 100%);">
                            <h3 class="mb-0"><i class="fa-solid fa-cubes-stacked me-2"></i>Sand Casting Division</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="side-nav">
                                        <div class="list-group" id="sand-list">
                                            <a class="list-group-item list-group-item-action" href="#sc-pattern">Pattern</a>
                                            <a class="list-group-item list-group-item-action" href="#sc-sand-mixing-coating">Sand Mixing & Coating</a>
                                            <a class="list-group-item list-group-item-action" href="#sc-setting">Setting</a>
                                            <a class="list-group-item list-group-item-action" href="#sc-melting">Melting</a>
                                            <a class="list-group-item list-group-item-action" href="#sc-fettling">Fettling</a>
                                            <a class="list-group-item list-group-item-action" href="#sc-heat-treatment">Heat Treatment</a>
                                            <a class="list-group-item list-group-item-action" href="#sc-machining">Machining</a>
                                            <a class="list-group-item list-group-item-action" href="#sc-quality">Quality</a>
                                            <a class="list-group-item list-group-item-action" href="#sc-final-inspection">Final Inspection</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <div data-bs-spy="scroll" data-bs-target="#sand-list" data-bs-smooth-scroll="true" 
                                         style="max-height: 600px; overflow-y: auto;">
                                        
                                        <div id="sc-pattern" class="process-section">
                                            <h4>Pattern</h4>
                                            <p>Used to prepare the cavity into which molten material will be poured during the casting process.</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/sand-casting/pattern-1.avif') }}" alt="Pattern Making" class="img-thumbnail">
                                                <img src="{{ asset('images/division/sand-casting/pattern-2.avif') }}" alt="Pattern Making" class="img-thumbnail">
                                                <img src="{{ asset('images/division/sand-casting/pattern-3.avif') }}" alt="Pattern Making" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div id="sc-sand-mixing-coating" class="process-section">
                                            <h4>sand Mixing & Coating</h4>
                                            <p>Metinca uses furan system process.</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/sand-casting/sand-mixing-coating-1.avif') }}" alt="Sand Mixing & Coating Process" class="img-thumbnail">
                                                <img src="{{ asset('images/division/sand-casting/sand-mixing-coating-2.avif') }}" alt="Sand Mixing & Coating Process" class="img-thumbnail">
                                                <img src="{{ asset('images/division/sand-casting/sand-mixing-coating-3.avif') }}" alt="Sand Mixing & Coating Process" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div id="sc-setting" class="process-section">
                                            <h4>Setting</h4>
                                            <p>Setting capacity up to 5 ton (mould), incorporating the pattern and sand in a gating system.</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/sand-casting/setting.avif') }}" alt="Setting" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div id="sc-melting" class="process-section">
                                            <h4>Melting</h4>
                                            <p>Melting material ranging from 1 ton - 800 kg, 800 - 500 kg, 500 - 500 kg.</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/sand-casting/melting.avif') }}" alt="Melting" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div id="sc-fettling" class="process-section">
                                            <h4>Fettling</h4>
                                            <p>Removal process of the cores, gates, sprues, runners, risers and chipping of any unnecessary projections on the surface of the castings.</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/sand-casting/fettling.avif') }}" alt="Fettling Process" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div id="sc-heat-treatment" class="process-section">
                                            <h4>Heat Treatment</h4>
                                            <p>Metal is heated to a certain temperature and then cooled in a particular manner to alter its internal structure in order to obtain desired degree of physical and mechanical properties such as brittleness, hardness, and roughness. Shotblast is used to clean, strengthen (peen) or polish metal.</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/sand-casting/heat-treatment-1.avif') }}" alt="heat-treatment" class="img-thumbnail">
                                                <img src="{{ asset('images/division/sand-casting/heat-treatment-2.avif') }}" alt="heat-treatment" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div class="process-section" id="machining">
                                            <h4>Machining</h4>
                                            <p>Controllable material removal process into desired shaped.</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/sand-casting/machining-1.avif') }}" alt="Machining Process" class="img-thumbnail">
                                                <img src="{{ asset('images/division/sand-casting/machining-2.avif') }}" alt="Machining Process" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div class="process-section" id="quality">
                                            <h4>Quality</h4>
                                            {{-- <p>Ensuring the highest standards through rigorous quality control measures including dimensional checks and non-destructive testing.</p> --}}
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/sand-casting/quality.avif') }}" alt="Quality Control Process" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div class="process-section" id="final-inspection">
                                            <h4>Final Inspection</h4>
                                            <p>Includes :</p>
                                            <ul>
                                                <li>Dimention</li>
                                                <li>Visual Inspection</li>
                                                <li>Magnetic Particle</li>
                                                <li>Pentrant Test</li>
                                            </ul>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/sand-casting/final-inspection.avif') }}" alt="Final Inspection Process" class="img-thumbnail">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Valve Manufacturing -->
                <div class="tab-pane fade" id="valve-manufacturing-tab" role="tabpanel">
                    <div class="card border-0 shadow">
                        <div class="card-header text-white" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                            <h3 class="mb-0"><i class="fa-solid fa-gears me-2"></i>Valve Division</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="side-nav">
                                        <div class="list-group" id="valve-list">
                                            <a class="list-group-item list-group-item-action" href="#vm-vd">Valve Division</a>
                                            <a class="list-group-item list-group-item-action" href="#vm-visual-demensional">Visual & Demensional Inspection</a>
                                            <a class="list-group-item list-group-item-action" href="#vm-welding">Welding</a>
                                            <a class="list-group-item list-group-item-action" href="#vm-penetrant-test">Penetrant Test</a>
                                            <a class="list-group-item list-group-item-action" href="#vm-lapping">Lapping</a>
                                            <a class="list-group-item list-group-item-action" href="#vm-assembling">Assembling</a>
                                            <a class="list-group-item list-group-item-action" href="#vm-hydro-test">Hydro Test</a>
                                            <a class="list-group-item list-group-item-action" href="#vm-painting">Painting</a>
                                            <a class="list-group-item list-group-item-action" href="#vm-packing">Packing</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <div data-bs-spy="scroll" data-bs-target="#valve-list" data-bs-smooth-scroll="true" 
                                         style="max-height: 600px; overflow-y: auto;">
                                        
                                        <div id="vm-vd" class="process-section">
                                            <h4>Valve Division</h4>
                                            <p></p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/valve/valve-division-1.avif') }}" alt="Design Process" class="img-thumbnail">
                                                <img src="{{ asset('images/division/valve/valve-division-2.avif') }}" alt="Design Process" class="img-thumbnail">
                                                <img src="{{ asset('images/division/valve/valve-division-3.avif') }}" alt="Design Process" class="img-thumbnail">
                                                <img src="{{ asset('images/division/valve/valve-division-4.avif') }}" alt="Design Process" class="img-thumbnail">
                                                <img src="{{ asset('images/division/valve/valve-division-5.avif') }}" alt="Design Process" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div id="vm-visual-demensional" class="process-section">
                                            <h4>visual-demensional Inspection</h4>
                                            <p>Mainly contains overlay disc and seat ring inspection.</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/valve/visual-demensional-1.avif') }}" alt="visual-demensional" class="img-thumbnail">
                                                <img src="{{ asset('images/division/valve/visual-demensional-2.avif') }}" alt="visual-demensional" class="img-thumbnail">
                                                <img src="{{ asset('images/division/valve/visual-demensional-3.avif') }}" alt="visual-demensional" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div id="vm-welding" class="process-section">
                                            <h4>Welding</h4>
                                            <p>Based material addition process, in order to get the desired hardness level.</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/valve/welding-1.avif') }}" alt="welding" class="img-thumbnail">
                                                <img src="{{ asset('images/division/valve/welding-2.avif') }}" alt="welding" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div id="vm-penetrant-test" class="process-section">
                                            <h4>penetrant-test</h4>
                                            <p>Used to locate surface breaking defects.</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/valve/penetrant-test-1.avif') }}" alt="penetrant-test" class="img-thumbnail">
                                                <img src="{{ asset('images/division/valve/penetrant-test-2.avif') }}" alt="penetrant-test" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div id="vm-lapping" class="process-section">
                                            <h4>Lapping</h4>
                                            {{-- <p>Protective lappings and surface treatments for durability.</p> --}}
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/valve/lapping.avif') }}" alt="lapping" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div class="product-section" id="vm-assembling">
                                            <h4>Assembling</h4>
                                            <p>Assembling valve parts into one unit consisting valve stem, core and cap.</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/valve/assembling-1.avif') }}" alt="assembling" class="img-thumbnail">
                                                <img src="{{ asset('images/division/valve/assembling-2.avif') }}" alt="assembling" class="img-thumbnail">
                                                <img src="{{ asset('images/division/valve/assembling-3.avif') }}" alt="assembling" class="img-thumbnail">
                                            </div>
                                        </div>
                                        <div class="product-section" id="vm-hydro-test">
                                            <h4>Hydro Test</h4>
                                            <p>The valve is tested for its pressure requirements and leakage proof.</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/valve/hydro-test-1.avif') }}" alt="hydro-test" class="img-thumbnail">
                                                <img src="{{ asset('images/division/valve/hydro-test-2.avif') }}" alt="hydro-test" class="img-thumbnail">
                                            </div>
                                        </div>
                                        <div class="product-section" id="vm-painting">
                                            <h4>Painting</h4>
                                            <p>Valve painting process follows the painting procedure acquired from the customer.</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/valve/painting-1.avif') }}" alt="painting" class="img-thumbnail">
                                                <img src="{{ asset('images/division/valve/painting-2.avif') }}" alt="painting" class="img-thumbnail">
                                                <img src="{{ asset('images/division/valve/painting-3.avif') }}" alt="painting" class="img-thumbnail">
                                            </div>
                                        </div>
                                        <div class="product-section" id="vm-packing">
                                            <h4>Packing</h4>
                                            <p>Metinca uses wooden crate packaging and wrap the valves with plastic to make sure that the goods arrived in destination conveniently and safely.</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/valve/packing-1.avif') }}" alt="packing" class="img-thumbnail">
                                                <img src="{{ asset('images/division/valve/packing-2.avif') }}" alt="packing" class="img-thumbnail">
                                                <img src="{{ asset('images/division/valve/packing-3.avif') }}" alt="packing" class="img-thumbnail">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permanent Mould -->
                <div class="tab-pane fade" id="permanent-mould-tab" role="tabpanel">
                    <div class="card border-0 shadow">
                        <div class="card-header text-white" style="background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);">
                            <h3 class="mb-0"><i class="fa-solid fa-box me-2"></i>Permanent Mould Division</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    {{-- under construction --}}
                                    <div class="alert alert-warning text-center" role="alert">
                                        <h4 class="alert-heading">Under Construction</h4>
                                        <p>Content for the Permanent Mould Division is currently being developed. Please check back later for detailed information about our processes and capabilities in this division.</p>
                                        </div>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-lg-3">
                                    <div class="side-nav">
                                        <div class="list-group" id="pm-list">
                                            <a class="list-group-item list-group-item-action" href="#pm-mold-design">Mold Design</a>
                                            <a class="list-group-item list-group-item-action" href="#pm-preparation">Mold Preparation</a>
                                            <a class="list-group-item list-group-item-action" href="#pm-casting">Casting</a>
                                            <a class="list-group-item list-group-item-action" href="#pm-cooling">Cooling</a>
                                            <a class="list-group-item list-group-item-action" href="#pm-trimming">Trimming</a>
                                            <a class="list-group-item list-group-item-action" href="#pm-finishing">Finishing</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <div data-bs-spy="scroll" data-bs-target="#pm-list" data-bs-smooth-scroll="true" 
                                         style="max-height: 600px; overflow-y: auto;">
                                        
                                        <div id="pm-mold-design" class="process-section">
                                            <h4>Mold Design</h4>
                                            <p>Designing durable metal molds for repeated use in high-volume production.</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/permanent-mould/design-1.avif') }}" alt="Mold Design" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div id="pm-preparation" class="process-section">
                                            <h4>Mold Preparation</h4>
                                            <p>Heating and coating molds to ensure proper metal flow and easy release.</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/permanent-mould/prep-1.avif') }}" alt="Preparation" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div id="pm-casting" class="process-section">
                                            <h4>Casting</h4>
                                            <p>Pouring molten metal into permanent molds for precision casting.</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/permanent-mould/casting-1.avif') }}" alt="Casting" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div id="pm-cooling" class="process-section">
                                            <h4>Cooling</h4>
                                            <p>Controlled cooling process for optimal metallurgical properties.</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/permanent-mould/cooling-1.avif') }}" alt="Cooling" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div id="pm-trimming" class="process-section">
                                            <h4>Trimming</h4>
                                            <p>Removing excess material and gates from castings.</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/permanent-mould/trimming-1.avif') }}" alt="Trimming" class="img-thumbnail">
                                            </div>
                                        </div>

                                        <div id="pm-finishing" class="process-section">
                                            <h4>Finishing</h4>
                                            <p>Final surface treatment and quality inspection.</p>
                                            <div class="image-gallery">
                                                <img src="{{ asset('images/division/permanent-mould/finishing-1.avif') }}" alt="Finishing" class="img-thumbnail">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            

            // Smooth scroll for navigation lists
            document.querySelectorAll('[data-bs-spy="scroll"]').forEach(scrollArea => {
                scrollArea.addEventListener('click', function(e) {
                    if (e.target.tagName === 'A') {
                        e.preventDefault();
                        const target = document.querySelector(e.target.getAttribute('href'));
                        if (target) {
                            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    }
                });
            });
        });
    </script>
    @endpush
@endsection