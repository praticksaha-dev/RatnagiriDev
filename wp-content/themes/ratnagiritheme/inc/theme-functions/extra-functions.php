<?php
/**************************************************Admin CSS For All Starts**************************************************/
function admin_custom_css(){
	global $post_type;
	if((isset($_GET['post_type'])&& $_GET['post_type'] == 'page')||(isset($post_type)&& $post_type == 'page')){
		echo '<style type="text/css"></style>';		
		$post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;		
		$template_file = get_post_meta($post_id,'custom_post_template',true);
		if(($template_file == 'single-projects-old.php')){
			echo '<style type="text/css"></style>';
			}
		}
	if((isset($_GET['post_type'])&& $_GET['post_type'] == 'jobs')||(isset($post_type)&& $post_type == 'jobs')){
		echo '<style type="text/css">
			 textarea#job_requirement_content{width: 100%; height: 200px; }
			 input#job_application_url{width: 99%; }
			 </style>';		
		}
	if((isset($_GET['page'])&& $_GET['page'] == 'more-fields')){
		echo '<style type="text/css">
			caption{display:none; }
			</style>';		
		}	
	}
add_action('admin_head','admin_custom_css');
/**************************************************Admin CSS For All Starts**************************************************/