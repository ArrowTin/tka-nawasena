@extends('layouts/layoutMaster')

@section('title', 'Dashboard')

@section('content')
<div class="container">

    <!-- ====================== FILTER ====================== -->
    <div class="card mb-4">
        <div class="card-header fw-bold">Filter Data</div>
        <div class="card-body">
            <form method="GET">
                <div class="row g-3">

                    <div class="col-md-3">
                        <label>Kategori</label>
                        <select name="category_id" class="form-control">
                            <option value="">Semua</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected':'' }}>
                                    {{ $c->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>Mata Pelajaran</label>
                        <select name="subject_id" class="form-control">
                            <option value="">Semua</option>
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected':'' }}>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="date_start" value="{{ request('date_start') }}" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label>Tanggal Selesai</label>
                        <input type="date" name="date_end" value="{{ request('date_end') }}" class="form-control">
                    </div>

                </div>

                <div class="mt-3">
                    <button class="btn btn-primary">Terapkan Filter</button>
                    <a href="{{ route('su.beranda') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- ====================== STATISTIK ====================== -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center p-3 shadow-sm">
                <h6 class="text-muted">Total Ujian</h6>
                <h2>{{ $totalUjian }}</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3 shadow-sm">
                <h6 class="text-muted">Total Soal</h6>
                <h2>{{ $totalSoal }}</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3 shadow-sm">
                <h6 class="text-muted">Total Attempts</h6>
                <h2>{{ $totalAttempts }}</h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3 shadow-sm">
                <h6 class="text-muted">Rata-Rata Soal/Ujian</h6>
                <h2>{{ $avgQuestions }}</h2>
            </div>
        </div>
    </div>

    <!-- ====================== CHART 1 ====================== -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header fw-bold">Jumlah Soal per Ujian</div>
        <div class="card-body">
            <canvas id="chartQuestions"></canvas>
        </div>
    </div>

    <!-- ====================== CHART 2 ====================== -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header fw-bold">Jumlah Attempt per Ujian</div>
        <div class="card-body">
            <canvas id="chartAttempts"></canvas>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const labels = @json($chartLabels);
    const dataQuestions = @json($chartData);
    const dataAttempts = @json($chartAttempts);

    // ===== Chart Jumlah Soal =====
    new Chart(document.getElementById('chartQuestions'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Soal',
                data: dataQuestions,
            }]
        }
    });

    // ===== Chart Attempts =====
    new Chart(document.getElementById('chartAttempts'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Attempt',
                data: dataAttempts,
                fill: true,
            }]
        }
    });
</script>
<script>
    const tkn = new URLSearchParams(window.location.search).get("token");
    
    if (tkn) {
        localStorage.setItem("api_token", tkn);
    }
</script>
@endpush
