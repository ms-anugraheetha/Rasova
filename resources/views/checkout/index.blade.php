@extends('layouts.storefront')

@section('title', 'Checkout — Rasova')

@section('extra-styles')
.checkout-header { padding: 28px 0 20px; }
.checkout-header h1 { font-size: clamp(24px, 6vw, 34px); margin: 0; }
.checkout-layout { padding-bottom: 64px; display: flex; flex-direction: column; gap: 28px; }

/* Order summary */
.order-summary { padding: 20px; border-radius: 16px; background: var(--color-surface); }
.order-summary h2 { font-size: 16px; margin: 0 0 16px; }
.order-line { display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 10px; }
.order-total { display: flex; justify-content: space-between; font-weight: 700; font-size: 16px; border-top: 1px solid var(--color-divider); padding-top: 14px; margin-top: 8px; }

/* Saved address panel */
.saved-addresses-panel { padding: 20px; border-radius: 16px; background: var(--color-surface); }
.saved-addresses-panel h2 { font-size: 16px; margin: 0 0 16px; }
.saved-address-grid { display: flex; flex-direction: column; gap: 12px; margin-bottom: 14px; }
.saved-address-card {
    border: 1.5px solid var(--color-divider); border-radius: 14px; padding: 14px;
    cursor: pointer; transition: border-color 0.2s ease, background 0.2s ease; background: var(--color-bg);
}
.saved-address-card:hover { border-color: var(--color-accent-2-200); }
.saved-address-card.selected { border-color: var(--color-accent); background: var(--color-accent-2-100); }
.saved-address-type-badge {
    display: inline-block; font-size: 10px; text-transform: uppercase; letter-spacing: 0.04em; font-weight: 700;
    padding: 3px 9px; border-radius: 6px; background: var(--color-accent-2-100); color: var(--color-accent-700); margin-bottom: 8px;
}
.saved-address-default-badge {
    font-size: 9px; background: var(--color-accent); color: white; padding: 2px 7px;
    border-radius: 10px; text-transform: none; letter-spacing: 0; margin-left: 6px;
}
.saved-address-card p { margin: 0; font-size: 14px; }
.saved-address-preview { opacity: 0.7; font-size: 13px; margin-top: 4px !important; }
.saved-address-actions { display: flex; gap: 12px; margin-top: 10px; flex-wrap: wrap; }
.saved-address-actions button {
    border: none; background: none; padding: 0; font-size: 12px; cursor: pointer;
    color: var(--color-accent-700); font-family: inherit;
}
.saved-address-actions button.danger { color: var(--color-error, #b3132d); }
.saved-address-edit-form { margin-top: 4px; }
.inline-edit-input {
    width: 100%; min-height: 40px; padding: 0 12px; border-radius: 8px; margin-bottom: 8px;
    border: 1px solid var(--color-divider); background: var(--color-bg); color: inherit; font-size: 13px; font-family: inherit; box-sizing: border-box;
}
select.inline-edit-input { min-height: 40px; }
.inline-edit-row { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.inline-edit-row .inline-edit-input { margin-bottom: 8px; }
.inline-edit-chip {
    flex: 1; text-align: center; padding: 8px 6px; border-radius: 8px;
    border: 1.5px solid var(--color-divider); background: var(--color-bg);
    cursor: pointer; font-size: 12px; font-weight: 600; color: var(--color-text);
}
.inline-edit-chip.selected { border-color: var(--color-accent); background: var(--color-accent); color: #fff; }

.add-new-address-btn {
    border: 1.5px dashed var(--color-divider); border-radius: 14px; padding: 14px;
    text-align: center; font-size: 14px; font-weight: 600; cursor: pointer; background: none;
    width: 100%; color: var(--color-accent-700); font-family: inherit;
}

/* Address type — clean segmented buttons, no emoji */
.address-type-row { display: flex; gap: 8px; margin-bottom: 20px; }
.address-type-chip {
    flex: 1; text-align: center; padding: 12px 8px; border-radius: 10px;
    border: 1.5px solid var(--color-divider); background: var(--color-bg);
    cursor: pointer; font-size: 14px; font-weight: 600; color: var(--color-text);
    transition: border-color 0.2s ease, background 0.2s ease, color 0.2s ease;
}
.address-type-chip.selected { border-color: var(--color-accent); background: var(--color-accent); color: #fff; }

.checkout-form-card { padding: 20px; border-radius: 16px; background: var(--color-surface); }
.checkout-field { margin-bottom: 14px; position: relative; }
.checkout-field label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; }
.checkout-field .optional-tag { font-weight: 400; opacity: 0.5; font-size: 12px; }
.checkout-field input {
    width: 100%; min-height: 46px; padding: 0 14px; border-radius: 10px;
    border: 1px solid var(--color-divider); background: var(--color-bg); color: inherit; font-size: 15px; box-sizing: border-box;
}
.checkout-field input.field-error { border-color: var(--color-error, #b3132d); }
.field-error-msg { font-size: 12px; color: var(--color-error, #b3132d); margin-top: 5px; min-height: 14px; }
.checkout-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.checkout-submit { width: 100%; min-height: 50px; margin-top: 16px; font-size: 15px; }
.checkout-submit:disabled { opacity: 0.5; cursor: not-allowed; }

.phone-input-wrap { display: flex; align-items: center; border: 1px solid var(--color-divider); border-radius: 10px; overflow: hidden; }
.phone-input-wrap.field-error { border-color: var(--color-error, #b3132d); }
.phone-prefix { padding: 0 10px; font-size: 15px; opacity: 0.6; border-right: 1px solid var(--color-divider); min-height: 46px; display: flex; align-items: center; }
.phone-input-wrap input { border: none; border-radius: 0; }

/* State combobox */
.state-combobox { position: relative; }
.state-combobox input { cursor: text; }
.state-dropdown {
    display: none; position: absolute; top: calc(100% + 4px); left: 0; right: 0; z-index: 30;
    background: var(--color-bg); border: 1px solid var(--color-divider); border-radius: 10px;
    max-height: 220px; overflow-y: auto; box-shadow: var(--shadow-lg);
}
.state-dropdown.open { display: block; }
.state-option { padding: 10px 14px; font-size: 14px; cursor: pointer; }
.state-option:hover, .state-option.active { background: var(--color-bg); }
.state-no-match { padding: 10px 14px; font-size: 13px; opacity: 0.5; }

.checkbox-row { display: flex; align-items: center; gap: 8px; font-size: 14px; margin-bottom: 14px; cursor: pointer; }
.checkbox-row input { width: 16px; height: 16px; }

/* ===== DESKTOP: 3-column grid — Summary | Form | Saved Addresses ===== */
@media (min-width: 1024px) {
    .checkout-layout {
        display: grid;
        grid-template-columns: 0.9fr 1.3fr 0.9fr;
        gap: 28px;
        align-items: start;
    }
    .order-summary { grid-column: 1; position: sticky; top: 90px; }
    .checkout-form-wrap { grid-column: 2; }
    .saved-addresses-panel { grid-column: 3; position: sticky; top: 90px; }
}
@endsection

@section('content')

<header class="wrap checkout-header">
    <h1>Checkout</h1>
</header>

@if (session('error'))
    <div class="wrap" style="padding-bottom:16px;">
        <p style="color:var(--color-error, #b3132d); font-size:13px;">{{ session('error') }}</p>
    </div>
@endif
@if (session('success'))
    <div class="wrap" style="padding-bottom:16px;">
        <p style="color:var(--color-success, green); font-size:13px;">{{ session('success') }}</p>
    </div>
@endif
@if ($errors->any())
    <div class="wrap" style="padding-bottom:16px;">
        @foreach ($errors->all() as $error)
            <p style="color:var(--color-error, #b3132d); font-size:13px;">{{ $error }}</p>
        @endforeach
    </div>
@endif

<div class="wrap checkout-layout">

    <div class="order-summary">
        <h2>Order summary</h2>
        @foreach ($items as $item)
            <div class="order-line">
                <span>{{ $item->productVariant->product->name }} ({{ $item->productVariant->weight }}) &times;{{ $item->quantity }}</span>
                <span>&#8377;{{ number_format($item->productVariant->price_minor * $item->quantity / 100, 2) }}</span>
            </div>
        @endforeach
        <div class="order-total">
            <span>Subtotal</span>
            <span>&#8377;{{ number_format($subtotal / 100, 2) }}</span>
        </div>
    </div>

    <div class="checkout-form-wrap">
        <form method="POST" action="{{ route('checkout.store') }}" class="checkout-form-card" id="checkoutForm" novalidate>
            @csrf
            <h2 style="font-size:16px;margin:0 0 16px;">Shipping details</h2>

            @guest
                <div class="checkout-field">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                </div>
            @endguest

            <input type="hidden" name="selected_address_id" id="selectedAddressId" value="">
            <input type="hidden" name="editing_address_id" id="editingAddressId" value="">

            <div class="address-type-row" id="addressTypeRow">
                <label class="address-type-chip" data-value="home">
                    <input type="radio" name="address_type" value="home" style="display:none;" checked>
                    Home
                </label>
                <label class="address-type-chip" data-value="office">
                    <input type="radio" name="address_type" value="office" style="display:none;">
                    Office
                </label>
                <label class="address-type-chip" data-value="other">
                    <input type="radio" name="address_type" value="other" style="display:none;">
                    Other
                </label>
            </div>

            <div class="checkout-field">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                <p class="field-error-msg" data-error-for="full_name"></p>
            </div>

            <div class="checkout-field">
                <label for="phone">Phone Number</label>
                <div class="phone-input-wrap" id="phoneWrap">
                    <span class="phone-prefix">+91</span>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" inputmode="numeric" maxlength="10" required>
                </div>
                <p class="field-error-msg" data-error-for="phone"></p>
            </div>

            <div class="checkout-field">
                <label for="address_line_1">House No., Building, Street</label>
                <input type="text" id="address_line_1" name="address_line_1" value="{{ old('address_line_1') }}" required>
                <p class="field-error-msg" data-error-for="address_line_1"></p>
            </div>

            <div class="checkout-field">
                <label for="address_line_2">Apartment, Suite, Landmark <span class="optional-tag">(Optional)</span></label>
                <input type="text" id="address_line_2" name="address_line_2" value="{{ old('address_line_2') }}">
            </div>

            <div class="checkout-field checkout-row">
                <div>
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" value="{{ old('city') }}" required>
                    <p class="field-error-msg" data-error-for="city"></p>
                </div>
                <div>
                    <label for="district">District</label>
                    <input type="text" id="district" name="district" value="{{ old('district') }}" required>
                    <p class="field-error-msg" data-error-for="district"></p>
                </div>
            </div>

            <div class="checkout-field">
                <div class="state-combobox">
                    <label for="stateInput">State</label>
                    <input type="text" id="stateInput" autocomplete="off"  value="{{ old('state') }}" required>
                    <input type="hidden" name="state" id="stateValue" value="{{ old('state') }}">
                    <div class="state-dropdown" id="stateDropdown"></div>
                </div>
                <p class="field-error-msg" data-error-for="state"></p>
            </div>

            <div class="checkout-field">
                <label for="postal_code">PIN Code</label>
                <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" inputmode="numeric" maxlength="6" required>
                <p class="field-error-msg" data-error-for="postal_code"></p>
            </div>

            @auth
                <label class="checkbox-row">
                    <input type="checkbox" name="save_address" id="saveAddressCheck" value="1">
                    Save this address
                </label>
                <label class="checkbox-row" id="setDefaultRow" style="display:none;">
                    <input type="checkbox" name="set_default" value="1">
                    Set as Default Address
                </label>
            @endauth

            <button type="submit" class="btn btn-primary checkout-submit" id="placeOrderBtn" disabled>Place Order</button>
            <p id="placeOrderHint" style="font-size:12px;color:var(--color-error, #b3132d);text-align:center;margin-top:8px;min-height:14px;"></p>

            @guest
                <p style="font-size:13px;opacity:0.65;text-align:center;margin-top:14px;">
                    Have an account? <a href="{{ route('login') }}">Log in</a> to check out faster.
                </p>
            @endguest
        </form>
    </div>

    @auth
        <div class="saved-addresses-panel">
            <h2>Saved addresses</h2>

            @if ($savedAddresses->isNotEmpty())
                <div class="saved-address-grid" id="savedAddressGrid">
                    @foreach ($savedAddresses as $addr)
                        <div class="saved-address-card">
                            <div class="saved-address-view">
                                <span class="saved-address-type-badge">
                                    {{ strtoupper($addr->address_type) }}
                                    @if ($addr->is_default)
                                        <span class="saved-address-default-badge">Default</span>
                                    @endif
                                </span>
                                <p><strong>{{ $addr->full_name }}</strong></p>
                                <p>{{ $addr->phone }}</p>
                                <p class="saved-address-preview">{{ $addr->address_line_1 }}{{ $addr->address_line_2 ? ', ' . $addr->address_line_2 : '' }}, {{ $addr->city }}{{ $addr->district ? ', ' . $addr->district : '' }}, {{ $addr->state }} {{ $addr->postal_code }}</p>
                                <div class="saved-address-actions">
                                    <button type="button" class="select-address-btn"
                                        data-id="{{ $addr->id }}"
                                        data-address-type="{{ $addr->address_type }}"
                                        data-full-name="{{ $addr->full_name }}"
                                        data-phone="{{ $addr->phone }}"
                                        data-address-line1="{{ $addr->address_line_1 }}"
                                        data-address-line2="{{ $addr->address_line_2 }}"
                                        data-city="{{ $addr->city }}"
                                        data-district="{{ $addr->district }}"
                                        data-state="{{ $addr->state }}"
                                        data-postal-code="{{ $addr->postal_code }}">Select</button>
                                    <button type="button" class="edit-toggle-btn">Edit</button>
                                    @if (! $addr->is_default)
                                        <form method="POST" action="{{ route('addresses.setDefault', $addr->id) }}" style="display:inline;">
                                            @csrf @method('PATCH')
                                            <button type="submit">Set as Default</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('addresses.destroy', $addr->id) }}" style="display:inline;" data-confirm="Delete this address?">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="danger">Delete</button>
                                    </form>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('addresses.update', $addr->id) }}" class="saved-address-edit-form" style="display:none;">
                                @csrf @method('PATCH')

                                <div class="address-type-row" style="margin-bottom:10px;">
                                    <label class="address-type-chip inline-edit-chip {{ $addr->address_type === 'home' ? 'selected' : '' }}" data-value="home">
                                        <input type="radio" name="address_type" value="home" style="display:none;" {{ $addr->address_type === 'home' ? 'checked' : '' }}>
                                        Home
                                    </label>
                                    <label class="address-type-chip inline-edit-chip {{ $addr->address_type === 'office' ? 'selected' : '' }}" data-value="office">
                                        <input type="radio" name="address_type" value="office" style="display:none;" {{ $addr->address_type === 'office' ? 'checked' : '' }}>
                                        Office
                                    </label>
                                    <label class="address-type-chip inline-edit-chip {{ $addr->address_type === 'other' ? 'selected' : '' }}" data-value="other">
                                        <input type="radio" name="address_type" value="other" style="display:none;" {{ $addr->address_type === 'other' ? 'checked' : '' }}>
                                        Other
                                    </label>
                                </div>

                                <input type="text" name="full_name" class="inline-edit-input" placeholder="Full Name" value="{{ $addr->full_name }}" required>
                                <input type="text" name="phone" class="inline-edit-input inline-edit-phone" placeholder="Phone" value="{{ $addr->phone }}" inputmode="numeric" maxlength="10" required>
                                <input type="text" name="address_line_1" class="inline-edit-input" placeholder="House No., Building, Street" value="{{ $addr->address_line_1 }}" required>
                                <input type="text" name="address_line_2" class="inline-edit-input" placeholder="Apartment, Suite, Landmark (Optional)" value="{{ $addr->address_line_2 }}">
                                <div class="inline-edit-row">
                                    <input type="text" name="city" class="inline-edit-input" placeholder="City" value="{{ $addr->city }}" required>
                                    <input type="text" name="district" class="inline-edit-input" placeholder="District" value="{{ $addr->district }}" required>
                                </div>
                                <select name="state" class="inline-edit-input" required>
                                    <option value="">Select state</option>
                                    @foreach ($indianStates as $stateOption)
                                        <option value="{{ $stateOption }}" {{ $addr->state === $stateOption ? 'selected' : '' }}>{{ $stateOption }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="postal_code" class="inline-edit-input inline-edit-pin" placeholder="PIN Code" value="{{ $addr->postal_code }}" inputmode="numeric" maxlength="6" required>

                                <div class="saved-address-actions" style="margin-top:10px;">
                                    <button type="submit" class="btn btn-primary" style="min-height:38px;padding:0 16px;font-size:13px;">Save</button>
                                    <button type="button" class="btn btn-secondary cancel-edit-btn" style="min-height:38px;padding:0 16px;font-size:13px;">Cancel</button>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="font-size:13px;opacity:0.6;margin-bottom:14px;">You don't have any saved addresses yet.</p>
            @endif

            <button type="button" class="add-new-address-btn" id="addNewAddressBtn">+ Add New Address</button>
        </div>
    @endauth

</div>

@endsection

@push('scripts')
<script>
    var INDIAN_STATES = @json($indianStates);
</script>
<script>
    (function () {
        // ===== Address type chips =====
        var typeChips = document.querySelectorAll('.address-type-chip');
        function setActiveChip(value) {
            typeChips.forEach(function (chip) {
                var isMatch = chip.getAttribute('data-value') === value;
                chip.classList.toggle('selected', isMatch);
                chip.querySelector('input').checked = isMatch;
            });
        }
        typeChips.forEach(function (chip) {
            chip.addEventListener('click', function () {
                setActiveChip(chip.getAttribute('data-value'));
            });
        });
        setActiveChip('home');

        // ===== Phone: digits only, max 10 =====
        var phoneInput = document.getElementById('phone');
        phoneInput.addEventListener('input', function () {
            phoneInput.value = phoneInput.value.replace(/\D/g, '').slice(0, 10);
            refreshSubmitState();
        });

        // ===== PIN code: digits only, max 6 =====
        var pinInput = document.getElementById('postal_code');
        pinInput.addEventListener('input', function () {
            pinInput.value = pinInput.value.replace(/\D/g, '').slice(0, 6);
            refreshSubmitState();
        });

        // ===== State combobox =====
        var stateInput = document.getElementById('stateInput');
        var stateHidden = document.getElementById('stateValue');
        var stateDropdown = document.getElementById('stateDropdown');
        var activeIndex = -1;
        var filteredStates = [];

        function renderStateOptions(filter) {
            var lower = filter.toLowerCase();
            filteredStates = INDIAN_STATES.filter(function (s) {
                return s.toLowerCase().indexOf(lower) !== -1;
            });
            activeIndex = -1;

            if (filteredStates.length === 0) {
                stateDropdown.innerHTML = '<div class="state-no-match">No matching states</div>';
            } else {
                stateDropdown.innerHTML = filteredStates.map(function (s, i) {
                    return '<div class="state-option" data-index="' + i + '">' + s + '</div>';
                }).join('');
            }
            stateDropdown.classList.add('open');
        }

        function selectState(value) {
            stateInput.value = value;
            stateHidden.value = value;
            stateDropdown.classList.remove('open');
            refreshSubmitState();
        }

        stateInput.addEventListener('focus', function () {
            renderStateOptions(stateInput.value);
        });
        stateInput.addEventListener('input', function () {
            stateHidden.value = '';
            renderStateOptions(stateInput.value);
            refreshSubmitState();
        });
        stateDropdown.addEventListener('click', function (e) {
            var option = e.target.closest('.state-option');
            if (option) selectState(option.textContent);
        });
        stateInput.addEventListener('keydown', function (e) {
            var options = stateDropdown.querySelectorAll('.state-option');
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                activeIndex = Math.min(activeIndex + 1, options.length - 1);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                activeIndex = Math.max(activeIndex - 1, 0);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (activeIndex >= 0 && filteredStates[activeIndex]) {
                    selectState(filteredStates[activeIndex]);
                }
                return;
            } else if (e.key === 'Escape') {
                stateDropdown.classList.remove('open');
                return;
            } else {
                return;
            }
            options.forEach(function (opt, i) {
                opt.classList.toggle('active', i === activeIndex);
            });
        });
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.state-combobox')) {
                stateDropdown.classList.remove('open');
            }
        });

        // ===== Saved address cards: select / edit =====
        var addressFields = {
            full_name: document.getElementById('full_name'),
            phone: document.getElementById('phone'),
            address_line_1: document.getElementById('address_line_1'),
            address_line_2: document.getElementById('address_line_2'),
            city: document.getElementById('city'),
            district: document.getElementById('district'),
            postal_code: document.getElementById('postal_code'),
        };
        var selectedAddressIdInput = document.getElementById('selectedAddressId');
        var editingAddressIdInput = document.getElementById('editingAddressId');
        var saveAddressCheck = document.getElementById('saveAddressCheck');
        var setDefaultRow = document.getElementById('setDefaultRow');

        function populateForm(data) {
            setActiveChip(data.address_type);
            addressFields.full_name.value = data.full_name || '';
            addressFields.phone.value = data.phone || '';
            addressFields.address_line_1.value = data.address_line_1 || '';
            addressFields.address_line_2.value = data.address_line_2 || '';
            addressFields.city.value = data.city || '';
            addressFields.district.value = data.district || '';
            addressFields.postal_code.value = data.postal_code || '';
            selectState(data.state || '');
            refreshSubmitState();
        }

        document.querySelectorAll('.select-address-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var data = {
                    id: btn.dataset.id,
                    address_type: btn.dataset.addressType,
                    full_name: btn.dataset.fullName,
                    phone: btn.dataset.phone,
                    address_line_1: btn.dataset.addressLine1,
                    address_line_2: btn.dataset.addressLine2,
                    city: btn.dataset.city,
                    district: btn.dataset.district,
                    state: btn.dataset.state,
                    postal_code: btn.dataset.postalCode,
                };
                document.querySelectorAll('.saved-address-card').forEach(function (c) { c.classList.remove('selected'); });
                btn.closest('.saved-address-card').classList.add('selected');
                populateForm(data);
                selectedAddressIdInput.value = data.id;
                editingAddressIdInput.value = '';
                if (saveAddressCheck) { saveAddressCheck.checked = false; setDefaultRow.style.display = 'none'; }
            });
        });

        // ===== Inline card editing: expand/collapse, chip selection, digit-only, cancel =====
        document.querySelectorAll('.saved-address-card').forEach(function (card) {
            var viewEl = card.querySelector('.saved-address-view');
            var editForm = card.querySelector('.saved-address-edit-form');
            var editToggleBtn = card.querySelector('.edit-toggle-btn');
            var cancelBtn = card.querySelector('.cancel-edit-btn');

            editToggleBtn.addEventListener('click', function () {
                viewEl.style.display = 'none';
                editForm.style.display = 'block';
            });

            cancelBtn.addEventListener('click', function () {
                editForm.style.display = 'none';
                viewEl.style.display = 'block';
            });

            editForm.querySelectorAll('.inline-edit-chip').forEach(function (chip) {
                chip.addEventListener('click', function () {
                    editForm.querySelectorAll('.inline-edit-chip').forEach(function (c) {
                        var match = c.getAttribute('data-value') === chip.getAttribute('data-value');
                        c.classList.toggle('selected', match);
                        c.querySelector('input').checked = match;
                    });
                });
            });

            var phoneField = editForm.querySelector('.inline-edit-phone');
            if (phoneField) {
                phoneField.addEventListener('input', function () {
                    phoneField.value = phoneField.value.replace(/\D/g, '').slice(0, 10);
                });
            }
            var pinField = editForm.querySelector('.inline-edit-pin');
            if (pinField) {
                pinField.addEventListener('input', function () {
                    pinField.value = pinField.value.replace(/\D/g, '').slice(0, 6);
                });
            }
        });

        var addNewBtn = document.getElementById('addNewAddressBtn');
        if (addNewBtn) {
            addNewBtn.addEventListener('click', function () {
                document.querySelectorAll('.saved-address-card').forEach(function (c) { c.classList.remove('selected'); });
                populateForm({ address_type: 'home', full_name: '', phone: '', address_line_1: '', address_line_2: '', city: '', district: '', state: '', postal_code: '' });
                selectedAddressIdInput.value = '';
                editingAddressIdInput.value = '';
                if (saveAddressCheck) { saveAddressCheck.disabled = false; saveAddressCheck.checked = false; setDefaultRow.style.display = 'none'; }
            });
        }

        if (saveAddressCheck) {
            saveAddressCheck.addEventListener('change', function () {
                setDefaultRow.style.display = saveAddressCheck.checked ? 'flex' : 'none';
            });
        }

        // ===== Disable submit until all required fields are valid =====
        var placeOrderBtn = document.getElementById('placeOrderBtn');
        var emailInput = document.getElementById('email'); // only present for guests

        function refreshSubmitState() {
            var valid = true;
            var missing = [];

            if (!addressFields.full_name.value.trim()) { valid = false; missing.push('Full Name'); }
            if (!/^\d{10}$/.test(addressFields.phone.value)) { valid = false; missing.push('Phone Number'); }
            if (!addressFields.address_line_1.value.trim()) { valid = false; missing.push('Address'); }
            if (!addressFields.city.value.trim()) { valid = false; missing.push('City'); }
            if (!addressFields.district.value.trim()) { valid = false; missing.push('District'); }
            if (!stateHidden.value || INDIAN_STATES.indexOf(stateHidden.value) === -1) { valid = false; missing.push('State'); }
            if (!/^\d{6}$/.test(addressFields.postal_code.value)) { valid = false; missing.push('PIN Code'); }
            if (emailInput && !emailInput.value.includes('@')) { valid = false; missing.push('Email'); }

            placeOrderBtn.disabled = !valid;

            var hintEl = document.getElementById('placeOrderHint');
            if (hintEl) {
                hintEl.textContent = missing.length ? 'Please complete: ' + missing.join(', ') : '';
            }
        }

        [addressFields.full_name, addressFields.address_line_1, addressFields.city, addressFields.district].forEach(function (el) {
            el.addEventListener('input', refreshSubmitState);
        });
        if (emailInput) emailInput.addEventListener('input', refreshSubmitState);

        refreshSubmitState();

        // ===== Inline validation on submit (defensive, in case JS state drifts) =====
        var form = document.getElementById('checkoutForm');
        form.addEventListener('submit', function (e) {
            var valid = true;

            function showError(fieldId, message, wrapEl) {
                var input = document.getElementById(fieldId);
                var errorEl = form.querySelector('[data-error-for="' + fieldId + '"]');
                (wrapEl || input).classList.add('field-error');
                if (errorEl) errorEl.textContent = message;
                valid = false;
            }
            function clearError(fieldId, wrapEl) {
                var input = document.getElementById(fieldId);
                var errorEl = form.querySelector('[data-error-for="' + fieldId + '"]');
                (wrapEl || input).classList.remove('field-error');
                if (errorEl) errorEl.textContent = '';
            }

            clearError('full_name'); clearError('address_line_1'); clearError('city'); clearError('district'); clearError('postal_code');
            clearError('phone', document.getElementById('phoneWrap'));
            clearError('state');

            if (!document.getElementById('full_name').value.trim()) showError('full_name', 'Please enter your full name.');
            if (!document.getElementById('address_line_1').value.trim()) showError('address_line_1', 'Please enter your address.');
            if (!document.getElementById('city').value.trim()) showError('city', 'Please enter your city.');
            if (!document.getElementById('district').value.trim()) showError('district', 'Please enter your district.');

            var phoneVal = document.getElementById('phone').value;
            if (!/^\d{10}$/.test(phoneVal)) showError('phone', 'Enter a valid 10-digit phone number.', document.getElementById('phoneWrap'));

            var pinVal = document.getElementById('postal_code').value;
            if (!/^\d{6}$/.test(pinVal)) showError('postal_code', 'Enter a valid 6-digit PIN code.');

            if (!stateHidden.value || INDIAN_STATES.indexOf(stateHidden.value) === -1) showError('state', 'Please select a valid state from the list.');

            if (!valid) e.preventDefault();
        });
    })();
</script>
@endpush