<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Error Diagnostics | {{ config('app.name', 'LaunchShop') }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  <style>
    body {
      background: #090d16;
      color: #f1f5f9;
      font-family: 'Plus Jakarta Sans', sans-serif;
      min-height: 100vh;
      padding: 30px 20px;
    }
    .code-font {
      font-family: 'JetBrains Mono', monospace;
    }
    .diagnostic-card {
      background: #0f172a;
      border: 1px solid #1e293b;
      border-radius: 16px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
      overflow: hidden;
    }
    .diagnostic-header {
      background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(15, 23, 42, 0.6) 100%);
      border-bottom: 1px solid rgba(239, 68, 68, 0.25);
      padding: 24px 30px;
    }
    .error-pill {
      background: rgba(239, 68, 68, 0.2);
      color: #f87171;
      border: 1px solid rgba(239, 68, 68, 0.4);
      padding: 6px 14px;
      border-radius: 50px;
      font-size: 13px;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }
    .error-title {
      color: #fca5a5;
      font-size: 22px;
      font-weight: 800;
      margin-top: 14px;
      margin-bottom: 4px;
      word-break: break-word;
    }
    .meta-box {
      background: #1e293b;
      border: 1px solid #334155;
      border-radius: 10px;
      padding: 14px 18px;
      margin-bottom: 20px;
    }
    .code-preview {
      background: #020617;
      border: 1px solid #1e293b;
      border-radius: 10px;
      padding: 16px;
      overflow-x: auto;
      font-size: 13px;
    }
    .code-line {
      display: flex;
      gap: 16px;
      line-height: 1.6;
    }
    .line-num {
      color: #64748b;
      user-select: none;
      min-width: 45px;
      text-align: right;
    }
    .line-content {
      color: #cbd5e1;
      white-space: pre-wrap;
    }
    .line-error {
      background: rgba(239, 68, 68, 0.2);
      color: #fca5a5;
      font-weight: 700;
      border-left: 3px solid #ef4444;
      padding-left: 8px;
      margin-left: -11px;
    }
    .trace-box {
      background: #020617;
      border: 1px solid #1e293b;
      border-radius: 10px;
      padding: 16px;
      max-height: 400px;
      overflow-y: auto;
      font-size: 12px;
      color: #94a3b8;
    }
  </style>
</head>
<body>
  <div class="container" style="max-width: 1060px;">
    
    <div class="d-flex align-items-center justify-content-between mb-3">
      <div class="d-flex align-items-center gap-2">
        <span class="badge bg-primary px-3 py-2 text-uppercase font-monospace" style="letter-spacing: 0.5px;">
          <i class="fas fa-terminal me-1"></i> Admin Error Inspector
        </span>
        <span class="text-muted small">Shown only for Admin users & development</span>
      </div>
      <div>
        <a href="javascript:history.back();" class="btn btn-outline-light btn-sm me-2">
          <i class="fas fa-arrow-left me-1"></i> Go Back
        </a>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-sm">
          <i class="fas fa-home me-1"></i> Dashboard
        </a>
      </div>
    </div>

    <div class="diagnostic-card">
      <div class="diagnostic-header">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
          <div class="error-pill code-font">
            <i class="fas fa-circle-exclamation"></i>
            {{ get_class($exception) }}
          </div>
          <span class="badge bg-dark border border-secondary code-font text-muted">
            HTTP 500 &bull; {{ date('Y-m-d H:i:s') }}
          </span>
        </div>

        <h1 class="error-title code-font">
          {{ $exception->getMessage() ?: 'An unhandled exception occurred during processing' }}
        </h1>
        <div class="text-secondary code-font small mt-2">
          <i class="fas fa-file-code text-warning me-1"></i>
          {{ $exception->getFile() }}:<strong class="text-white">{{ $exception->getLine() }}</strong>
        </div>
      </div>

      <div class="p-4">
        <!-- Request Details -->
        <div class="meta-box">
          <div class="row g-3">
            <div class="col-md-3">
              <span class="text-muted small d-block font-weight-bold text-uppercase">HTTP Method</span>
              <span class="badge bg-{{ request()->isMethod('post') ? 'danger' : 'info' }} code-font mt-1">
                {{ request()->method() }}
              </span>
            </div>
            <div class="col-md-9">
              <span class="text-muted small d-block font-weight-bold text-uppercase">Requested URL</span>
              <span class="code-font text-info small" style="word-break: break-all;">
                {{ request()->fullUrl() }}
              </span>
            </div>
          </div>
        </div>

        <!-- Code Snippet Around Error -->
        @php
          $fileLines = [];
          $errLine = $exception->getLine();
          $errFile = $exception->getFile();
          if (file_exists($errFile) && is_readable($errFile)) {
              $allLines = file($errFile);
              $startLine = max(1, $errLine - 6);
              $endLine = min(count($allLines), $errLine + 6);
              for ($i = $startLine; $i <= $endLine; $i++) {
                  $fileLines[$i] = $allLines[$i - 1];
              }
          }
        @endphp

        @if (!empty($fileLines))
          <div class="mb-4">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="font-weight-bold text-light small text-uppercase">
                <i class="fas fa-code text-info me-1"></i> Code Snippet
              </span>
              <span class="text-muted code-font small">{{ basename($errFile) }}:{{ $errLine }}</span>
            </div>
            <div class="code-preview code-font">
              @foreach ($fileLines as $ln => $code)
                <div class="code-line {{ $ln == $errLine ? 'line-error' : '' }}">
                  <span class="line-num">{{ $ln }}</span>
                  <span class="line-content">{{ rtrim($code) }}</span>
                </div>
              @endforeach
            </div>
          </div>
        @endif

        <!-- Stack Trace Collapsible -->
        <div>
          <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="font-weight-bold text-light small text-uppercase">
              <i class="fas fa-layer-group text-warning me-1"></i> Stack Trace
            </span>
          </div>
          <pre class="trace-box code-font m-0">{{ $exception->getTraceAsString() }}</pre>
        </div>

        <div class="text-center mt-4 pt-3 border-top border-secondary">
          <button onclick="window.location.reload();" class="btn btn-outline-light me-2">
            <i class="fas fa-rotate-right me-1"></i> Try Again / Reload
          </button>
          <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-1"></i> Return to Safe Dashboard
          </a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>