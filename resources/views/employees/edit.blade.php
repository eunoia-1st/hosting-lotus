@extends('layouts.admin_layout')

@section('title', 'Edit Karyawan & Shift')
@section('page-title', "Edit Karyawan & Shift: {$employee->name}")

@push('styles')
    <style>
        .hidden {
            display: none !important;
        }
    </style>
@endpush

@section('content')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Data Karyawan</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('employees.updateDetails', $employee->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Data Karyawan --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold">Nama</label>
                        <input type="text" id="name" name="name" class="form-control"
                            value="{{ old('name', $employee->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="position" class="form-label fw-semibold">Posisi</label>
                        <select id="position" name="position" class="form-select" required>
                            @foreach (['office', 'cook', 'waiter', 'staff', 'bar'] as $pos)
                                <option value="{{ $pos }}" {{ $employee->position == $pos ? 'selected' : '' }}>
                                    {{ ucfirst($pos) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Card Shift --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0">Shift Mingguan</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle mb-0">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th style="width: 15%;">Hari</th>
                                        <th style="width: 45%;">Jam Kerja</th>
                                        <th style="width: 25%;">Tipe Shift</th>
                                        <th style="width: 10%;">Libur?</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($details as $day => $detail)
                                        @php $dayKey = is_string($day) ? $day : $detail->day; @endphp
                                        <tr
                                            class="{{ !$detail->start_time && !$detail->shift_type ? 'table-secondary' : '' }}">
                                            <td class="fw-bold text-center">{{ ucfirst($dayKey) }}</td>

                                            <td>
                                                <div
                                                    class="d-flex align-items-center justify-content-center gap-2 flex-wrap">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <input type="time" name="detail[{{ $dayKey }}][start_time]"
                                                            value="{{ $detail->start_time }}"
                                                            class="form-control start-time text-center"
                                                            data-day="{{ $dayKey }}">
                                                        <span>-</span>
                                                        <input type="time" name="detail[{{ $dayKey }}][end_time]"
                                                            value="{{ $detail->end_time }}"
                                                            class="form-control end-time text-center"
                                                            data-day="{{ $dayKey }}">
                                                    </div>

                                                    {{-- PERUBAHAN KUNCI DI SINI: Menambahkan class 'hidden' secara kondisional menggunakan Blade --}}
                                                    <div class="split-shift-inputs d-flex align-items-center gap-2 {{ $detail->shift_type !== 'split' ? 'hidden' : '' }}"
                                                        data-day="{{ $dayKey }}">
                                                        <span class="fw-bold">&</span>
                                                        <input type="time"
                                                            name="detail[{{ $dayKey }}][start_time_2]"
                                                            value="{{ $detail->start_time_2 }}"
                                                            class="form-control start-time-2 text-center"
                                                            data-day="{{ $dayKey }}">
                                                        <span>-</span>
                                                        <input type="time"
                                                            name="detail[{{ $dayKey }}][end_time_2]"
                                                            value="{{ $detail->end_time_2 }}"
                                                            class="form-control end-time-2 text-center"
                                                            data-day="{{ $dayKey }}">
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <select name="detail[{{ $dayKey }}][shift_type]"
                                                    class="form-select shift-type text-center"
                                                    data-day="{{ $dayKey }}">
                                                    <option value="">-- Pilih / Libur --</option>
                                                    @foreach (['morning', 'evening', 'split', 'middle'] as $stype)
                                                        <option value="{{ $stype }}"
                                                            {{ $detail->shift_type == $stype ? 'selected' : '' }}>
                                                            {{ ucfirst($stype) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td class="text-center">
                                                <input type="checkbox" name="detail[{{ $dayKey }}][is_off]"
                                                    class="form-check-input off-day-toggle" data-day="{{ $dayKey }}"
                                                    {{ !$detail->start_time && !$detail->shift_type ? 'checked' : '' }}>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">💾 Simpan Semua</button>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">← Kembali</a>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    {{-- Script JavaScript tidak perlu diubah, karena sudah mendukung logika ini --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            function handleShiftTypeChange(selectElement) {
                const day = selectElement.dataset.day;
                const splitInputsContainer = document.querySelector(`.split-shift-inputs[data-day="${day}"]`);
                const splitInputs = splitInputsContainer.querySelectorAll('input[type="time"]');

                if (selectElement.value === 'split') {
                    splitInputsContainer.classList.remove('hidden');
                } else {
                    splitInputsContainer.classList.add('hidden');
                    splitInputs.forEach(input => input.value = '');
                }
            }

            function toggleOffDayFields(checkbox) {
                const tr = checkbox.closest('tr');
                const isChecked = checkbox.checked;
                const inputs = tr.querySelectorAll('input[type="time"], select.shift-type');
                inputs.forEach(input => input.disabled = isChecked);

                if (isChecked) {
                    tr.classList.add('table-secondary');
                    tr.querySelectorAll('input[type="time"]').forEach(i => i.value = '');
                    tr.querySelector('.shift-type').value = '';
                    handleShiftTypeChange(tr.querySelector('.shift-type'));
                } else {
                    tr.classList.remove('table-secondary');
                }
            }

            document.querySelectorAll('.shift-type').forEach(shiftSelect => {
                // Baris ini tidak lagi krusial untuk state awal, tapi tetap baik untuk interaksi
                handleShiftTypeChange(shiftSelect);
                shiftSelect.addEventListener('change', (e) => handleShiftTypeChange(e.target));
            });

            document.querySelectorAll('.off-day-toggle').forEach(cb => {
                toggleOffDayFields(cb);
                cb.addEventListener('change', (e) => toggleOffDayFields(e.target));
            });
        });
    </script>
@endpush
