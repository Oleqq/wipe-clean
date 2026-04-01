<?php
/**
 * Contacts page company requisites band.
 *
 * @package wipe-clean
 */

$section = $args['section'] ?? wipe_clean_get_contacts_page_section_defaults( 'company_requisites_band' );
$items   = array(
	array(
		'label' => (string) ( $section['company_label'] ?? '' ),
		'value' => (string) ( $section['company_value'] ?? '' ),
	),
	array(
		'label' => (string) ( $section['ogrn_label'] ?? '' ),
		'value' => (string) ( $section['ogrn_value'] ?? '' ),
	),
	array(
		'label' => (string) ( $section['inn_label'] ?? '' ),
		'value' => (string) ( $section['inn_value'] ?? '' ),
	),
	array(
		'label' => (string) ( $section['kpp_label'] ?? '' ),
		'value' => (string) ( $section['kpp_value'] ?? '' ),
	),
);
?>
<section class="company-requisites-band">
	<div class="_container">
		<div class="company-requisites-band__wrapper">
			<ul class="company-requisites-band__list">
				<?php foreach ( $items as $item ) : ?>
					<?php if ( '' === trim( $item['label'] ) && '' === trim( $item['value'] ) ) : ?>
						<?php continue; ?>
					<?php endif; ?>
					<li class="company-requisites-band__item">
						<strong class="company-requisites-band__label"><?php echo esc_html( $item['label'] ); ?></strong>
						<span class="company-requisites-band__value"><?php echo esc_html( $item['value'] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
