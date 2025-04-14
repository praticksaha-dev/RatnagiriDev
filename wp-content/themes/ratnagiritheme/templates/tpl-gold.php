<?php /*Template Name: Layout: Gold */
 get_header();
 if (have_posts()):
    while (have_posts()):
        the_post();
 get_sidebar('banner');
?>

<section class="gallery jewellery-section">
        <div class="container">
			<div class="row">
				<div class="col-md-12 text-center">
					<div class="hs_about_heading_wrapper">
						<h2><?php echo get_field('heading_text'); ?></h2>
						<h4><span>&nbsp;</span></h4>
					</div>
				</div>
			</div>
            <div class="row same-height-cardbox-row-style">
	<?php
	$args = array(
		'post_type' => 'product',
		'posts_per_page' => -1,
		'product_cat' => 'gold',
		'orderby' => 'menu_order',
		'order' => 'ASC'
	);

	$gold_products = new WP_Query($args);

	if ($gold_products->have_posts()) :
		while ($gold_products->have_posts()) : $gold_products->the_post();
			global $product;
			?>
			<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6 mb-20">
				<div class="hs_shop_prodt_main_box">
					<a href="#!">
						<div class="hs_shop_prodt_img_wrapper">
							<?php if (has_post_thumbnail()) {
								the_post_thumbnail('medium');
							} ?>
						</div>
					</a>
					<div class="hs_shop_prodt_img_cont_wrapper">
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<div class="p-text-box">
						<?php echo get_the_excerpt(); ?>
							
						</div>
					</div>
				</div>
			</div>
			<?php
		endwhile;
		wp_reset_postdata();
	endif;
	?>
</div>

        </div>
    </section>

      <div class="modal" id="imageModal">
        <div class="modal-content">
          <span class="close-btn" id="closeModal">&times;</span>
          <span class="nav-arrow nav-left" id="prev">&#8592;</span>
          <span class="nav-arrow nav-right" id="next">&#8594;</span>
          <img id="modalImage" src="" alt="Modal Image">
          <div class="modal-description" id="modalDesc"></div>
        </div>
      </div>
      
		<script>
		const images = document.querySelectorAll('.gallery img');
		const modal = document.getElementById('imageModal');
		const modalImg = document.getElementById('modalImage');
		const modalDesc = document.getElementById('modalDesc');
		const closeModal = document.getElementById('closeModal');
		const prevBtn = document.getElementById('prev');
		const nextBtn = document.getElementById('next');

		let currentIndex = 0;

		function showModal(index) {
		const img = images[index];
		modal.style.display = 'flex';
		modalImg.src = img.src;
		modalDesc.textContent = img.getAttribute('data-description');
		currentIndex = index;
		}

		images.forEach((img, index) => {
		img.addEventListener('click', () => showModal(index));
		});

		closeModal.onclick = () => {
		modal.style.display = 'none';
		};

		prevBtn.onclick = () => {
		currentIndex = (currentIndex - 1 + images.length) % images.length;
		showModal(currentIndex);
		};

		nextBtn.onclick = () => {
		currentIndex = (currentIndex + 1) % images.length;
		showModal(currentIndex);
		};

		window.onclick = (e) => {
		if (e.target == modal) {
			modal.style.display = 'none';
		}
		};

		// Optional: Keyboard navigation
		document.addEventListener('keydown', function (e) {
		if (modal.style.display === 'flex') {
			if (e.key === 'ArrowRight') nextBtn.click();
			if (e.key === 'ArrowLeft') prevBtn.click();
			if (e.key === 'Escape') closeModal.click();
		}
		});
		</script>
		<!--main js file end-->

<?php
endwhile;
endif;
get_footer(); ?>