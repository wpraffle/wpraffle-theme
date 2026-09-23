<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$native = array(
    'page-about.php'        => 'WPRaffle — About',
    'page-charities.php'    => 'WPRaffle — Charities',
    'page-competitions.php' => 'WPRaffle — Competitions',
    'page-contact.php'      => 'WPRaffle — Contact',
    'page-draw-results.php' => 'WPRaffle — Draw Results',
    'page-faq.php'          => 'WPRaffle — FAQ',
    'page-full-width.php'   => 'WPRaffle — Full Width',
    'page-how-it-works.php' => 'WPRaffle — How It Works',
    'page-instant-wins.php' => 'WPRaffle — Instant Wins',
    'page-legal.php'        => 'WPRaffle — Legal & Policy',
    'page-winners.php'      => 'WPRaffle — Winners',
);
$templates = WPRaffle_Theme_Elementor_Importer::available_templates();
$builder = array_filter( $templates, fn($k) => 0 === strpos($k,'theme-builder/'), ARRAY_FILTER_USE_KEY );
$sections = array_filter( $templates, fn($k) => 0 === strpos($k,'sections/'), ARRAY_FILTER_USE_KEY );
$elementor_ready = WPRaffle_Theme_Elementor_Importer::is_elementor_ready();
if ( isset($_GET['elementor_imported_all']) ) {
    printf('<div class="notice notice-success inline"><p>%s</p></div>', esc_html( sprintf( __( '%d WPRaffle Elementor templates imported or updated.', 'wpraffle-theme' ), absint($_GET['elementor_imported_all']) ) ) );
}
if ( isset($_GET['elementor_error']) ) {
    echo '<div class="notice notice-error inline"><p>' . esc_html__( 'The template could not be imported. Check that Elementor is active and try again.', 'wpraffle-theme' ) . '</p></div>';
}
?>
<section class="wpr-panel">
    <div class="wprt-card-heading"><div><span class="dashicons dashicons-wordpress"></span><h2><?php esc_html_e('Native WordPress templates','wpraffle-theme'); ?></h2></div></div>
    <p class="wpr-panel-intro"><?php esc_html_e('Fast, builder-free templates that inherit the active WPRaffle style automatically.','wpraffle-theme'); ?></p>
    <div class="wprt-template-grid"><?php foreach($native as $file=>$label): ?><div class="wprt-template-card"><span class="dashicons dashicons-media-document"></span><div><strong><?php echo esc_html($label); ?></strong><small><?php echo esc_html($file); ?></small></div><span class="wprt-ready-pill"><?php esc_html_e('Ready','wpraffle-theme'); ?></span></div><?php endforeach; ?></div>
</section>
<section class="wpr-panel">
    <div class="wprt-card-heading"><div><span class="dashicons dashicons-layout"></span><h2><?php esc_html_e('Elementor Template Library','wpraffle-theme'); ?></h2></div>
    <?php if($elementor_ready): ?><form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>"><input type="hidden" name="action" value="wprt_import_elementor_library"><?php wp_nonce_field('wprt_import_elementor_library'); ?><button class="button button-primary"><?php esc_html_e('Import / Update Complete Library','wpraffle-theme'); ?></button></form><?php endif; ?></div>
    <p class="wpr-panel-intro"><?php echo esc_html($elementor_ready ? __('Import any bundled design with one click. Re-importing updates WPRaffle-owned templates instead of creating duplicates.','wpraffle-theme') : __('Activate Elementor to enable one-click imports. The native WPRaffle templates remain fully usable without it.','wpraffle-theme')); ?></p>
    <div class="wprt-template-grid">
    <?php foreach($builder as $file=>$label): $id=WPRaffle_Theme_Elementor_Importer::imported_post_id($file); ?>
        <div class="wprt-template-card"><span class="dashicons dashicons-welcome-widgets-menus"></span><div><strong><?php echo esc_html($label); ?></strong><small>elementor/<?php echo esc_html($file); ?></small></div>
        <?php if($elementor_ready): ?><form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>"><input type="hidden" name="action" value="wprt_import_elementor_template"><input type="hidden" name="template" value="<?php echo esc_attr($file); ?>"><?php wp_nonce_field('wprt_import_elementor_template'); ?><button class="button button-small"><?php echo $id ? esc_html__('Update','wpraffle-theme') : esc_html__('Import','wpraffle-theme'); ?></button></form><?php else: ?><span class="wprt-ready-pill"><?php esc_html_e('Bundled','wpraffle-theme'); ?></span><?php endif; ?>
        </div>
    <?php endforeach; ?></div>
    <h3><?php esc_html_e('Reusable sections','wpraffle-theme'); ?></h3>
    <div class="wprt-template-grid">
    <?php foreach($sections as $file=>$label): $id=WPRaffle_Theme_Elementor_Importer::imported_post_id($file); ?><div class="wprt-template-card"><span class="dashicons dashicons-screenoptions"></span><div><strong><?php echo esc_html($label); ?></strong><small>elementor/<?php echo esc_html($file); ?></small></div><?php if($elementor_ready): ?><form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>"><input type="hidden" name="action" value="wprt_import_elementor_template"><input type="hidden" name="template" value="<?php echo esc_attr($file); ?>"><?php wp_nonce_field('wprt_import_elementor_template'); ?><button class="button button-small"><?php echo $id ? esc_html__('Update','wpraffle-theme') : esc_html__('Import','wpraffle-theme'); ?></button></form><?php endif; ?></div><?php endforeach; ?>
    </div>
</section>
