{{-- Database Status Indicator Component --}}
@php
    $currentDb = getCurrentDatabaseName();
    $isAgencyDb = isUsingAgencyDb();
    $customDomainInfo = getCustomDomainInfo();
    $mainDb = env('DB_DATABASE', 'maindb');
    $agencyDb = env('AGENCY_DB_DATABASE', 'agencydb');
@endphp

<div class="alert {{ $isAgencyDb ? 'alert-success' : 'alert-info' }} alert-dismissible fade show" role="alert" style="margin-bottom: 20px; border-left: 4px solid {{ $isAgencyDb ? '#28a745' : '#007bff' }};">
    <div class="d-flex align-items-center">
        <div style="margin-right: 15px; font-size: 24px;">
            @if($isAgencyDb)
                <i class="fas fa-database text-success"></i>
            @else
                <i class="fas fa-server text-info"></i>
            @endif
        </div>
        <div style="flex: 1;">
            <h5 class="alert-heading mb-1" style="font-weight: 600;">
                @if($isAgencyDb)
                    <i class="fas fa-check-circle text-success"></i> Agency Database Active
                @else
                    <i class="fas fa-info-circle text-info"></i> Main Database Active
                @endif
            </h5>
            <p class="mb-1" style="font-size: 14px;">
                <strong>Current Database:</strong> 
                <code style="background: rgba(0,0,0,0.1); padding: 2px 8px; border-radius: 4px; font-size: 13px;">{{ $currentDb }}</code>
            </p>
            
            @if($customDomainInfo)
                <p class="mb-0" style="font-size: 13px;">
                    <i class="fas fa-globe text-primary"></i> 
                    <strong>Custom Domain:</strong> 
                    <span class="badge badge-primary">{{ $customDomainInfo['domain'] }}</span>
                    <span class="badge badge-secondary">User ID: {{ $customDomainInfo['user_id'] }}</span>
                </p>
            @else
                <p class="mb-0" style="font-size: 13px; color: #6c757d;">
                    <i class="fas fa-home"></i> Using main infrastructure database
                </p>
            @endif
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="font-size: 24px; opacity: 0.5;">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    
    <hr style="margin: 10px 0; opacity: 0.3;">
    
    <div style="font-size: 12px; color: #6c757d;">
        <i class="fas fa-info-circle"></i>
        @if($isAgencyDb)
            <strong>Agency Mode:</strong> All data operations are using the agency database. Custom domain is active.
        @else
            <strong>Main Mode:</strong> All data operations are using the main application database.
        @endif
    </div>
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
</style>
