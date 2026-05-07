<?php $region = get_current_region(); ?>
<body>
	
<header class="fixed-top d-flex align-items-center">
	<div class="container-fluid px-5 position-relative d-flex align-items-center justify-content-between">
		<a href="<?php echo home_url(); ?>" class="logo"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/global/pureflo-logo-white.png"  width="267" height="68" alt="PureFlo"/></a>

		<nav id="navmenu" class="navmenu">
			<ul><!-- Products Mobile Only  -->
				<li class="dropdown d-lg-none"><a href="/<?php echo $region; ?>/products/"><span>Products</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
					<ul>
						<li><a href="/<?php echo $region; ?>/products/"><span>PureFlo 3000 PAPR Series</span></a></li>
						<li><a href="/<?php echo $region; ?>/products/"><span>PureFlo ESM+ Series</span></a></li>
						<li><a href="/<?php echo $region; ?>/products/"><span>Filter &amp; Parts</span></a></li>
						<li><a href="/<?php echo $region; ?>/industries/"><span>Industries</span></a></li>
					</ul>
				</li>

				<!-- Megamenu -->
				<li class="megamenu"><a href="/<?php echo $region; ?>/products/"><span>Products</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
					<!-- Desktop Megamenu -->
					<div class="container desktop-megamenu">
						<div class="tab-navigation">
							<ul class="nav nav-tabs flex-column" id="megamenu-tabs" role="tablist">

								<li class="nav-item" role="presentation">
									<button class="nav-link active" id="mega-tab-2-tab" data-bs-toggle="tab" data-bs-target="#mega-tab-2" type="button" role="tab" aria-controls="mega-tab-2" aria-selected="true">
										<i class="fa-solid fa-mask-face"></i>
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
											<a href="/<?php echo $region; ?>/products/" class="product-link">
												<i class="bi bi-cpu-fill"></i>
												<div>
												<span>PureFlo 3000 PAPR Series</span>
												<small>PureFlo PAPRs provide respiratory, head, eye, and face protection in one integrated head-top unit, reducing the need for multiple PPE components. And PureFlo PAPRs are ready to use right out of the box, with no complex setup required, so you can get to work faster. </small>
												</div>
											</a>
											<a href="/<?php echo $region; ?>/products/" class="product-link">
												<i class="bi bi-cpu-fill"></i>
												<div>
												<span>PureFlo ESM+ Series</span>
												<small>In high-heat environments, extra protection is essential. PureFlo has you covered with dependable protection when it matters most.</small>
												</div>
											</a>			
											<a href="/<?php echo $region; ?>/products/" class="product-link">
												<i class="bi bi-cpu-fill"></i>
												<div>
												<span>Filters &amp; Parts</span>
												<small>In high-heat environments, extra protection is essential. PureFlo has you covered with dependable protection when it matters most.</small>
												</div>
											</a>									
										</div>										
									</div>
																	
									<div class="col-12 col-xl-6 product-section text-center">	
									<?php if ($region === 'us'): ?>									
										<div class="product-list">
											<a href="/us/trial/"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/global/try-purflo-risk-free.png" width="201" height="200" alt="Try PureFlo Risk Free"  class="mt-4"  data-aos="flip-up" data-aos-duration="3000"></a>
										</div>
										<a href="/us/trial/" class="btn btn-primary mt-4 ms-3 CTA-nav-products">Learn more</a>
									<?php endif; ?>
									</div>
								</div> <!-- End Row -->

								<div class="row mt-4">
									<div class="col col-xl-8 d-flex align-items-center gap-3 feature">
										<img src="<?php echo get_template_directory_uri(); ?>/assets/img/nav/welder-200x200.jpg" width="200" height="200"  alt="Learn more how Gentex's experience can work for you | decorative photo of a welder at work">
										<div class="feature-info">
											<h5>Put Our Experience to Work for You</h5>
											<a href="/<?php echo $region; ?>/contact/" class="callout-link">Learn more now <i class="bi bi-arrow-right"></i></a>
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
											<a href="/<?php echo $region; ?>/industries/#industry-tab-1" class="product-link">
												<i class="bi bi-fire"></i>
												<div>
													<span>Foundries / High Heat</span>
													<small>Safety is top of mind.</small>
												</div>
											</a>

											<a href="/<?php echo $region; ?>/industries/#industry-tab-2" class="product-link">
												<i class="bi bi-cone-striped"></i>
												<div>
													<span>Construction</span>
													<small>Keeping you safe.</small>
												</div>
											</a>

											<a href="/<?php echo $region; ?>/industries/#industry-tab-3" class="product-link">
												<i class="bi bi-cpu-fill"></i>
												<div>
													<span>General Trades / Technology</span>
													<small>Configurable design.  </small>
												</div>
											</a>

											<div class="mt-3"><a href="/<?php echo $region; ?>/industries/" class="btn btn-primary  mx-auto CTA-nav-industries-learn-more">Learn more</a></div>
										</div>
									</div>
									
									<div class="col-12 col-xl-6 product-section">
										<h4>&nbsp;</h4>
										<div class="product-list">
											<a href="/<?php echo $region; ?>/industries/#industry-tab-4" class="product-link">
												<i class="bi bi-lightning-charge"></i>
												<div>
													<span>Welding / Metalforming / Fabrication</span>
													<small>A breakthrough in safety. </small>
												</div>
											</a>

											<a href="/<?php echo $region; ?>/industries/#industry-tab-5" class="product-link">
												<i class="bi bi-radioactive"></i>
												<div>
													<span>Nuclear</span>
													<small>Safety and comfort in one unit.  </small>
												</div>
											</a>

											<a href="/<?php echo $region; ?>/industries/#industry-tab-6" class="product-link">
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
											<h5>Customer Service</h5>
											<p>Call <a href="tel:+1888-894-1755">888-894-1755</a>, Monday-Friday, 9am-5pm ET.</p>
											<a href="tel:+18888941755" class="callout-link">Call now <i class="bi bi-arrow-right"></i></a>
										</div>	
									</div>								
								</div>

							</div> <!-- End: Tab -->

						</div><!-- End Tab Content-->
					</div><!-- End Desktop Megamenu -->
				</li><!-- /Megamenu -->

				
				<li class="dropdown"><a href="/<?php echo $region; ?>/industries/"><span>Industries</span></a></li>

				<li class="dropdown"><a href="/<?php echo $region; ?>/about/"><span>About</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
					<ul>
						<li><a href="/<?php echo $region; ?>/about/">About Us</a></li>
						<li><a href="/<?php echo $region; ?>/news/">News</a></li>
						<li><a href="/<?php echo $region; ?>/faq/">FAQs</a></li>
					<?php if (in_array($region, ['eu', 'anz'])): ?>	
						<li><a href="/<?php echo $region; ?>/conformity/">Declarations of Conformity</a></li>
					<?php endif; ?>
					</ul>
				</li>
				<li class="dropdown"><a href="/<?php echo $region; ?>/resources/"><span>Resources</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
					<ul>    
						<li><a href="/<?php echo $region; ?>/resources/">Resource Library</a></li>
						<li><a href="/<?php echo $region; ?>/faq/">FAQs</a></li>
						<li><a href="/<?php echo $region; ?>/distributors/">Find a Distributor</a></li>
					</ul>
				</li>
				<li><a href="/<?php echo $region; ?>/contact/">Contact</a></li>

				<!-- Flag Selector -->
				<?php $region_esc = htmlspecialchars($region, ENT_QUOTES, 'UTF-8');
					  $template_uri = get_template_directory_uri();  ?>

				<div class="region-switcher">
					<div class="region-current" onclick="toggleRegionMenu()">
						<img id="currentFlag" src="<?= $template_uri; ?>/assets/img/global/flag-<?= $region_esc; ?>-icon.png" alt="Region flag <?= $region_esc; ?>" width="30">
						<span class="caret"></span>
					</div>

					<div class="region-menu" id="regionMenu">
						<img src="<?php echo get_template_directory_uri(); ?>/assets/img/global/flag-us-icon.png" alt="US" onclick="switchRegion('us')" width="30">
						<img src="<?php echo get_template_directory_uri(); ?>/assets/img/global/flag-eu-icon.png" alt="EU" onclick="switchRegion('eu')" width="30">
						<img src="<?php echo get_template_directory_uri(); ?>/assets/img/global/flag-anz-icon.png" alt="ANZ" onclick="switchRegion('anz')" width="30">
					</div>
				</div>

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
