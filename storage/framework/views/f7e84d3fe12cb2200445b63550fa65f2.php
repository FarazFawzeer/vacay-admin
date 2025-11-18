<div class="app-sidebar">
    <!-- Sidebar Logo -->
    <div class="logo-box">
        <a href="<?php echo e(route('any', 'index')); ?>" class="logo-dark">
            <img src="/images/vacayguider.png" class="logo-sm" alt="logo sm">
            <img src="/images/vacayguider.png" class="logo-lg" alt="logo dark" style="width: 150px; height: 75px;">
        </a>

        <a href="<?php echo e(route('any', 'index')); ?>" class="logo-light">
            <img src="/images/vacayguider.png" class="logo-sm" alt="logo sm">
            <img src="/images/vacayguider.png" class="logo-lg" alt="logo light" style="width: 150px; height: 75px;">
        </a>
    </div>

    <div class="scrollbar" data-simplebar>

        <ul class="navbar-nav" id="navbar-nav">

            <li class="menu-title">Menu...</li>

            

            
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarAdmin" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarAdmin">
                    <span class="nav-icon">
                        <iconify-icon icon="mdi:account-cog-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Admin</span>
                </a>
                <div class="collapse" id="sidebarAdmin">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('second', ['admin', 'create'])); ?>">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.users.index')); ?>">View </a>
                        </li>
                    </ul>
                </div>
            </li>

            
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarCustomer" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarCustomer">
                    <span class="nav-icon">
                        <iconify-icon icon="mdi:account-multiple-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Customer</span>
                </a>
                <div class="collapse" id="sidebarCustomer">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('second', ['customer', 'create'])); ?>">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.customers.index')); ?>">View </a>
                        </li>
                    </ul>
                </div>
            </li>

            
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarEnquiry" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarEnquiry">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:question-circle-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Enquiry</span>
                </a>
                <div class="collapse" id="sidebarEnquiry">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.enquiry.tours')); ?>">
                                <iconify-icon icon="solar:circle-outline" style="margin-right:5px;"></iconify-icon>
                                Tours
                            </a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.enquiry.customTour')); ?>">
                                <iconify-icon icon="solar:circle-outline" style="margin-right:5px;"></iconify-icon>
                                Custom Tour
                            </a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.enquiry.rentVehicle')); ?>">
                                <iconify-icon icon="solar:circle-outline" style="margin-right:5px;"></iconify-icon>
                                Rent Vehicle
                            </a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.enquiry.transport')); ?>">
                                <iconify-icon icon="solar:circle-outline" style="margin-right:5px;"></iconify-icon>
                                Transport
                            </a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.enquiry.airTicket')); ?>">
                                <iconify-icon icon="solar:circle-outline" style="margin-right:5px;"></iconify-icon>
                                Air Ticket Inquiries
                            </a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.enquiry.drivingPermit')); ?>">
                                <iconify-icon icon="solar:circle-outline" style="margin-right:5px;"></iconify-icon>
                                Driving Permit Request
                            </a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.enquiry.contactUs')); ?>">
                                <iconify-icon icon="solar:circle-outline" style="margin-right:5px;"></iconify-icon>
                                Contact Us
                            </a>
                        </li>
                    </ul>

                </div>
            </li>

            
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarAddDeatails" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarAddDeatails">
                    <span class="nav-icon">
                        <iconify-icon icon="mdi:folder-plus-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Add Details</span>
                </a>
                <div class="collapse" id="sidebarAddDeatails">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.destinations.create')); ?>">Tour
                                Destination</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.destination-highlights.index')); ?>">Tour
                                Highlights</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.hotels.index')); ?>">Hotels</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.vehicles.index')); ?>">Vehicles</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.visa.index')); ?>">Visa</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.inclusions.index')); ?>">Inclusions</a>
                        </li>
                    </ul>
                </div>
            </li>

            
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarTourPackage" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarTourPackage">
                    <span class="nav-icon">
                        <iconify-icon icon="mdi:package-variant-closed"></iconify-icon>
                    </span>
                    <span class="nav-text"> Tour Package</span>
                </a>
                <div class="collapse" id="sidebarTourPackage">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.packages.create')); ?>">Create Tour</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.packages.index')); ?>">View Tour</a>
                        </li>
                    </ul>
                </div>
            </li>

            
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarbookings" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarbookings">
                    <span class="nav-icon">
                        <iconify-icon icon="mdi:calendar-check-outline"></iconify-icon>
                    </span>
                    <span class="nav-text">Bookings</span>
                </a>
                <div class="collapse" id="sidebarbookings">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.tour-bookings.index')); ?>">Tours</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.rent-vehicle-bookings.index')); ?>">Rent
                                Vehicles</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.vehicle-bookings.index')); ?>">Transport
                                Solutions</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.visa-bookings.index')); ?>">Visa</a>
                        </li>
                    </ul>
                </div>
            </li>

            
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#blogpost" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="blogpost">
                    <span class="nav-icon">
                        <iconify-icon icon="mdi:file-document-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Blog Post</span>
                </a>
                <div class="collapse" id="blogpost">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.blogs.create')); ?>">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.blogs.index')); ?>">View</a>
                        </li>
                    </ul>
                </div>
            </li>

            
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#testimonial" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="testimonial">
                    <span class="nav-icon">
                        <iconify-icon icon="mdi:comment-account-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Testimonial</span>
                </a>
                <div class="collapse" id="testimonial">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.testimonials.create')); ?>">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?php echo e(route('admin.testimonials.index')); ?>">View</a>
                        </li>
                    </ul>
                </div>
            </li>

            
            <li class="nav-item">
                <a class="nav-link" href="<?php echo e(route('admin.profile.edit')); ?>">
                    <span class="nav-icon">
                        <iconify-icon icon="mdi:account-circle-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Profile </span>
                </a>
            </li>


            
        </ul>
    </div>
</div>
<?php /**PATH F:\Personal Projects\Vacay Guider\vacay-admin\resources\views/layouts/partials/sidebar.blade.php ENDPATH**/ ?>