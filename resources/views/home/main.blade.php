@extends('layouts.home')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 hero-content">
                    <h1>The #1 Precision Casting and Tooling Facility in Indonesia</h1>
                    <p>
                        Produce beneficial investment castings products that add value to the industry and are safe for the
                        environment.
                    </p>
                    <button class="btn btn-primary-custom">
                        <i class="bi bi-whatsapp me-2"></i>Call Us
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Profile Section -->
    <section class="py-5" id="profile">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Profile</h2>
            </div>
            <div class="row g-4 mb-3">
                <div class="col-md-12 text-center">
                    <p>
                        PT.Metinca Prima Industrial Works was founded in 1986 at its present site on Pulogadung
                        Industrial Estate in Jakarta. The company is privately owned and is the first of its
                        kind in Indonesia. Metinca has the leading advantage in the production of high-quality
                        castings, both of ferrous and non-ferrous materials, which gives much wider
                        customer base and more importantly, gives customers greater freedom of choice.
                        If high quality, great price and excellent service are your main concern,
                        then Metinca Prima is the right choice for you.
                    </p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fa-regular fa-compass"></i>
                        </div>
                        <h4>Vision</h4>
                        <p>To produce beneficial investment castings products that add value to the industry and are safe
                            for the environment.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <h4>Mission</h4>
                        <p>To feature technology development, as well as reliable human resources, and to be able to work
                            together in a healthy working environment with both customers and suppliers, with the aim to
                            create innovative and high-quality products.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4 stat-item">
                    <a href="https://maps.app.goo.gl/69gvCEbDPtWCpKD87" target="_blank">
                        <img src="{{ asset('images/pulo-gadung-foundary.avif') }}" class="img-fluid rounded" alt="">
                    </a>
                    <p class="fw-bold">Pulo Gadung Foundary</p>
                    <p class="fs-6">The Pulogadung foundry was established in 1986 and began operating in the field of
                        investment castings in the same year.
                        With a total area of 0,6 ha, it is located in Jl. Rawa Sumur Barat No.6 Jakarta Industrial Estate
                        Pulogadung.
                        The number of workers currently working on this site is about 300.</p>
                    <p class="fs-6 text-start"><i class="bi bi-geo-alt-fill"></i> Jl. Rawa Sumur Barat No.6 Kawasan Jakarta
                        Industrial Estate, RT.2/RW.9, Pulogadung, Cakung, East Jakarta City, Jakarta 13930</p>
                </div>
                <div class="col-md-4 stat-item">
                    <a href="https://maps.app.goo.gl/JRRK87xFATzgG95o6" target="_blank">
                        <img src="{{ asset('images/tambun-foundary.avif') }}" class="img-fluid rounded" alt="">
                    </a>
                    <p class="fw-bold">Tambun Foundary</p>
                    <p class="fs-6">The Tambun foundry was founded in the year 2002, sitting on an area of 2,02 Ha.
                        Located in Jl. Setia Dharma No.35 Tambun Selatan,
                        Bekasi 17510, over 600 workers are now working at the foundry. Aside of focusing on investment
                        casting, it also has a valve division,
                        sand casting division using furan process and permanent mould division (low pressure die casting),
                        adding variety to work that the company can produce.</p>
                    <p class="fs-6"><i class="bi bi-geo-alt-fill"></i> Jl. Setia Darma No.35, Setiadarma, Kec. Tambun
                        Sel., Kabupaten Bekasi, Jawa Barat 17510</p>
                </div>
                <div class="col-md-4 stat-item">
                    <a href="https://maps.app.goo.gl/T97pYNWGkxmeGneeA" target="_blank">
                        <img src="{{ asset('images/salatiga-foundary.avif') }}" class="img-fluid rounded" alt="">
                    </a>
                    <p class="fw-bold">Salatiga Foundary</p>
                    <p class="fs-6">The Salatiga foundry was established in 2016, with an area of 1.50 Ha. Located in
                        Jl.Srikandi, DK Brajan RT 001/RW 003 Noborejo, Argomulyo, Salatiga - Jawa Tengah. Employing around
                        135 workers, the foundry focuses on investment castings</p>
                    <p class="fs-6"><i class="bi bi-geo-alt-fill"></i> Noborejo, Kec. Argomulyo, Kota Salatiga, Jawa
                        Tengah 50736</p>
                </div>
            </div>
        </div>
    </section>
@endsection
