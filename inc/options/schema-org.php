<?php if ( ! defined('LP_THEME_DIR')) exit('No direct script access allowed');

// schema.org options

// микроразметка schema.org
	echo '<script type="application/ld+json">'.
	{
			"@context": "http://schema.org",
			"@type": "Article",
			"mainEntityOfPage": {
					"@type": "WebPage",
					"@id": "<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'';?>"
			},
			"headline": "<?php if(get_field('mytitle')) echo get_field('mytitle');?>",
			"image": {
					"@type": "ImageObject",
					"url": "<?php $postid = $post->ID; echo get_the_post_thumbnail_url($postid) ;?>",
					"width": 1280,
					"height": 800
			},
			"datePublished": "<?php echo get_the_date('c');?>",
			"dateModified": "<?php echo get_the_modified_date('c');?>",
			"author": {
					"@type": "Person",
					"name": "Дмитрий Дмитриев"
			},
			"publisher": {
					"@type": "Organization",
					"name": "Дмитрий Дмитриев - персональный блог",
					"logo": {
							"@type": "ImageObject",
							"url": "http://ВАШ САЙТ.РУ/wp-content/uploads/logo.png",
							"width": 120,
							"height": 120
					}
			},
			"description": "<?php if(get_field('description')) echo get_field('description');?>"
	}
	.'</script>';