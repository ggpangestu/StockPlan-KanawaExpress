{{-- resources/views/production/detail.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Production Detail</title>

    {{-- Google Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @extends('layouts.app')

@section('content')

<style>

    .main-content{
        padding:40px;
        background:#f7f5f2;
        min-height:100vh;
    }

    .page-title{
        font-size:42px;
        font-weight:700;
        letter-spacing:-1px;
        color:#1d1d1d;
    }

    .top-controls{
        display:flex;
        align-items:center;
        gap:16px;
    }

    .custom-input{
        height:48px;
        border:1px solid #e7e2dc;
        background:white;
        border-radius:16px;
        padding:0 18px;
        font-size:15px;
        color:#4b4b4b;
        outline:none;
        transition:.2s;
    }

    .custom-input:focus{
        border-color:#c7b29a;
        box-shadow:0 0 0 4px rgba(166,124,82,.08);
    }

    .filter-box{
        width:130px;
    }

    .search-box{
        width:340px;
    }

    .brown-btn{
        border:none;
        background:linear-gradient(135deg,#7a4b24,#4e2d13);
        color:white;
        height:52px;
        padding:0 24px;
        border-radius:16px;
        font-weight:600;
        font-size:15px;
        display:flex;
        align-items:center;
        gap:10px;
        transition:.2s;
        box-shadow:0 10px 25px rgba(82,46,19,.15);
    }

    .brown-btn:hover{
        transform:translateY(-2px);
    }

    .card-custom{
        background:white;
        border:1px solid #ece7e2;
        border-radius:28px;
        padding:28px;
        box-shadow:
            0 4px 20px rgba(0,0,0,.03);
    }

    .soft-icon{
        width:56px;
        height:56px;
        border-radius:18px;
        background:#f6f1eb;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:24px;
        color:#7b4a24;
    }

    .status-badge{
        display:inline-flex;
        align-items:center;
        padding:7px 14px;
        border-radius:999px;
        font-size:14px;
        font-weight:600;
        background:#e9f2ff;
        color:#2570d8;
    }

    .export-btn{
        height:48px;
        border-radius:16px;
        padding:0 24px;
        border:1px solid #d8d1ca;
        background:white;
        font-weight:600;
        color:#3f3f3f;
        display:flex;
        align-items:center;
        gap:10px;
        transition:.2s;
    }

    .export-btn:hover{
        background:#faf8f6;
    }

    .finish-btn{
        height:48px;
        border:none;
        border-radius:16px;
        padding:0 28px;
        background:#2faa39;
        color:white;
        font-weight:600;
        display:flex;
        align-items:center;
        gap:10px;
        box-shadow:0 10px 20px rgba(47,170,57,.18);
    }

    .section-title{
        font-size:30px;
        font-weight:700;
        color:#1f1f1f;
    }

    .info-block{
        display:flex;
        align-items:center;
        gap:16px;
        padding-right:24px;
    }

    .info-separator{
        border-right:1px solid #eee8e3;
        height:72px;
    }

    .info-icon{
        width:52px;
        height:52px;
        border-radius:16px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:22px;
        background:#f6f1eb;
        color:#7a4b24;
    }

    .info-label{
        font-size:14px;
        color:#8a8a8a;
        margin-bottom:4px;
    }

    .info-value{
        font-size:17px;
        font-weight:700;
        color:#1f1f1f;
    }

    .info-sub{
        font-size:14px;
        color:#8f8f8f;
        margin-top:2px;
    }

    .table-custom{
        margin:0;
        border-collapse:separate;
        border-spacing:0;
    }

    .table-custom thead th{
        font-size:14px;
        font-weight:600;
        color:#8d8d8d;
        border-bottom:1px solid #f0ebe6;
        padding:18px 20px;
        background:transparent;
    }

    .table-custom tbody td{
        padding:22px 20px;
        vertical-align:middle;
        border-bottom:1px solid #f5f1ed;
        font-size:15px;
        color:#2d2d2d;
    }

    .ingredient-icon{
        width:42px;
        height:42px;
        border-radius:14px;
        background:#faf6f2;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:20px;
    }

    .stock-badge{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        padding:8px 14px;
        border-radius:999px;
        font-size:13px;
        font-weight:600;
    }

    .safe{
        background:#edf8ea;
        color:#46a045;
    }

    .warning{
        background:#fff3e7;
        color:#ef8a1c;
    }

    .danger{
        background:#ffeaea;
        color:#e14b4b;
    }

    /* QUEUE BADGES */

    .queue-badge{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:8px;
        padding:8px 14px;
        border-radius:999px;
        font-size:13px;
        font-weight:600;
        border:none;
        cursor:pointer;
        transition:.2s ease;
    }

    .queue-badge:hover{
        transform:translateY(-1px);
        opacity:.92;
    }

    .queue-progress{
        background:#e8f2ff;
        color:#2d73db;
    }

    .queue-waiting{
        background:#fff3e6;
        color:#ef8d1d;
    }

    .queue-finished{
        background:#edf8ea;
        color:#41a041;
    }

    .queue-cancelled{
        background:#ffeaea;
        color:#e14b4b;
    }

    /* STATUS DOT */

    .status-dot{
        width:8px;
        height:8px;
        border-radius:50%;
        background:currentColor;
    }

    /* DROPDOWN */

    .status-dropdown{
        border-radius:16px;
        padding:10px;
        min-width:220px;
        border:1px solid #ece7e2 !important;
    }

    .status-dropdown .dropdown-item{
        border-radius:10px;
        padding:10px 12px;
        font-size:14px;
        transition:.2s ease;
    }

    .status-dropdown .dropdown-item:hover{
        background:#f7f5f2;
    }

    .mini-dot{
        width:8px;
        height:8px;
        border-radius:50%;
    }

</style>

<div class="main-content">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div class="d-flex align-items-center gap-4">

            <h1 class="page-title mb-0">
                PRODUCTION
            </h1>

            <div class="top-controls">

                <select class="custom-input filter-box">
                    <option>Filter</option>
                </select>

                <select class="custom-input filter-box">
                    <option>Filter</option>
                </select>

                <div class="position-relative">

                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>

                    <input
                        type="text"
                        class="custom-input search-box ps-5"
                        placeholder="Search..."
                    >

                </div>

            </div>

        </div>

        <button class="brown-btn">
            <i class="bi bi-plus-lg"></i>
            Add Production
        </button>

    </div>

    {{-- TOP CARD --}}
    <div class="card-custom mb-4">

        <div class="d-flex justify-content-between align-items-center">

            <div class="d-flex align-items-center gap-4">

                <div class="soft-icon">
                    <i class="bi bi-clipboard-check"></i>
                </div>

                <div>

                    <h2 class="section-title mb-2">
                        Produksi #002
                    </h2>

                    <span class="status-badge">
                        Work in Progress
                    </span>

                </div>

            </div>

            <div class="d-flex gap-3">

                <button class="export-btn">
                    <i class="bi bi-download"></i>
                    Export
                </button>

                <button class="finish-btn">
                    <i class="bi bi-check-circle"></i>
                    Finish
                </button>

            </div>

        </div>

    </div>

    {{-- DETAIL --}}
    <div class="card-custom mb-4">

        <div class="d-flex align-items-center gap-3 mb-5">

            <div class="soft-icon">
                <i class="bi bi-card-checklist"></i>
            </div>

            <h3 class="fw-bold mb-0">
                Detail Produksi
            </h3>

        </div>

        <div class="row align-items-center">

            <div class="col">

                <div class="info-block">

                    <div class="info-icon">
                        <i class="bi bi-cup-hot"></i>
                    </div>

                    <div>
                        <div class="info-label">
                            Kuantitas Produksi
                        </div>

                        <div class="info-value">
                            250
                        </div>

                        <div class="info-sub">
                            cups
                        </div>
                    </div>

                </div>

            </div>

            <div class="col-auto">
                <div class="info-separator"></div>
            </div>

            <div class="col">

                <div class="info-block">

                    <div class="info-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>

                    <div>
                        <div class="info-label">
                            Status
                        </div>

                        <span class="status-badge">
                            Work in Progress
                        </span>
                    </div>

                </div>

            </div>

            <div class="col-auto">
                <div class="info-separator"></div>
            </div>

            <div class="col">

                <div class="info-block">

                    <div class="info-icon">
                        <i class="bi bi-calendar-event"></i>
                    </div>

                    <div>
                        <div class="info-label">
                            Start
                        </div>

                        <div class="info-value">
                            23 Mei 2024
                        </div>

                        <div class="info-sub">
                            08:00
                        </div>
                    </div>

                </div>

            </div>

            <div class="col-auto">
                <div class="info-separator"></div>
            </div>

            <div class="col">

                <div class="info-block">

                    <div class="info-icon">
                        <i class="bi bi-flag"></i>
                    </div>

                    <div>
                        <div class="info-label">
                            Finish
                        </div>

                        <div class="info-value">
                            23 Mei 2024
                        </div>

                        <div class="info-sub">
                            16:00
                        </div>
                    </div>

                </div>

            </div>

            <div class="col-auto">
                <div class="info-separator"></div>
            </div>

            <div class="col">

                <div class="info-block">

                    <div class="info-icon">
                        <i class="bi bi-wallet2"></i>
                    </div>

                    <div>
                        <div class="info-label">
                            HPP / produk
                        </div>

                        <div class="info-value">
                            Rp 8.250
                        </div>
                    </div>

                </div>

            </div>

            <div class="col-auto">
                <div class="info-separator"></div>
            </div>

            <div class="col">

                <div class="info-block">

                    <div class="info-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>

                    <div>
                        <div class="info-label">
                            Total HPP
                        </div>

                        <div class="info-value">
                            Rp 2.062.500
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- DAFTAR PRODUKSI --}}
    <div class="card-custom mb-4">

        <div class="d-flex align-items-center gap-3 mb-4">

            <div class="soft-icon">
                <i class="bi bi-list-task"></i>
            </div>

            <h3 class="fw-bold mb-0">
                Daftar Produksi
            </h3>

        </div>

        <table class="table table-custom align-middle">

            <thead>
            <tr>
                <th>Product</th>
                <th>Jumlah (cups)</th>
                <th>Status</th>
            </tr>
            </thead>

            <tbody>

            {{-- ITEM 1 --}}
            <tr>

                <td>
                    <div class="d-flex flex-column">

                        <strong>Caramel Latte</strong>

                        <small class="text-secondary">
                            Espresso • Fresh Milk • Caramel
                        </small>

                    </div>
                </td>

                <td>
                    <span class="fw-semibold">
                        120 cups
                    </span>
                </td>

                <td>

                    <div class="dropdown">

                        <button
                            class="queue-badge queue-progress dropdown-toggle border-0"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            <span class="status-dot"></span>
                            In Progress
                        </button>

                        <ul class="dropdown-menu shadow-sm border-0 status-dropdown">

                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                                    <span class="mini-dot bg-secondary"></span>
                                    Waiting
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                                    <span class="mini-dot bg-primary"></span>
                                    In Progress
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                                    <span class="mini-dot bg-success"></span>
                                    Finished
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                                    <span class="mini-dot bg-danger"></span>
                                    Cancelled
                                </a>
                            </li>

                        </ul>

                    </div>

                </td>

            </tr>

            {{-- ITEM 2 --}}
            <tr>

                <td>
                    <div class="d-flex flex-column">

                        <strong>Matcha Latte</strong>

                        <small class="text-secondary">
                            Matcha • Milk • Sugar Syrup
                        </small>

                    </div>
                </td>

                <td>
                    <span class="fw-semibold">
                        80 cups
                    </span>
                </td>

                <td>

                    <div class="dropdown">

                        <button
                            class="queue-badge queue-waiting dropdown-toggle border-0"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            <span class="status-dot"></span>
                            Waiting
                        </button>

                        <ul class="dropdown-menu shadow-sm border-0 status-dropdown">

                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                                    <span class="mini-dot bg-secondary"></span>
                                    Waiting
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                                    <span class="mini-dot bg-primary"></span>
                                    In Progress
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                                    <span class="mini-dot bg-success"></span>
                                    Finished
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                                    <span class="mini-dot bg-danger"></span>
                                    Cancelled
                                </a>
                            </li>

                        </ul>

                    </div>

                </td>

            </tr>

            {{-- ITEM 3 --}}
            <tr>

                <td>
                    <div class="d-flex flex-column">

                        <strong>Americano</strong>

                        <small class="text-secondary">
                            Espresso • Water
                        </small>

                    </div>
                </td>

                <td>
                    <span class="fw-semibold">
                        50 cups
                    </span>
                </td>

                <td>

                    <div class="dropdown">

                        <button
                            class="queue-badge queue-finished dropdown-toggle border-0"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            <span class="status-dot"></span>
                            Finished
                        </button>

                        <ul class="dropdown-menu shadow-sm border-0 status-dropdown">

                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                                    <span class="mini-dot bg-secondary"></span>
                                    Waiting
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                                    <span class="mini-dot bg-primary"></span>
                                    In Progress
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                                    <span class="mini-dot bg-success"></span>
                                    Finished
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="#">
                                    <span class="mini-dot bg-danger"></span>
                                    Cancelled
                                </a>
                            </li>

                        </ul>

                    </div>

                </td>

            </tr>

            </tbody>

        </table>

    </div>
    {{-- BAHAN --}}
    <div class="card-custom">

        <div class="d-flex align-items-center gap-3 mb-4">

            <div class="soft-icon">
                <i class="bi bi-box-seam"></i>
            </div>

            <h3 class="fw-bold mb-0">
                Bahan
            </h3>

        </div>

        <table class="table table-custom">

            <thead>
            <tr>
                <th>Bahan</th>
                <th>Total Kebutuhan</th>
                <th>Satuan</th>
                <th>Stok Tersedia</th>
                <th>Status</th>
            </tr>
            </thead>

            <tbody>

            <tr>
                <td>
                    <div class="d-flex align-items-center gap-3">
                        <div class="ingredient-icon">🥛</div>
                        <strong>Susu Segar</strong>
                    </div>
                </td>

                <td>15.000</td>
                <td>ml</td>

                <td class="text-success fw-semibold">
                    18.500 ml
                </td>

                <td>
                    <span class="stock-badge safe">
                        Aman
                    </span>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="d-flex align-items-center gap-3">
                        <div class="ingredient-icon">☕</div>
                        <strong>Espresso</strong>
                    </div>
                </td>

                <td>4.000</td>
                <td>ml</td>

                <td class="text-success fw-semibold">
                    7.200 ml
                </td>

                <td>
                    <span class="stock-badge safe">
                        Aman
                    </span>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="d-flex align-items-center gap-3">
                        <div class="ingredient-icon">🍯</div>
                        <strong>Sirup Caramel</strong>
                    </div>
                </td>

                <td>1.200</td>
                <td>ml</td>

                <td class="text-warning fw-semibold">
                    950 ml
                </td>

                <td>
                    <span class="stock-badge warning">
                        Perlu Refill
                    </span>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="d-flex align-items-center gap-3">
                        <div class="ingredient-icon">💧</div>
                        <strong>Air</strong>
                    </div>
                </td>

                <td>10.000</td>
                <td>ml</td>

                <td class="text-danger fw-semibold">
                    7.200 ml
                </td>

                <td>
                    <span class="stock-badge danger">
                        Stok Rendah
                    </span>
                </td>
            </tr>

            </tbody>

        </table>

    </div>

</div>

@endsection