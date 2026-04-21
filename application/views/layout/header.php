<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Absensi IoT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #eef2f7;
            color: #334155;
            margin: 0;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background: linear-gradient(180deg, #1a56db 0%, #1e3a8a 100%);
            color: #fff;
            z-index: 1000;
        }

        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease, visibility 0.2s ease;
        }

        .sidebar-backdrop.show {
            opacity: 1;
            visibility: visible;
        }

        .sidebar .brand {
            padding: 22px 20px;
            font-size: 18px;
            font-weight: 800;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -0.3px;
        }

        .sidebar .brand .brand-icon {
            background: rgba(255, 255, 255, 0.2);
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .sidebar nav {
            padding: 10px 0;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.6);
            padding: 11px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13.5px;
            font-weight: 600;
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all 0.15s;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
        }

        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.12);
            border-left-color: #93c5fd;
        }

        .sidebar .nav-link i {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        .main-content {
            margin-left: 250px;
            min-height: 100vh;
        }

        .top-navbar {
            background: #fff;
            padding: 16px 28px;
            border-bottom: 1px solid #dfe6ee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .top-navbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .top-navbar h5 {
            margin: 0;
            font-weight: 800;
            font-size: 17px;
            color: #111827;
        }

        .mobile-menu-btn {
            display: none;
            width: 40px;
            height: 40px;
            border: 1px solid #dbe4f0;
            background: #fff;
            border-radius: 10px;
            align-items: center;
            justify-content: center;
            color: #1e3a8a;
            font-size: 22px;
        }

        .top-navbar .date-info {
            color: #6b7280;
            font-size: 13px;
            font-weight: 600;
        }

        .content-area {
            padding: 24px 28px;
        }

        .stat-card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }

        .stat-card .card-body {
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .stat-number {
            font-size: 30px;
            font-weight: 800;
            color: #111827;
            line-height: 1;
        }

        .stat-label {
            color: #6b7280;
            font-size: 12.5px;
            font-weight: 600;
            margin-top: 3px;
        }

        .data-card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .data-card .card-header {
            background: #fff;
            border-bottom: 1px solid #e5e9f0;
            padding: 16px 20px;
        }

        .data-card .card-header h6 {
            font-weight: 700;
            font-size: 14px;
            color: #111827;
            margin: 0;
        }

        .table thead th {
            background: #1a56db;
            color: #fff;
            font-weight: 700;
            font-size: 11.5px;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            padding: 12px 14px;
            border: none;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 11px 14px;
            font-size: 13px;
            color: #374151;
            border-bottom: 1px solid #f1f5f9;
            font-weight: 500;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        .table {
            white-space: nowrap;
        }

        .badge {
            font-weight: 600;
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 6px;
        }

        .badge-hadir {
            background: #16a34a;
        }

        .badge-telat {
            background: #dc2626;
        }

        .badge-invalid {
            background: #d97706;
        }

        .btn {
            font-size: 12.5px;
            font-weight: 600;
            border-radius: 8px;
        }

        .btn-primary {
            background: #1a56db;
            border-color: #1a56db;
        }

        .btn-primary:hover {
            background: #1e3a8a;
            border-color: #1e3a8a;
        }

        .modal-header {
            background: #1a56db;
            color: #fff;
            border: none;
            padding: 18px 22px;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-content {
            border: none;
            border-radius: 14px;
        }

        .modal-title {
            font-weight: 700;
            font-size: 15px;
        }

        .modal-body {
            padding: 22px;
        }

        .form-label {
            font-weight: 600;
            font-size: 12.5px;
            color: #4b5563;
            margin-bottom: 5px;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1.5px solid #d1d5db;
            font-size: 13.5px;
            padding: 9px 12px;
            font-weight: 500;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #1a56db;
            box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.1);
        }

        .alert {
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            border: none;
        }

        code {
            font-size: 12px;
            background: #eef2f7;
            padding: 2px 7px;
            border-radius: 5px;
            color: #1a56db;
            font-weight: 600;
        }

        .empty-state {
            padding: 36px;
            text-align: center;
            color: #9ca3af;
            font-weight: 500;
            font-size: 13.5px;
        }

        .action-group {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        @media (max-width: 991.98px) {
            body.sidebar-open {
                overflow: hidden;
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.25s ease;
                width: 280px;
                box-shadow: 0 20px 40px rgba(15, 23, 42, 0.22);
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .sidebar-backdrop {
                display: block;
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu-btn {
                display: inline-flex;
            }

            .top-navbar {
                padding: 14px 16px;
            }

            .content-area {
                padding: 16px;
            }

            .stat-card .card-body {
                padding: 18px;
            }

            .data-card .card-header {
                padding: 14px 16px;
            }
        }

        @media (max-width: 575.98px) {
            .top-navbar {
                flex-wrap: wrap;
            }

            .top-navbar h5 {
                font-size: 15px;
            }

            .top-navbar .date-info {
                width: 100%;
                font-size: 12px;
            }

            .content-area {
                padding: 14px;
            }

            .table thead th,
            .table tbody td {
                padding: 10px 12px;
            }

            .modal-dialog {
                margin: 0.75rem;
            }
        }
    </style>
</head>

<body>
