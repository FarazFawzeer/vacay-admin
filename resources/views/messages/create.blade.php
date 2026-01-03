@extends('layouts.vertical', ['subtitle' => 'Send Notification'])

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Send Email Notification</h5>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('admin.messages.send') }}" method="POST">
                @csrf

                {{-- ================= RECIPIENT MODE ================= --}}
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Send To</label>
                        <select class="form-select" name="send_mode" id="sendMode">
                            <option value="all">All Customers</option>
                            <option value="filter">Filter Customers</option>
                            <option value="selected">Selected Customers</option>
                        </select>
                    </div>
                </div>

                {{-- ================= FILTER SECTION ================= --}}
                <div class="row d-none" id="filterSection">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Customer Type</label>
                        <select class="form-select" id="filterType" name="type">
                            <option value="">-- All Types --</option>
                            @foreach ($types as $type)
                                <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Customer Sub Type</label>
                        <select class="form-select" id="filterSubType" name="sub_type">
                            <option value="">-- All Sub Types --</option>
                            @foreach ($subTypes as $subType)
                                <option value="{{ $subType }}">{{ ucfirst($subType) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Select Customers</label>
                        <select id="filteredCustomers" name="customer_ids[]" multiple></select>
                    </div>

                </div>

                {{-- ================= SELECTED CUSTOMERS ================= --}}
                <div class="row d-none" id="selectedCustomersSection">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Select Customers</label>
                        <select id="selectedCustomers" name="customer_ids[]" multiple></select>
                    </div>
                </div>

                <hr>

                {{-- ================= EMAIL COMMON FIELDS ================= --}}
                <h6 class="mb-3">Email Content</h6>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email Subject</label>
                        <input type="text" name="subject" class="form-control"
                            placeholder="Example: Booking Confirmation / Promotion" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Greeting Line</label>
                        <input type="text" name="greeting" class="form-control"
                            placeholder="Example: Dear Valued Customer," value="Dear Valued Customer,">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Message</label>
                    <textarea name="message" rows="6" class="form-control" required
                        placeholder="Write your main email content here..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Footer</label>
                    <textarea name="footer" rows="3" class="form-control" placeholder="Best regards, Vacay Guider Team">Best regards,
VacayGuider Team</textarea>
                </div>


                {{-- ================= ACTION ================= --}}
                <div class="text-end">
                    <button type="submit" class="btn btn-primary" id="sendBtn">
                        <span id="sendBtnText">
                            <iconify-icon icon="mdi:send-outline"></iconify-icon>
                            Send Email
                        </span>

                        <span id="sendBtnLoading" class="d-none">
                            <span class="spinner-border spinner-border-sm me-2"></span>
                            Sending...
                        </span>
                    </button>

                </div>
                <div id="loadingOverlay" class="position-fixed top-0 start-0 w-100 h-100 d-none"
                    style="background: rgba(255,255,255,0.7); z-index: 1050;">
                    <div class="d-flex justify-content-center align-items-center h-100">
                        <div class="text-center">
                            <div class="spinner-border text-primary mb-3"></div>
                            <div>Sending emails, please wait...</div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    {{-- Include Choices.js --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <script>
        (function() {

            const sendMode = document.getElementById('sendMode');
            const filterSection = document.getElementById('filterSection');
            const selectedCustomersSection = document.getElementById('selectedCustomersSection');

            const filterType = document.getElementById('filterType');
            const filterSubType = document.getElementById('filterSubType');
            const filteredCustomersSelect = document.getElementById('filteredCustomers');
            const selectedCustomersSelect = document.getElementById('selectedCustomers');

            // Initialize Choices.js
            const filteredChoices = new Choices(filteredCustomersSelect, {
                removeItemButton: true,
                searchResultLimit: 100,
                placeholderValue: 'Select customers...',
                searchPlaceholderValue: 'Type to search...',
            });

            const selectedChoices = new Choices(selectedCustomersSelect, {
                removeItemButton: true,
                searchResultLimit: 100,
                placeholderValue: 'Select customers...',
                searchPlaceholderValue: 'Type to search...',
            });

            function handleSendMode() {
                filterSection.classList.add('d-none');
                selectedCustomersSection.classList.add('d-none');

                if (sendMode.value === 'filter') {
                    filterSection.classList.remove('d-none');
                    loadFilteredCustomers();
                }

                if (sendMode.value === 'selected') {
                    selectedCustomersSection.classList.remove('d-none');
                    loadAllCustomers(); // load all customers into searchable select
                }
            }

            sendMode.addEventListener('change', handleSendMode);
            handleSendMode();

            filterType.addEventListener('change', loadFilteredCustomers);
            filterSubType.addEventListener('change', loadFilteredCustomers);

            // Load filtered customers via AJAX
            function loadFilteredCustomers() {
                const type = filterType.value;
                const subType = filterSubType.value;

                filteredChoices.clearStore();
                filteredChoices.setChoices([{
                    value: '',
                    label: 'Loading...',
                    disabled: true
                }], 'value', 'label', true);

                fetch(`{{ route('admin.messages.filterCustomers') }}?type=${type}&sub_type=${subType}`)
                    .then(res => res.json())
                    .then(data => {
                        filteredChoices.clearStore();
                        if (!data.length) {
                            filteredChoices.setChoices([{
                                value: '',
                                label: 'No customers found',
                                disabled: true
                            }], 'value', 'label', true);
                            return;
                        }

                        const options = data.map(c => ({
                            value: c.id,
                            label: `${c.name} (${c.email})`
                        }));
                        filteredChoices.setChoices(options, 'value', 'label', true);
                    })
                    .catch(() => {
                        filteredChoices.clearStore();
                        filteredChoices.setChoices([{
                            value: '',
                            label: 'Error loading customers',
                            disabled: true
                        }], 'value', 'label', true);
                    });
            }

            // Load all customers for "Selected Customers"
            function loadAllCustomers() {
                selectedChoices.clearStore();
                const allCustomers = @json($customers);
                const options = allCustomers.map(c => ({
                    value: c.id,
                    label: `${c.name} (${c.email})`
                }));
                selectedChoices.setChoices(options, 'value', 'label', true);
            }

        })();

        const form = document.querySelector('form');
        const sendBtn = document.getElementById('sendBtn');
        const sendBtnText = document.getElementById('sendBtnText');
        const sendBtnLoading = document.getElementById('sendBtnLoading');
        const loadingOverlay = document.getElementById('loadingOverlay');

        form.addEventListener('submit', function() {
            // Disable button
            sendBtn.disabled = true;

            // Switch text
            sendBtnText.classList.add('d-none');
            sendBtnLoading.classList.remove('d-none');

            // Show overlay
            loadingOverlay.classList.remove('d-none');
        });
    </script>
@endsection
