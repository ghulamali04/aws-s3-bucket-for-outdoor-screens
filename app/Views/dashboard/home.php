<?= $this->extend('dashboard/base') ?>

<?= $this->section('content') ?>
<!--begin::Toolbar-->
<div class="toolbar" id="kt_toolbar">
							<!--begin::Container-->
							<div id="kt_toolbar_container" class="container-xxl d-flex flex-stack">
								<!--begin::Page title-->
								<div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
									<!--begin::Title-->
									<h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">Dashboard</h1>
									<!--end::Title-->
									<!--begin::Separator-->
									<span class="h-20px border-gray-300 border-start mx-4"></span>
									<!--end::Separator-->
									<!--begin::Breadcrumb-->
									<ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
										<!--begin::Item-->
										
									</ul>
									<!--end::Breadcrumb-->
								</div>
								<!--end::Page title-->
								<!--begin::Actions-->
								<div class="d-flex align-items-center gap-2 gap-lg-3">
									
								</div>
								<!--end::Actions-->
							</div>
							<!--end::Container-->
						</div>
						<!--end::Toolbar-->
						<!--begin::Post-->
						<div class="post d-flex flex-column-fluid" id="kt_post">
							<!--begin::Container-->
							<div id="kt_content_container" class="container-xxl">
							<div class="row g-5 g-xl-8">
						<div class="col-xl-3">
							<!--begin::Statistics Widget 5-->
							<a href="javascript:;" class="card bg-body hoverable card-xl-stretch mb-xl-8">
								<!--begin::Body-->
								<div class="card-body">
									<!--begin::Svg Icon | path: icons/duotune/general/gen032.svg-->
									<span class="svg-icon svg-icon-primary svg-icon-3x ms-n1">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
											<path d="M21 9V11C21 11.6 20.6 12 20 12H14V8H20C20.6 8 21 8.4 21 9ZM10 8H4C3.4 8 3 8.4 3 9V11C3 11.6 3.4 12 4 12H10V8Z" fill="currentColor"></path>
											<path d="M15 2C13.3 2 12 3.3 12 5V8H15C16.7 8 18 6.7 18 5C18 3.3 16.7 2 15 2Z" fill="currentColor"></path>
											<path opacity="0.3" d="M9 2C10.7 2 12 3.3 12 5V8H9C7.3 8 6 6.7 6 5C6 3.3 7.3 2 9 2ZM4 12V21C4 21.6 4.4 22 5 22H10V12H4ZM20 12V21C20 21.6 19.6 22 19 22H14V12H20Z" fill="currentColor"></path>
										</svg>
									</span>
									<!--end::Svg Icon-->
									<div class="text-gray-900 fw-bolder fs-2 mb-2 mt-5"><?= count($events) ?></div>
									<div class="fw-bold text-gray-400">Total Events</div>
								</div>
								<!--end::Body-->
							</a>
							<!--end::Statistics Widget 5-->
						</div>
						<div class="col-xl-3">
							<!--begin::Statistics Widget 5-->
							<a href="javascript:;" class="card bg-dark hoverable card-xl-stretch mb-xl-8">
								<!--begin::Body-->
								<div class="card-body">
									<!--begin::Svg Icon | path: icons/duotune/ecommerce/ecm008.svg-->
									<span class="svg-icon svg-icon-gray-100 svg-icon-3x ms-n1">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
											<path d="M21 9V11C21 11.6 20.6 12 20 12H14V8H20C20.6 8 21 8.4 21 9ZM10 8H4C3.4 8 3 8.4 3 9V11C3 11.6 3.4 12 4 12H10V8Z" fill="currentColor"></path>
											<path d="M15 2C13.3 2 12 3.3 12 5V8H15C16.7 8 18 6.7 18 5C18 3.3 16.7 2 15 2Z" fill="currentColor"></path>
											<path opacity="0.3" d="M9 2C10.7 2 12 3.3 12 5V8H9C7.3 8 6 6.7 6 5C6 3.3 7.3 2 9 2ZM4 12V21C4 21.6 4.4 22 5 22H10V12H4ZM20 12V21C20 21.6 19.6 22 19 22H14V12H20Z" fill="currentColor"></path>
										</svg>
									</span>
									<!--end::Svg Icon-->
									<div class="text-gray-100 fw-bolder fs-2 mb-2 mt-5"><?= count($inactive)  ?></div>
									<div class="fw-bold text-gray-100">Total Inactive</div>
								</div>
								<!--end::Body-->
							</a>
							<!--end::Statistics Widget 5-->
						</div>
						<div class="col-xl-3">
							<!--begin::Statistics Widget 5-->
							<a href="javascript:;" class="card bg-success hoverable card-xl-stretch mb-xl-8">
								<!--begin::Body-->
								<div class="card-body">
									<!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
									<span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
											<path d="M21 9V11C21 11.6 20.6 12 20 12H14V8H20C20.6 8 21 8.4 21 9ZM10 8H4C3.4 8 3 8.4 3 9V11C3 11.6 3.4 12 4 12H10V8Z" fill="currentColor"></path>
											<path d="M15 2C13.3 2 12 3.3 12 5V8H15C16.7 8 18 6.7 18 5C18 3.3 16.7 2 15 2Z" fill="currentColor"></path>
											<path opacity="0.3" d="M9 2C10.7 2 12 3.3 12 5V8H9C7.3 8 6 6.7 6 5C6 3.3 7.3 2 9 2ZM4 12V21C4 21.6 4.4 22 5 22H10V12H4ZM20 12V21C20 21.6 19.6 22 19 22H14V12H20Z" fill="currentColor"></path>
										</svg>
									</span>
									<!--end::Svg Icon-->
									<div class="text-white fw-bolder fs-2 mb-2 mt-5"><?= count($active) ?></div>
									<div class="fw-bold text-white">Total Active</div>
								</div>
								<!--end::Body-->
							</a>
							<!--end::Statistics Widget 5-->
						</div>
					
					</div>
							</div>
							<!--end::Container-->
						</div>
						<!--end::Post-->
<?= $this->endSection() ?>