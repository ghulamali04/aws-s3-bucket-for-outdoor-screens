<!DOCTYPE html>
<!--
Author: Keenthemes
Product Name: Metronic - Bootstrap 5 HTML, VueJS, React, Angular & Laravel Admin Dashboard Theme
Purchase: https://1.envato.market/EA4JP
Website: http://www.keenthemes.com
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html lang="en">
	<!--begin::Head-->
	<head><base href="<?php echo base_url('public'); ?>/">
		<title>Samsung Outdoor Screens</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<link rel="shortcut icon" href="dashboard/media/logos/favicon.ico" />
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Page Vendor Stylesheets(used by this page)-->
		<link href="dashboard/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
		<link href="dashboard/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
        <?= $this->renderSection('css') ?>
        <!--end::Page Vendor Stylesheets-->
		<!--begin::Global Stylesheets Bundle(used by all pages)-->
		<link href="dashboard/plugins/global/plugins.dark.bundle.css" rel="stylesheet" type="text/css" />
		<link href="dashboard/css/style.dark.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed" style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
		<!--begin::Main-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Page-->
			<div class="page d-flex flex-row flex-column-fluid">
				<!--begin::Wrapper-->
				<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
					<!--begin::Header-->
					<div id="kt_header" style="" class="header align-items-stretch">
						<!--begin::Container-->
						<div class="container-xxl d-flex align-items-stretch justify-content-between">
							<!--begin::Aside mobile toggle-->
							<!--end::Aside mobile toggle-->
							<!--begin::Logo-->
							<div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-lg-15">
								<a href="<?php echo base_url(); ?>/">
									<h2 class="mb-0">SamsungOutdoorScreen</h2>
								</a>
							</div>
							<!--end::Logo-->
							<!--begin::Wrapper-->
							<div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
								<!--begin::Navbar-->
								<div class="d-flex align-items-stretch" id="kt_header_nav">
									<!--begin::Menu wrapper-->
									<div class="header-menu align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_header_menu_mobile_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
										<!--begin::Menu-->
										<div class="menu menu-lg-rounded menu-column menu-lg-row menu-state-bg menu-title-gray-700 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-400 fw-bold my-5 my-lg-0 align-items-stretch" id="#kt_header_menu" data-kt-menu="true">
											<div class="menu-item menu-lg-down-accordion me-lg-1">
												<a class="menu-link py-3" href="<?php echo base_url(); ?>/dashboard/">
													<span class="menu-title">Dashboard</span>
													<span class="menu-arrow d-lg-none"></span>
												</a>
								
											</div>
                                            <div class="menu-item menu-lg-down-accordion me-lg-1">
												<a class="menu-link py-3" href="<?php echo base_url(); ?>/dashboard/events">
													<span class="menu-title">Events</span>
													<span class="menu-arrow d-lg-none"></span>
												</a>
								
											</div>
		
										</div>
										<!--end::Menu-->
									</div>
									<!--end::Menu wrapper-->
								</div>
								<!--end::Navbar-->
								<!--begin::Toolbar wrapper-->
								<div class="d-flex align-items-stretch flex-shrink-0">
									
							
									<!--begin::User menu-->
									<div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
										<!--begin::Menu wrapper-->
										<div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
											<img src="dashboard/media/avatars/300-1.jpg" alt="user" />
										</div>
										<!--begin::User account menu-->
										<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px" data-kt-menu="true">
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<div class="menu-content d-flex align-items-center px-3">
													<!--begin::Avatar-->
													<div class="symbol symbol-50px me-5">
														<img alt="Logo" src="dashboard/media/avatars/300-1.jpg" />
													</div>
													<!--end::Avatar-->
													<!--begin::Username-->
													<div class="d-flex flex-column">
														<div class="fw-bolder d-flex align-items-center fs-5"><?= session()->get('name') ?>
														<span class="badge badge-light-success fw-bolder fs-8 px-2 py-1 ms-2">Pro</span></div>
														<a href="#" class="fw-bold text-muted text-hover-primary fs-7"><?= session()->get('email') ?></a>
													</div>
													<!--end::Username-->
												</div>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu separator-->
											<div class="separator my-2"></div>
											<!--end::Menu separator-->
									
											<!--begin::Menu item-->
											<div class="menu-item px-5 my-1">
												<a href="<?= base_url() ?>/dashboard/users/settings" class="menu-link px-5">Settings</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu item-->
											<div class="menu-item px-5">
												<a href="<?= base_url() ?>/dashboard/logout" class="menu-link px-5">Sign Out</a>
											</div>
											<!--end::Menu item-->
                                            
										</div>
										<!--end::User account menu-->
										<!--end::Menu wrapper-->
									</div>
									<!--end::User menu-->
									<!--begin::Header menu toggle-->
									<div class="d-flex align-items-center d-lg-none ms-2 me-n3" title="Show header menu">
										<div class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px" id="kt_header_menu_mobile_toggle">
											<!--begin::Svg Icon | path: icons/duotune/text/txt001.svg-->
											<span class="svg-icon svg-icon-1">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
													<path d="M13 11H3C2.4 11 2 10.6 2 10V9C2 8.4 2.4 8 3 8H13C13.6 8 14 8.4 14 9V10C14 10.6 13.6 11 13 11ZM22 5V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4V5C2 5.6 2.4 6 3 6H21C21.6 6 22 5.6 22 5Z" fill="currentColor" />
													<path opacity="0.3" d="M21 16H3C2.4 16 2 15.6 2 15V14C2 13.4 2.4 13 3 13H21C21.6 13 22 13.4 22 14V15C22 15.6 21.6 16 21 16ZM14 20V19C14 18.4 13.6 18 13 18H3C2.4 18 2 18.4 2 19V20C2 20.6 2.4 21 3 21H13C13.6 21 14 20.6 14 20Z" fill="currentColor" />
												</svg>
											</span>
											<!--end::Svg Icon-->
										</div>
									</div>
									<!--end::Header menu toggle-->
								</div>
								<!--end::Toolbar wrapper-->
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Header-->
					<!--begin::Content-->
					<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
						<?= $this->renderSection('content') ?>
					</div>
					<!--end::Content-->
					<!--begin::Footer-->
					<div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
						<!--begin::Container-->
						<div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
							<!--begin::Copyright-->
							<div class="text-dark order-2 order-md-1">
								<span class="text-muted fw-bold me-1">2022©</span>
								<a href="<?= base_url() ?>/" target="_blank" class="text-gray-800 text-hover-primary">SamsungOutdoorScreen</a>
							</div>
							<!--end::Copyright-->
							<!--begin::Menu-->
							<ul class="menu menu-gray-600 menu-hover-primary fw-bold order-1">
								
							</ul>
							<!--end::Menu-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Footer-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::Root-->


		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
			<span class="svg-icon">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
					<rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor" />
					<path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="currentColor" />
				</svg>
			</span>
			<!--end::Svg Icon-->
		</div>
		<!--end::Scrolltop-->
	
		
<!--begin::Toast-->
<div id="kt_docs_toast_stack_container" class="toast-container position-fixed bottom-0 end-0 p-3 z-index-3">
<?php if(session()->getFlashdata('success')):?>
	<div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-kt-docs-toast="stack1">
        <div class="toast-header">
            <span class="svg-icon svg-icon-2 svg-icon-primary me-3">
				<i class="fas fa-check-circle text-success"></i>
			</span>
            <strong class="me-auto text-success">Success</strong>
            <small></small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <?= session()->getFlashdata('success') ?>
        </div>
    </div>
	<?php endif; ?>
	<?php if(session()->getFlashdata('error')):?>
	<div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-kt-docs-toast="stack2">
        <div class="toast-header">
            <span class="svg-icon svg-icon-2 svg-icon-primary me-3">
				<i class="fas fa-times-circle text-danger"></i>
			</span>
            <strong class="me-auto text-danger">Error</strong>
            <small></small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <?= session()->getFlashdata('error') ?>
        </div>
    </div>
	<?php endif; ?>
</div>

<!--end::Toast-->

		<!--begin::Javascript-->
		<script>var hostUrl = "<?php echo base_url('public'); ?>/";</script>
		<!--begin::Global Javascript Bundle(used by all pages)-->
		<script src="dashboard/plugins/global/plugins.bundle.js"></script>
		<script src="dashboard/js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Page Vendors Javascript(used by this page)-->
		<script src="dashboard/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
		<script src="dashboard/plugins/custom/datatables/datatables.bundle.js"></script>
		<!--end::Page Vendors Javascript-->
		<!--begin::Page Custom Javascript(used by this page)-->
		<script src="dashboard/js/widgets.bundle.js"></script>
		<script src="dashboard/js/custom/widgets.js"></script>
		<script src="dashboard/js/custom/apps/chat/chat.js"></script>
		<script src="dashboard/js/custom/utilities/modals/upgrade-plan.js"></script>
		<script src="dashboard/js/custom/utilities/modals/create-app.js"></script>
		<script src="dashboard/js/custom/utilities/modals/users-search.js"></script>
		<script>
	const container = document.getElementById('kt_docs_toast_stack_container');
</script>
<?php if(session()->getFlashdata('success')):?>
		<script>
			// Select elements

const targetElement1 = document.querySelector('[data-kt-docs-toast="stack1"]'); // Use CSS class or HTML attr to avoid duplicating ids



// Create new toast element
const newToast1 = targetElement1.cloneNode(true);
    container.append(newToast1);

    // Create new toast instance --- more info: https://getbootstrap.com/docs/5.1/components/toasts/#getorcreateinstance
    const toast1 = bootstrap.Toast.getOrCreateInstance(newToast1);

    // Toggle toast to show --- more info: https://getbootstrap.com/docs/5.1/components/toasts/#show
    toast1.show();

		</script>
		<?php endif; ?>
		<?php if(session()->getFlashdata('error')):?>
			<script>
				// Select elements
	
	
	const targetElement2 = document.querySelector('[data-kt-docs-toast="stack2"]');
	

		// Create new toast element
	const newToast2 = targetElement2.cloneNode(true);
		container.append(newToast2);
	
		// Create new toast instance --- more info: https://getbootstrap.com/docs/5.1/components/toasts/#getorcreateinstance
		const toast2 = bootstrap.Toast.getOrCreateInstance(newToast2);
	
		// Toggle toast to show --- more info: https://getbootstrap.com/docs/5.1/components/toasts/#show
		toast2.show();
			</script>
		<?php endif; ?>
		<!--end::Page Custom Javascript-->
        <?= $this->renderSection('javascript') ?>
		<!--end::Javascript-->

	</body>
	<!--end::Body-->
</html>