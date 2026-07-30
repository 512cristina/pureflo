<body>
	
<header class="fixed-top d-flex align-items-center">
	<div class="container-fluid px-5 position-relative d-flex align-items-center justify-content-between">
		<a href="/" class="logo"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/global/pureflo-logo-white.png"  width="267" height="68" alt="PureFlo"/></a>

		<nav id="navmenu" class="navmenu">
			<div class="nav1 text-end d-none d-lg-block"><a href="/distributors/" class="btn CTA-nav1-distributor">Find a Distributor</a></div>

			<ul><!-- Products & Distributor Mobile Only  -->
				<li class="nav1 d-lg-none"><a href="/distributors/" class="btn CTA-nav1-distributor">Find a Distributor</a></li>
				<li class="dropdown d-lg-none"><a href="/products/"><span>Products</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>

					<ul><small class="fst-italic fw-500 ms-3">NIOSH Certified</small>
						<li><a href="/products/niosh/pf3000/">PureFlo 3000</a></li>
						<li><a href="/products/niosh/esm/">PureFlo ESM+</a></li>

						<small class="fst-italic fw-500 ms-3">EN (CE) Certified</small>
						<li><a href="/products/en/pf2000/">PureFlo 2000</a></li>
						<li><a href="/products/en/purelite/">PureLite</a></li>
						<li><a href="/products/en/pf3000/">PureFlo 3000</a></li>
						<li><a href="/products/en/esm/">PureFlo ESM+</a></li>
					</ul>
				</li>

				<!-- Megamenu -->
				<li class="megamenu"><a href="/products/"><span>Products</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
					<!-- Desktop Megamenu -->
					<div class="container desktop-megamenu">
						<div class="tab-navigation">
							<ul class="nav nav-tabs flex-column" id="megamenu-tabs" role="tablist">

								<li class="nav-item" role="presentation">
									<button class="nav-link active" id="mega-tab-2-tab" data-bs-toggle="tab" data-bs-target="#mega-tab-2" type="button" role="tab" aria-controls="mega-tab-2" aria-selected="true">
										<i class="bi bi-lungs-fill"></i>
										<span>Respiratory Protection</span>
									</button>
								</li>
								<li class="nav-item" role="presentation">
									<button class="nav-link" id="mega-tab-3-tab" data-bs-toggle="tab" data-bs-target="#mega-tab-3" type="button" role="tab" aria-controls="mega-tab-3" aria-selected="false" tabindex="-1">
										<i class="bi bi-building"></i>
										<span>Industries</span>
									</button>
								</li>

							</ul>
						</div>

						<div class="tab-content">

							<!-- Respiratory Protection Tab -->
							<div class="tab-pane fade show active" id="mega-tab-2" role="tabpanel" aria-labelledby="mega-tab-2-tab">
								<div class="row">
									<div class="col-12 col-xl-6 product-section">
										
										<h4>Working for You</h4>

										<div class="product-list">
											<div class="product-item">
												<i class="bi bi-cpu-fill"></i>
												<div>
												<a href="/products/#tab-ence">NIOSH Certified</a>
												<small>Certified to U.S. NIOSH standards for respiratory protection used throughout the United States and Canada.</small>
												<ul>
													<li><a href="/products/niosh/pf3000/">PureFlo 3000</a></li>
													<li><a href="/products/niosh/esm/">PureFlo ESM+</a></li>
												</ul>
												</div>
											</div>

											<div class="product-item">
												<i class="bi bi-cpu-fill"></i>
												<div>
												<a href="/products/#tab-ence">EN (CE) Certified</a>
												<small>Certified to European EN (CE) standards, commonly required throughout the EU, Australia, and New Zealand.</small>
												<ul>
													<li><a href="/products/en/purelite/">PureLite</a></li>
													<li><a href="/products/en/pf2000/">PureFlo 2000</a></li>
													<li><a href="/products/en/pf3000/">PureFlo 3000</a></li>
													<li><a href="/products/en/esm/">PureFlo ESM+</a></li>
												</ul>
												</div>
											</div>		
									
										</div>										
									</div>
																	
									<div class="col-12 col-xl-6 product-section text-center">																		
										<div class="product-list">
											<a href="/trial/"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/global/try-purflo-risk-free.png" width="201" height="200" alt="Try PureFlo Risk Free"  class="mt-4"  data-aos="flip-up" data-aos-duration="3000"></a>
										</div>
										<a href="/trial/" class="btn btn-primary mt-4 ms-3 CTA-nav-products">Learn more</a>
									</div>
								</div> <!-- End Row -->

								<div class="row mt-4">
									<div class="col col-xl-8 d-flex align-items-center gap-3 feature">
										<img src="<?php echo get_template_directory_uri(); ?>/assets/img/nav/welder-200x200.jpg" width="200" height="200"  alt="Learn more how Gentex's experience can work for you | decorative photo of a welder at work">
										<div class="feature-info">
											<h5 class="mt-0">Find a Distributor</h5>
											<p class="mb-0">Ready to buy? Find a distributor closests to you.</p>
											<a href="/distributor/" class="callout-link">Learn more now <i class="bi bi-arrow-right"></i></a>
										</div>	
									</div>								
								</div>

							</div> <!-- End: Tab -->

							<!-- Industries Tab -->
							<div class="tab-pane fade" id="mega-tab-3" role="tabpanel" aria-labelledby="mega-tab-3-tab">
								<div class="row">
									<div class="col-12 col-xl-6 product-section">
										
										<h4>Industries We Serve</h4>

										<div class="product-list">											
											<a href="/industries/#industry-tab-1" class="product-link">
												<i class="bi bi-fire"></i>
												<div>
													<span>Foundries / High Heat</span>
													<small>Safety is top of mind.</small>
												</div>
											</a>

											<a href="/industries/#industry-tab-2" class="product-link">
												<i class="bi bi-cone-striped"></i>
												<div>
													<span>Construction</span>
													<small>Keeping you safe.</small>
												</div>
											</a>

											<a href="/industries/#industry-tab-3" class="product-link">
												<i class="bi bi-cpu-fill"></i>
												<div>
													<span>General Trades / Technology</span>
													<small>Configurable design.  </small>
												</div>
											</a>

											<div class="mt-3"><a href="/industries/" class="btn btn-primary  mx-auto CTA-nav-industries-learn-more">Learn more</a></div>
										</div>
									</div>
									
									<div class="col-12 col-xl-6 product-section">
										<h4>&nbsp;</h4>
										<div class="product-list">
											<a href="/industries/#industry-tab-4" class="product-link">
												<i class="bi bi-lightning-charge"></i>
												<div>
													<span>Welding / Metalforming / Fabrication</span>
													<small>A breakthrough in safety. </small>
												</div>
											</a>

											<a href="/industries/#industry-tab-5" class="product-link">
												<i class="bi bi-radioactive"></i>
												<div>
													<span>Nuclear</span>
													<small>Safety and comfort in one unit.  </small>
												</div>
											</a>

											<a href="/industries/#industry-tab-6" class="product-link">
												<i class="bi bi-prescription"></i>
												<div>
													<span>Laboratory / Pharmaceutical / Healthcare</span>
													<small>World-class protection in one.</small>
												</div>
											</a>	
										</div>
									</div>
								</div> <!-- End Row -->
									
								<div class="row mt-4">
									<div class="col col-xl-8 d-flex align-items-center gap-3 feature">
										<img src="<?php echo get_template_directory_uri(); ?>/assets/img/nav/phone-photo.jpg" width="150" height="100" alt="Customer Support | decorative photo of a phone">
										<div class="feature-info">
											<h5 class="mt-0">Customer Service</h5>
											<p class="mb-0">Call <a href="tel:+1888-894-1755">888-894-1755</a>, Monday-Friday, 9am-5pm ET.</p>
											<a href="tel:+18888941755" class="callout-link">Call now <i class="bi bi-arrow-right"></i></a>
										</div>	
									</div>								
								</div>

							</div> <!-- End: Tab -->

						</div><!-- End Tab Content-->
					</div><!-- End Desktop Megamenu -->
				</li><!-- /Megamenu -->

				
				<li class="dropdown"><a href="/industries/"><span>Industries</span></a></li>

				<li class="dropdown"><a href="/about/"><span>About</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
					<ul>
						<li><a href="/about/">About Us</a></li>
						<li><a href="/faq/">FAQs</a></li>
						<li><a href="/conformity/">Declarations of Conformity</a></li>
					</ul>
				</li>
				<li class="dropdown"><a href="/resources/"><span>Resources</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
					<ul>    
						<li><a href="/resources/">Resource Library</a></li>
						<li><a href="/faq/">FAQs</a></li>
						<li><a href="/distributors/">Find a Distributor</a></li>
					</ul>
				</li>
				<li><a href="/contact/">Contact</a></li>

				<button type="button" class="search-toggle ms-3"><i class="bi bi-search"></i></button>
				<!-- Search container -->
				<div class="nav-search">
					<form role="search" method="get" action="<?php echo home_url('/'); ?>" class="search-form d-flex align-items-center">
						<input  type="search"  class="form-control search-input"  placeholder="Search..." name="s" onclick="this.placeholder=''">
					</form>

				</div>
			</ul> 

			<i class="mobile-nav-toggle d-lg-none bi bi-list"></i>
		</nav>

	</div>	
</header>

<main class="main">
