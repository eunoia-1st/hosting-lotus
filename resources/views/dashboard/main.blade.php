@extends('layouts.admin_layout')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
    <div class="container mt-4">


        {{-- 📊 Summary Cards --}}
        <div class="row g-3 mb-4">
            @foreach ([['label' => 'Total Customer', 'value' => $totalCustomer, 'color' => 'primary'], ['label' => 'Total Category', 'value' => $totalCategory, 'color' => 'success'], ['label' => 'Total Feedback', 'value' => $totalFeedback, 'color' => 'warning text-dark']] as $card)
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 rounded-3 h-100 text-center p-3">
                        <h6 class="text-muted mb-2">{{ $card['label'] }}</h6>
                        <div class="fs-3 fw-bold text-{{ $card['color'] }}">{{ $card['value'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row g-4 mb-4">
            {{-- 🔎 Filter Tanggal --}}
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <form method="GET" action="{{ route('dashboard.main') }}" class="row g-3 align-items-end">
                            <div class="col-md-6">
                                <label class="form-label">Dari</label>
                                <input type="date" name="start_date" class="form-control"
                                    value="{{ \Carbon\Carbon::parse($start)->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sampai</label>
                                <input type="date" name="end_date" class="form-control"
                                    value="{{ \Carbon\Carbon::parse($end)->format('Y-m-d') }}">
                            </div>
                            <div class="col-12 d-flex gap-2">
                                <button class="btn btn-primary w-50">Apply</button>
                                <a href="{{ route('dashboard.main') }}" class="btn btn-secondary w-50">Reset</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- 👨‍💼 Karyawan Aktif --}}
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body p-3">
                        <h6 class="mb-3" style="font-size: 0.95rem;">Karyawan yang Sedang Bekerja</h6>
                        @if ($activeEmployees->count())
                            <ul class="list-group list-group-flush" style="font-size: 0.875rem;">
                                @foreach ($activeEmployees as $shift)
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-1 px-2">
                                        {{ $shift->employee->name }}
                                        <span class="text-muted small">
                                            ({{ $shift->employee->position_label }} | {{ ucfirst($shift->shift_type) }})
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-0" style="font-size: 0.875rem;">Tidak ada karyawan yang sedang bekerja
                                saat ini.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>


        {{-- 🏆 Top / Bottom Category --}}
        <div class="d-flex gap-2 mb-4 flex-wrap">
            <span class="badge bg-success px-3 py-2 rounded-pill">
                Top: {{ $topCategory->name ?? '-' }} ({{ $topCategory->total ?? 0 }})
            </span>
            <span class="badge bg-warning px-3 py-2 rounded-pill text-dark">
                Bottom: {{ $bottomCategory->name ?? '-' }} ({{ $bottomCategory->total ?? 0 }})
            </span>
        </div>

        {{-- 📊 Charts --}}
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-3 p-3">
                    <h6 class="mb-3 fw-bold"><i class="bi bi-bar-chart"></i> Feedback per Kategori</h6>
                    <canvas id="barChart" height="200"></canvas>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 rounded-3 p-3">
                    <h6 class="mb-3 fw-bold"><i class="bi bi-graph-up"></i> Tren Feedback per Kategori</h6>
                    <canvas id="lineChart" height="200"></canvas>
                </div>
            </div>
        </div>

        {{-- 📝 Feedback Hari Ini --}}
        <div class="card shadow-sm border-0 rounded-3 mt-4">
            <div class="card-header bg-dark text-white fw-bold">
                Feedback Hari Ini
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kategori</th>
                                <th>Customer</th>
                                <th>Seat</th>
                                <th>Tanggal Submit</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($todayFeedback as $i => $f)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $f->answers->pluck('question.question_category.name')->unique()->implode(', ') }}
                                    </td>
                                    <td>{{ $f->customer->name ?? 'Anonim' }}</td>
                                    <td>{{ $f->seat->name ?? '-' }}</td>
                                    <td>{{ $f->created_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('feedback-answers.show', $f->id) }}"
                                            class="btn btn-primary btn-sm">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">
                                        Belum ada feedback hari ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const palette = ['rgba(75,192,192,0.6)', 'rgba(255,159,64,0.6)', 'rgba(153,102,255,0.6)', 'rgba(255,205,86,0.6)',
            'rgba(54,162,235,0.6)', 'rgba(201,203,207,0.6)'
        ];

        // Bar Chart
        new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: {
                labels: @json($barLabels),
                datasets: [{
                    label: 'Jumlah Feedback',
                    data: @json($barData),
                    backgroundColor: @json($barLabels).map((_, i) => palette[i % palette
                        .length]),
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#333',
                        titleColor: '#fff',
                        bodyColor: '#eee',
                        padding: 10
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            stepSize: 1
                        },
                        grid: {
                            color: 'rgba(200,200,200,0.2)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Line Chart
        const styledDatasets = @json($lineDatasets).map((ds, i) => ({
            ...ds,
            borderColor: palette[i % palette.length].replace('0.6', '1'),
            backgroundColor: palette[i % palette.length],
            tension: 0.4,
            pointRadius: 4,
            pointHoverRadius: 6,
            fill: true
        }));
        new Chart(document.getElementById('lineChart'), {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: styledDatasets
            },
            options: {
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: '#222',
                        titleColor: '#fff',
                        bodyColor: '#ddd',
                        padding: 10
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        grid: {
                            color: 'rgba(200,200,200,0.2)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // ======================================================================
        // KODE DI BAWAH INI ADALAH PENYEBAB GLITCHING DAN TELAH DIHAPUS
        // Layout utama sudah memiliki skrip jam, jadi tidak perlu ada skrip kedua.
        // ----------------------------------------------------------------------
        // function updateTime() { ... }
        // setInterval(updateTime, 1000);
        // ======================================================================
    </script>
@endsection
