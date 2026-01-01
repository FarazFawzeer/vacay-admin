@extends('layouts.vertical', ['subtitle' => 'Edit Visa Booking'])

@section('content')
    @include('layouts.partials.page-title', [
        'title' => 'Visa Booking',
        'subtitle' => 'Edit',
    ])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Visa Booking</h5>
        </div>

        <div class="card-body">
            <form id="visaBookingForm" action="{{ route('admin.visa-bookings.update', $booking->id) }}" method="POST">
                @csrf
                @method('PUT')
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="row">

                    {{-- Passport --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Passport</label>
                        <select name="passport_id" class="form-select" required>
                            @foreach ($passports as $passport)
                                <option value="{{ $passport->id }}" @selected($booking->passport_id == $passport->id)>
                                    {{ $passport->passport_number }} -
                                    {{ $passport->first_name }} {{ $passport->second_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- From → To --}}
                    {{-- From → To --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">From → To Country</label>
                        <select id="country_pair" class="form-select" required>
                            <option value="">Select Route</option>
                            @foreach ($visas as $visa)
                                <option value="{{ $visa->from_country }}|{{ $visa->to_country }}"
                                    {{ $booking->visa->from_country == $visa->from_country && $booking->visa->to_country == $visa->to_country ? 'selected' : '' }}>
                                    {{ $visa->from_country }} → {{ $visa->to_country }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Visa Type --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Visa Type</label>
                        <select name="visa_id" id="visa_id" class="form-select" required>
                            <option value="">Select Visa Type</option>
                        </select>
                    </div>

                    {{-- Visa Category --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Visa Category</label>
                        <select name="visa_category_id" id="visa_category_id" class="form-select" required>
                            <option value="">Select Category</option>
                        </select>
                    </div>




                    {{-- Status --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Booking Status</label>
                        <select name="status" class="form-select">
                            @foreach (['Quotation', 'Accepted', 'Invoiced', 'Partially Paid', 'Paid', 'Cancelled'] as $st)
                                <option value="{{ $st }}" @selected($booking->status == $st)>
                                    {{ ucwords(str_replace('_', ' ', $st)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Payment --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Payment Status</label>
                        <select name="payment_status" class="form-select">
                            @foreach (['unpaid', 'partial', 'paid'] as $ps)
                                <option value="{{ $ps }}" @selected($booking->payment_status == $ps)>
                                    {{ ucfirst($ps) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Agent --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Agent</label>
                        <select name="agent_id" id="agent_id" class="form-select" required>
                            <option value="">Select Agent</option>
                            @if ($booking->agent)
                                <option value="{{ $booking->agent->id }}" selected>
                                    {{ $booking->agent->name }} ({{ $booking->agent->company_name ?? 'N/A' }})
                                </option>
                            @endif
                        </select>
                    </div>

                                       <div class="col-md-3">
                        <label for="published_at" class="form-label">Published Date</label>
                        <input type="date" name="published_at" id="published_at" class="form-control"
                            value="{{ $booking->published_at?->format('Y-m-d') }}">
                    </div>
                    
                    {{-- Note --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Note</label>
                        <textarea name="note" id="note" class="form-control" rows="3"
                            placeholder="Add any special notes or remarks (optional)">{{ $booking->note }}</textarea>
                    </div>

                    {{-- Price & Payment Details --}}
                    <div class="col-md-6 mb-3">
                        <div class="card border-secondary">
                            <div class="card-header bg-light">
                                <strong>Price & Payment Details</strong>
                            </div>
                            <div class="card-body">

                                {{-- Currency --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Currency</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="currency" name="currency" class="form-control"
                                            value="{{ $booking->currency }}" readonly>
                                    </div>
                                </div>

                                {{-- Base Price --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Base Price</label>
                                    <div class="col-sm-10">
                                        <input type="number" id="base_price" name="base_price" class="form-control"
                                            value="{{ $booking->base_price }}" readonly>
                                    </div>
                                </div>

                                {{-- Additional Price --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Additional Price</label>
                                    <div class="col-sm-10">
                                        <input type="number" id="additional_price" name="additional_price"
                                            class="form-control" value="{{ $booking->additional_price }}">
                                    </div>
                                </div>

                                {{-- Discount --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Discount</label>
                                    <div class="col-sm-10">
                                        <input type="number" id="discount" name="discount" class="form-control"
                                            value="{{ $booking->discount }}">
                                    </div>
                                </div>

                                <hr>

                                {{-- Total --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Total</label>
                                    <div class="col-sm-10">
                                        <input type="number" id="total_amount" name="total_amount" class="form-control"
                                            value="{{ $booking->total_amount }}" readonly>
                                    </div>
                                </div>

                                {{-- Advance Paid --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Advance Paid</label>
                                    <div class="col-sm-10">
                                        <input type="number" id="advanced_paid" name="advanced_paid"
                                            class="form-control" value="{{ $booking->advanced_paid }}">
                                    </div>
                                </div>

                                {{-- Balance --}}
                                <div class="mb-2 row">
                                    <label class="col-sm-2 col-form-label">Balance</label>
                                    <div class="col-sm-10">
                                        <input type="number" id="balance" name="balance" class="form-control"
                                            value="{{ $booking->balance }}" readonly>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <div class="text-end mt-3">
                    <a href="{{ route('admin.visa-bookings.index') }}" class="btn btn-warning"
                        style="width:130px;">Back</a>

                    <button type="button" class="btn btn-secondary me-2" onclick="previewBooking()"
                        style="width:130px;">
                        Preview
                    </button>

                    <button type="submit" class="btn btn-primary" style="width:130px;">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Preview Modal --}}
    <div class="modal fade" id="bookingPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header text-white bg-primary">
                    <h5 class="modal-title">Visa Booking Preview</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="bookingPreviewBody"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="width:130px;">Close</button>
                    <button type="button" class="btn btn-success" onclick="generatePdf()" style="width:130px;">Generate
                        PDF</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            function calculate() {
                const basePrice = parseFloat(document.getElementById('base_price').value) || 0;
                const additional = parseFloat(document.getElementById('additional_price').value) || 0;
                const discount = parseFloat(document.getElementById('discount').value) || 0;
                const advanced = parseFloat(document.getElementById('advanced_paid').value) || 0;

                const total = basePrice + additional - discount;
                const balance = total - advanced;

                document.getElementById('total_amount').value = total.toFixed(2);
                document.getElementById('balance').value = balance.toFixed(2);
            }

            // Recalculate on input changes
            document.getElementById('additional_price').addEventListener('input', calculate);
            document.getElementById('discount').addEventListener('input', calculate);
            document.getElementById('advanced_paid').addEventListener('input', calculate);

            // Recalculate when category changes (already in your code)
            const categorySelect = document.getElementById('visa_category_id');
            categorySelect.addEventListener('change', function() {
                const opt = this.options[this.selectedIndex];
                document.getElementById('base_price').value = opt.dataset.price || 0;
                document.getElementById('currency').value = opt.dataset.currency || '';
                calculate();
            });

            // Initial calculation on page load
            calculate();
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {


            function loadAgents(selectedAgentId = null) {
                if (!countryPair.value) return;

                const [from, to] = countryPair.value.split('|');

                fetch(`/admin/ajax/agents/by-country?from_country=${from}&to_country=${to}`)
                    .then(res => res.json())
                    .then(data => {
                        const agentSelect = document.getElementById('agent_id');
                        agentSelect.innerHTML = '<option value="">Select Agent</option>';

                        if (!data.length) {
                            agentSelect.innerHTML += `<option disabled>No agents available</option>`;
                            return;
                        }

                        data.forEach(agent => {
                            const selected = selectedAgentId && agent.id == selectedAgentId ?
                                'selected' : '';
                            agentSelect.innerHTML += `
                    <option value="${agent.id}" ${selected}>
                        ${agent.name} (${agent.company_name ?? 'N/A'})
                    </option>
                `;
                        });
                    });
            }

            const bookingVisaId = {{ $booking->visa_id }};
            const bookingCategoryId = {{ $booking->visa_category_id }};

            const countryPair = document.getElementById('country_pair');
            const visaSelect = document.getElementById('visa_id');
            const categorySelect = document.getElementById('visa_category_id');

            // Load visa types when route changes
            function loadVisas(selectSaved = false) {
                if (!countryPair.value) return;

                const [from, to] = countryPair.value.split('|');

                fetch(`/admin/ajax/visas/by-country?from_country=${from}&to_country=${to}`)
                    .then(res => res.json())
                    .then(visas => {
                        visaSelect.innerHTML = '<option value="">Select Visa Type</option>';

                        visas.forEach(v => {
                            visaSelect.innerHTML += `
                        <option value="${v.id}" ${selectSaved && v.id == bookingVisaId ? 'selected' : ''}>
                            ${v.visa_type}
                        </option>`;
                        });

                        if (selectSaved) {
                            loadCategories(true);
                        }
                    });
            }

            // Load categories
            function loadCategories(selectSaved = false) {
                if (!visaSelect.value) return;

                fetch(`/admin/ajax/visa/${visaSelect.value}/categories`)
                    .then(res => res.json())
                    .then(categories => {
                        categorySelect.innerHTML = '<option value="">Select Category</option>';

                        categories.forEach(c => {
                            categorySelect.innerHTML += `
                        <option value="${c.id}"
                            data-price="${c.price}"
                            data-currency="${c.currency}"
                            ${selectSaved && c.id == bookingCategoryId ? 'selected' : ''}>
                            ${c.visa_type} (${c.processing_time})
                        </option>`;
                        });

                        if (selectSaved) {
                            categorySelect.dispatchEvent(new Event('change'));
                        }
                    });
            }

            // EVENTS
            countryPair.addEventListener('change', () => {
                loadVisas();
                loadAgents(); // Load agents whenever the route changes
            });

            // INIT LOAD FOR EDIT
            loadVisas(true);
            loadAgents({{ $booking->agent_id ?? 'null' }}); // Load selected agent on edit
            visaSelect.addEventListener('change', () => loadCategories());

            categorySelect.addEventListener('change', function() {
                const opt = this.options[this.selectedIndex];
                document.getElementById('base_price').value = opt.dataset.price || 0;
                document.getElementById('currency').value = opt.dataset.currency || '';
                calculate();
            });

            // INIT LOAD FOR EDIT
            loadVisas(true);
        });

        // Preview Booking
        function previewBooking() {
            const passportSelect = document.querySelector('select[name="passport_id"]');
            const countryPairSelect = document.getElementById('country_pair');
            const visaTypeSelect = document.getElementById('visa_id');
            const visaCategorySelect = document.getElementById('visa_category_id');

            const passportOption = passportSelect.options[passportSelect.selectedIndex];
            const countryPair = countryPairSelect.value.split('|');
            const visaTypeOption = visaTypeSelect.options[visaTypeSelect.selectedIndex];
            const visaCategoryOption = visaCategorySelect.options[visaCategorySelect.selectedIndex];

            const passportNumber = passportOption ? passportOption.text : '-';
            const fromCountry = countryPair[0] || '-';
            const toCountry = countryPair[1] || '-';
            const visaType = visaTypeOption ? visaTypeOption.text : '-';
            const visaCategory = visaCategoryOption ? visaCategoryOption.text : '-';
            const note = document.getElementById('note')?.value || '';
            const basePrice = parseFloat(document.getElementById('base_price').value) || 0;
            const additionalPrice = parseFloat(document.getElementById('additional_price').value) || 0;
            const discount = parseFloat(document.getElementById('discount').value) || 0;
            const advancedPaid = parseFloat(document.getElementById('advanced_paid').value) || 0;
            const total = basePrice + additionalPrice - discount;
            const balance = total - advancedPaid;
            const currency = document.getElementById('currency').value || 'LKR';
            const status = document.querySelector('select[name="status"]').value || '-';
            const paymentStatus = document.querySelector('select[name="payment_status"]').value || '-';
            const bookingDate = new Date().toLocaleDateString('en-GB');

            const html = `
<div style="max-width:800px;margin:0 auto;font-family:'Helvetica Neue',Arial,sans-serif;color:#333;background:#fff;padding:25px;">
    <table style="width:100%;border-bottom:2px solid #333;margin-bottom:30px;">
        <tr>
            <td>
                <img src="{{ asset('images/vacayguider.png') }}" style="height:80px;">
                <div style="font-size:12px;color:#666;margin-top:10px;line-height:1.4;">
                    <strong>Vacay Guider (Pvt) Ltd.</strong><br>
                    Negombo, Sri Lanka<br>
                    +94 114 272 372 | info@vacayguider.com
                </div>
            </td>
            <td style="text-align:right;">
                <h1 style="margin:0;font-size:24px;font-weight:300;letter-spacing:2px;">${status}</h1>
                <table style="margin-left:auto;margin-top:10px;font-size:13px;">
                    <tr><td style="color:#888;padding:2px 10px;">Booking Date</td><td>${bookingDate}</td></tr>
                    <tr><td style="color:#888;padding:2px 10px;">Payment Status</td><td>${paymentStatus}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <table style="width:100%;margin-bottom:35px;font-size:13px;">
        <tr>
            <td style="width:50%;vertical-align:top;">
                <h4 style="font-size:11px;color:#888;text-transform:uppercase;margin-bottom:8px;">Passport Information</h4>
                <div style="font-size:15px;font-weight:bold;">${passportNumber}</div>
            </td>
            <td style="width:50%;vertical-align:top;border-left:1px solid #eee;padding-left:25px;">
                <h4 style="font-size:11px;color:#888;text-transform:uppercase;margin-bottom:8px;">Visa Details</h4>
                <div><strong>Route:</strong> ${fromCountry} - ${toCountry}</div>
                <div><strong>Visa Type:</strong> ${visaType}</div>
                <div><strong>Visa Category:</strong> ${visaCategory}</div>
            </td>
        </tr>
    </table>

    <table style="width:100%;border-collapse:collapse;font-size:14px;margin-bottom:30px;">
        <thead>
            <tr style="background:#f9f9f9;border-top:1px solid #333;border-bottom:1px solid #333;">
                <th style="padding:12px;text-align:left;font-size:11px;text-transform:uppercase;">Description</th>
                <th style="padding:12px;text-align:right;font-size:11px;text-transform:uppercase;">Amount (${currency})</th>
            </tr>
        </thead>
        <tbody>
            <tr><td style="padding:14px;border-bottom:1px solid #eee;">Base Price</td><td style="padding:14px;text-align:right;border-bottom:1px solid #eee;">${basePrice.toFixed(2)}</td></tr>
            ${additionalPrice > 0 ? `<tr><td style="padding:14px;border-bottom:1px solid #eee;">Additional Price</td><td style="padding:14px;text-align:right;border-bottom:1px solid #eee;">${additionalPrice.toFixed(2)}</td></tr>` : ''}
            ${discount > 0 ? `<tr><td style="padding:14px;border-bottom:1px solid #eee;color:#888;">Discount</td><td style="padding:14px;text-align:right;border-bottom:1px solid #eee;color:#888;">(${discount.toFixed(2)})</td></tr>` : ''}
        </tbody>
    </table>

    <div style="width:40%;margin-left:auto;">
        <table style="width:100%;font-size:14px;">
            <tr><td style="padding:8px 0;color:#888;">Total</td><td style="padding:8px 0;text-align:right;">${total.toFixed(2)}</td></tr>
            <tr><td style="padding:8px 0;color:#198754;">Advanced Paid</td><td style="padding:8px 0;text-align:right;color:#198754;">${advancedPaid.toFixed(2)}</td></tr>
            <tr style="border-top:1px solid #333;"><td style="padding:12px 0;font-weight:bold;font-size:16px;">Balance</td><td style="padding:12px 0;text-align:right;font-weight:bold;font-size:18px;">${currency} ${balance.toFixed(2)}</td></tr>
        </table>
    </div>

     ${note ? `
            <div style="margin-top:25px;padding:15px;border:1px dashed #ddd;background:#fafafa;">
                <h4 style="margin:0 0 8px;font-size:12px;color:#888;text-transform:uppercase;">
                    Note
                </h4>
                <div style="font-size:14px;line-height:1.6;color:#333;">
                    ${note.replace(/\n/g, '<br>')}
                </div>
            </div>
        ` : ''}

    <div style="margin-top:60px;text-align:center;border-top:1px solid #eee;padding-top:20px;font-size:11px;color:#aaa;">
        This is a system generated invoice. No signature required.<br>
        <strong>Vacay Guider</strong> | www.vacayguider.com
    </div>
</div>`;

            document.getElementById('bookingPreviewBody').innerHTML = html;
            new bootstrap.Modal(document.getElementById('bookingPreviewModal')).show();
        }

        // PDF Generation
        function generatePdf() {
            const htmlContent = document.getElementById('bookingPreviewBody').innerHTML;

            fetch("{{ route('admin.visa-bookings.generatePdf') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        html: htmlContent
                    })
                })
                .then(response => response.blob())
                .then(blob => {
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = 'Visa_Booking.pdf';
                    link.click();
                    URL.revokeObjectURL(link.href);
                })
                .catch(error => {
                    console.error(error);
                    alert("PDF generation failed");
                });
        }
    </script>
@endsection
