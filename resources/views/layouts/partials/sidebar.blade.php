<div class="app-sidebar">
    <!-- Sidebar Logo -->
    <div class="logo-box">
        <a href="{{ route('any', 'index') }}" class="logo-dark">
            <img src="/images/vacayguider.png" class="logo-sm" alt="logo sm">
            <img src="/images/vacayguider.png" class="logo-lg" alt="logo dark" style="width: 150px; height: 75px;">
        </a>

        <a href="{{ route('any', 'index') }}" class="logo-light">
            <img src="/images/vacayguider.png" class="logo-sm" alt="logo sm">
            <img src="/images/vacayguider.png" class="logo-lg" alt="logo light" style="width: 150px; height: 75px;">
        </a>
    </div>

    <div class="scrollbar" data-simplebar>

        <ul class="navbar-nav" id="navbar-nav">

            <li class="menu-title">Menu...</li>

            {{-- <li class="nav-item">
                    <a class="nav-link" href="{{ route('any', 'index') }}">
                         <span class="nav-icon">
                                      <iconify-icon icon="solar:user-circle-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Admin </span>
                      

                         
                    </a>
               </li> --}}

            {{-- Admin --}}
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
                            <a class="sub-nav-link" href="{{ route('second', ['admin', 'create']) }}">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.users.index') }}">View </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Customer --}}
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
                            <a class="sub-nav-link" href="{{ route('second', ['customer', 'create']) }}">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.customers.index') }}">View </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Agent --}}
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarAgent" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarAgent">
                    <span class="nav-icon">
                     <iconify-icon icon="mdi:account-tie-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Agent</span>
                </a>
                <div class="collapse" id="sidebarAgent">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.agents.create') }}">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.agents.index') }}">View </a>
                        </li>
                    </ul>
                </div>
            </li>
            {{-- Enquiry --}}
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
                            <a class="sub-nav-link" href="{{ route('admin.enquiry.tours') }}">
                                <iconify-icon icon="solar:circle-outline" style="margin-right:5px;"></iconify-icon>
                                Tours
                            </a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.enquiry.customTour') }}">
                                <iconify-icon icon="solar:circle-outline" style="margin-right:5px;"></iconify-icon>
                                Custom Tour
                            </a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.enquiry.rentVehicle') }}">
                                <iconify-icon icon="solar:circle-outline" style="margin-right:5px;"></iconify-icon>
                                Rent Vehicle
                            </a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.enquiry.transport') }}">
                                <iconify-icon icon="solar:circle-outline" style="margin-right:5px;"></iconify-icon>
                                Transport
                            </a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.enquiry.airTicket') }}">
                                <iconify-icon icon="solar:circle-outline" style="margin-right:5px;"></iconify-icon>
                                Air Ticket
                            </a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.enquiry.drivingPermit') }}">
                                <iconify-icon icon="solar:circle-outline" style="margin-right:5px;"></iconify-icon>
                                Driving Permit Request
                            </a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.enquiry.chatbot') }}">
                                <iconify-icon icon="solar:circle-outline" style="margin-right:5px;"></iconify-icon>
                                Chatbot
                            </a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.enquiry.contactUs') }}">
                                <iconify-icon icon="solar:circle-outline" style="margin-right:5px;"></iconify-icon>
                                Contact Us
                            </a>
                        </li>
                    </ul>

                </div>
            </li>

            {{-- Add Details --}}
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
                            <a class="sub-nav-link" href="{{ route('admin.destinations.create') }}">Tour
                                Destination</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.destination-highlights.index') }}">Tour
                                Highlights</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.hotels.index') }}">Hotels</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.vehicles.index') }}">Vehicles</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.visa.index') }}">Visa</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.inclusions.index') }}">Inclusions</a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Tour Package --}}
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
                            <a class="sub-nav-link" href="{{ route('admin.packages.create') }}">Create Tour</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.packages.index') }}">View Tour</a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Bookings --}}
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
                            <a class="sub-nav-link" href="{{ route('admin.tour-bookings.index') }}">Tours</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.rent-vehicle-bookings.index') }}">Rent
                                Vehicles</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.vehicle-bookings.index') }}">Transport
                                Solutions</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.visa-bookings.index') }}">Visa</a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Blog Post --}}
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
                            <a class="sub-nav-link" href="{{ route('admin.blogs.create') }}">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.blogs.index') }}">View</a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Testimonial --}}
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
                            <a class="sub-nav-link" href="{{ route('admin.testimonials.create') }}">Create</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('admin.testimonials.index') }}">View</a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Profile --}}
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.profile.edit') }}">
                    <span class="nav-icon">
                        <iconify-icon icon="mdi:account-circle-outline"></iconify-icon>
                    </span>
                    <span class="nav-text"> Profile </span>
                </a>
            </li>


            {{-- <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarAuthentication" data-bs-toggle="collapse" role="button"
                         aria-expanded="false" aria-controls="sidebarAuthentication">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:user-circle-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Authentication </span>
                    </a>
                    <div class="collapse" id="sidebarAuthentication">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['auth','signin']) }}">Sign In</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['auth','signup']) }}">Sign Up</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['auth','password']) }}">Reset Password</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['auth','lock-screen']) }}">Lock Screen</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarError" data-bs-toggle="collapse" role="button"
                         aria-expanded="false" aria-controls="sidebarError">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:danger-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Error Pages</span>
                    </a>
                    <div class="collapse" id="sidebarError">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['pages','404']) }}">Pages 404</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['pages','404-alt']) }}">Pages 404 Alt</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="menu-title">UI Kit...</li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarBaseUI" data-bs-toggle="collapse" role="button"
                         aria-expanded="false" aria-controls="sidebarBaseUI">
                         <span class="nav-icon"><iconify-icon icon="solar:leaf-outline"></iconify-icon></span>
                         <span class="nav-text"> Base UI </span>
                    </a>
                    <div class="collapse" id="sidebarBaseUI">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','accordion']) }}">Accordion</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','alerts']) }}">Alerts</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','avatar']) }}">Avatar</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','badge']) }}">Badge</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','breadcrumb']) }}">Breadcrumb</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','buttons']) }}">Buttons</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','card']) }}">Card</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','carousel']) }}">Carousel</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','collapse']) }}">Collapse</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','dropdown']) }}">Dropdown</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','list-group']) }}">List Group</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','modal']) }}">Modal</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','tabs']) }}">Tabs</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','offcanvas']) }}">Offcanvas</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','pagination']) }}">Pagination</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','placeholders']) }}">Placeholders</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','popovers']) }}">Popovers</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','progress']) }}">Progress</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','scrollspy']) }}">Scrollspy</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','spinners']) }}">Spinners</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','toasts']) }}">Toasts</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['ui','tooltips']) }}">Tooltips</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link" href="{{ route ('second' , ['pages','charts']) }}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:chart-square-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Apex Charts </span>
                    </a>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarForms" data-bs-toggle="collapse" role="button"
                         aria-expanded="false" aria-controls="sidebarForms">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:box-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Forms </span>
                    </a>
                    <div class="collapse" id="sidebarForms">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['forms','basic']) }}">Basic Elements</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['forms','flatpicker']) }}">Flatpicker</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['forms','validation']) }}">Validation</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['forms','fileuploads']) }}">File Upload</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['forms','editors']) }}">Editors</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarTables" data-bs-toggle="collapse" role="button"
                         aria-expanded="false" aria-controls="sidebarTables">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:checklist-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Tables </span>
                    </a>
                    <div class="collapse" id="sidebarTables">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['tables','basic']) }}">Basic Tables</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['tables','gridjs']) }}">Grid Js</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarIcons" data-bs-toggle="collapse" role="button"
                         aria-expanded="false" aria-controls="sidebarIcons">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:crown-star-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Icons </span>
                    </a>
                    <div class="collapse" id="sidebarIcons">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['icons','boxicons']) }}">Boxicons</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['icons','solar']) }}">Solar Icons</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarMaps" data-bs-toggle="collapse" role="button"
                         aria-expanded="false" aria-controls="sidebarMaps">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:map-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Maps </span>
                    </a>
                    <div class="collapse" id="sidebarMaps">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['maps','google']) }}">Google Maps</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['maps','vector']) }}">Vector Maps</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="menu-title">Other</li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarLayouts" data-bs-toggle="collapse" role="button"
                         aria-expanded="false" aria-controls="sidebarLayouts">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:window-frame-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Layouts </span>
                    </a>
                    <div class="collapse" id="sidebarLayouts">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['layouts-demo','dark-sidenav']) }}" target="_blank">Dark
                                        Sidenav</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['layouts-demo','dark-topnav']) }}" target="_blank">Dark
                                        Topnav</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['layouts-demo','small-sidenav']) }}" target="_blank">Small
                                        Sidenav</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route ('second' , ['layouts-demo','hidden-sidenav']) }}" target="_blank">Hidden
                                        Sidenav</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" target="_blank" href="{{ route ('second' , ['layouts-demo','dark']) }}">
                                        <span class="nav-text">Dark Mode</span>
                                        <span class="badge badge-soft-danger badge-pill text-end">Hot</span>
                                   </a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarMultiLevelDemo" data-bs-toggle="collapse" role="button"
                         aria-expanded="false" aria-controls="sidebarMultiLevelDemo">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:share-circle-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Menu Item </span>
                    </a>
                    <div class="collapse" id="sidebarMultiLevelDemo">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="javascript:void(0);">Menu Item 1</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link  menu-arrow" href="#sidebarItemDemoSubItem"
                                        data-bs-toggle="collapse" role="button" aria-expanded="false"
                                        aria-controls="sidebarItemDemoSubItem">
                                        <span> Menu Item 2 </span>
                                   </a>
                                   <div class="collapse" id="sidebarItemDemoSubItem">
                                        <ul class="nav sub-navbar-nav">
                                             <li class="sub-nav-item">
                                                  <a class="sub-nav-link" href="javascript:void(0);">Menu Sub item</a>
                                             </li>
                                        </ul>
                                   </div>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link disabled" href="javascript:void(0);">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:library-outline"></iconify-icon>
                         </span>
                         <span class="nav-text"> Disable Item </span>
                    </a>
               </li> --}}
        </ul>
    </div>
</div>
