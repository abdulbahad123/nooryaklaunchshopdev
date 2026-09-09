{{-- Minimal Floating Database Status Badge --}}
@php
    $currentDb = getCurrentDatabaseName();
    $isAgencyDb = isUsingAgencyDb();
@endphp

<div class="database-status-badge {{ $isAgencyDb ? 'agency' : 'main' }}" 
     title="{{ $isAgencyDb ? 'Agency Database Active' : 'Main Database Active' }} - {{ $currentDb }}"
     data-toggle="tooltip"
     data-placement="left">
    @if($isAgencyDb)
        <i class="fas fa-database"></i> Agency DB
    @else
        <i class="fas fa-server"></i> Main DB
    @endif
</div>

<style>
    .database-status-badge {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1050;
        padding: 10px 15px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .database-status-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 8px rgba(0,0,0,0.15);
    }
    
    .database-status-badge.agency {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }
    
    .database-status-badge.main {
        background: linear-gradient(135deg, #007bff 0%, #17a2b8 100%);
        color: white;
    }
    
    .database-status-badge i {
        margin-right: 5px;
    }
</style>

<script>
    // Initialize tooltips
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
