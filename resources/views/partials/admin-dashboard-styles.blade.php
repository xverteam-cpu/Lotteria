<style>
  * {
    font-family: 'Inter', sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
  }

  body {
    background-color: #F8FAFC;
  }

  .admin-shell {
    max-width: 1400px;
    margin: 0 auto;
    padding: 40px 32px;
    min-height: 100vh;
    background-color: #F8FAFC;
  }

  /* Top Navigation Bar */
  .admin-nav {
    display: flex;
    gap: 16px;
    padding: 20px 32px;
    background-color: #ffffff;
    border: 1px solid #E5E7EB;
    border-radius: 16px;
    position: sticky;
    top: 0;
    z-index: 100;
    flex-wrap: wrap;
    align-items: center;
    box-shadow: 0 1px 2px rgba(15,23,42,0.05), 0 8px 30px rgba(15,23,42,0.06);
  }

  .admin-nav-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    height: 44px;
    padding: 0 18px;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    background-color: #ffffff;
    color: #475569;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all .18s ease;
    white-space: nowrap;
  }

  .admin-nav-btn:hover {
    background-color: #F8FAFC;
    border-color: #E5E7EB;
    color: #0F172A;
  }

  .admin-nav-btn.active {
    background-color: #C8102E;
    border-color: #C8102E;
    color: #ffffff;
    box-shadow: 0 8px 20px rgba(200,16,46,.20);
  }

  /* Header Section */
  .admin-header {
    padding: 48px 32px 40px;
    background-color: #ffffff;
    border: 1px solid #E5E7EB;
    border-radius: 18px;
    position: sticky;
    top: 88px;
    z-index: 99;
    box-shadow: 0 1px 2px rgba(15,23,42,.05), 0 8px 30px rgba(15,23,42,.06);
    margin-top: 20px;
  }

  .admin-header-content {
    max-width: 1400px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 24px;
  }

  .admin-header-info {
    flex: 1;
  }

  .admin-title {
    margin: 0;
    font-size: 36px;
    font-weight: 700;
    color: #0F172A;
    letter-spacing: -0.5px;
  }

  .admin-copy {
    margin: 12px 0 0;
    font-size: 15px;
    line-height: 1.6;
    color: #475569;
    font-weight: 500;
  }

  .admin-header-actions {
    display: flex;
    gap: 12px;
    align-items: center;
  }

  .header-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 44px;
    padding: 0 22px;
    border: none;
    border-radius: 12px;
    background-color: #C8102E;
    color: #ffffff;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: transform .18s ease, box-shadow .18s ease, background-color .18s ease;
    white-space: nowrap;
    text-decoration: none;
  }

  .header-btn:hover {
    transform: translateY(-1px);
    background-color: #A40F25;
    box-shadow: 0 4px 16px rgba(200,16,46,.18);
  }

  .header-btn:active {
    transform: translateY(0) scale(0.98);
  }

  /* Content Wrapper */
  .admin-content {
    padding: 24px;
    max-width: 1400px;
    margin: 0 auto;
  }

  /* Statistics Grid */
  .summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
  }

  .summary-card {
    background-color: #ffffff;
    border: 1px solid #E5E7EB;
    border-radius: 18px;
    padding: 28px;
    box-shadow: 0 1px 2px rgba(15,23,42,.05), 0 8px 30px rgba(15,23,42,.06);
    transition: transform .18s ease, box-shadow .18s ease;
    position: relative;
    overflow: hidden;
  }

  .summary-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(15,23,42,.08);
    border-color: #D1D5DB;
  }

  .summary-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #c40000, #e31b23);
  }

  .summary-card-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background-color: #fef2f2;
    color: #c40000;
    font-size: 20px;
    margin-bottom: 12px;
  }

  .summary-value {
    display: block;
    font-size: 32px;
    font-weight: 700;
    color: #1f2937;
    line-height: 1;
    margin-bottom: 4px;
  }

  .summary-label {
    display: block;
    font-size: 12px;
    font-weight: 500;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  /* Users Panel */
  .users-panel {
    background-color: #ffffff;
    border: 1px solid #E5E7EB;
    border-radius: 18px;
    box-shadow: 0 1px 2px rgba(15,23,42,.05), 0 8px 30px rgba(15,23,42,.06);
    overflow: hidden;
  }

  .users-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 28px 32px;
    border-bottom: 1px solid #E5E7EB;
    flex-wrap: wrap;
    gap: 16px;
  }

  .users-panel-title {
    flex: 1;
    min-width: 220px;
  }

  .users-panel-title-main {
    display: block;
    font-size: 22px;
    font-weight: 600;
    color: #0F172A;
    margin-bottom: 6px;
  }

  .users-panel-title-sub {
    display: block;
    font-size: 14px;
    color: #94A3B8;
    font-weight: 400;
  }

  .search-box {
    position: relative;
    flex: 1;
    min-width: 280px;
  }

  .search-box form {
    display: flex;
    gap: 8px;
  }

  .search-input {
    flex: 1;
    height: 46px;
    padding: 0 16px;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    font-size: 15px;
    color: #0F172A;
    background-color: #F8FAFC;
    transition: border-color .18s ease, box-shadow .18s ease, background-color .18s ease;
  }

  .search-input::placeholder {
    color: #94A3B8;
  }

  .search-input:focus {
    outline: none;
    background-color: #ffffff;
    border-color: #C8102E;
    box-shadow: 0 0 0 4px rgba(200,16,46,.08);
  }

  /* Table Styles */
  .table-wrap {
    overflow-x: auto;
  }

  .users-table {
    width: 100%;
    border-collapse: collapse;
  }

  .users-table thead {
    background-color: #F8FAFC;
    border-bottom: 1px solid #E5E7EB;
  }

  .users-table th {
    padding: 16px 18px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 0.08em;
  }

  .users-table td {
    padding: 18px 18px;
    border-bottom: 1px solid #EEF2F7;
    font-size: 15px;
    color: #475569;
    vertical-align: middle;
  }

  .user-cell-ip {
    font-family: 'JetBrains Mono', 'Courier New', monospace;
    font-size: 13px;
    color: #64748B;
  }

  .users-table tbody tr {
    transition: background-color .2s ease;
    height: 68px;
  }

  .users-table tbody tr:hover {
    background-color: #FAFBFC;
  }

  .user-cell-name {
    font-size: 15px;
    font-weight: 600;
    color: #0F172A;
    display: block;
    margin-bottom: 4px;
  }

  .user-cell-email {
    font-size: 14px;
    color: #94A3B8;
    font-weight: 400;
  }

  .status-badges {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
  }

  /* Status Badges */
  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 5px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
  }

  .status-badge.online {
    background-color: #ECFDF5;
    color: #059669;
  }

  .status-badge.offline {
    background-color: #F1F5F9;
    color: #64748B;
  }

  .status-badge.new {
    background-color: #FFF7ED;
    color: #EA580C;
  }

  .status-badge.admin {
    background-color: #EEF2FF;
    color: #4F46E5;
  }

  .view-link {
    display: inline-flex;
    align-items: center;
    color: #C8102E;
    font-weight: 600;
    text-decoration: none;
    font-size: 13px;
    padding: 4px 8px;
    border-radius: 8px;
    transition: all .18s ease;
    cursor: pointer;
  }

  .view-link:hover {
    text-decoration: underline;
    background-color: rgba(200,16,46,.06);
  }

  /* Pagination */
  .pagination {
    display: flex;
    justify-content: center;
    gap: 4px;
    padding: 16px 24px;
    border-top: 1px solid #f3f4f6;
  }

  .pagination a,
  .pagination span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 8px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    text-decoration: none;
    color: #374151;
    background-color: #ffffff;
    transition: all 0.2s ease;
  }

  .pagination a:hover {
    background-color: #f3f4f6;
    border-color: #9ca3af;
  }

  .pagination .active {
    background-color: #c40000;
    color: #ffffff;
    border-color: #c40000;
  }

  .pagination .disabled {
    color: #d1d5db;
    cursor: not-allowed;
  }

  /* Empty State */
  .empty-state {
    text-align: center;
    padding: 40px 24px;
    color: #9ca3af;
  }

  /* Responsive Design */
  @media (max-width: 1024px) {
    .summary-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 768px) {
    .admin-nav {
      padding: 12px 16px;
      gap: 6px;
    }

    .admin-nav-btn {
      padding: 0 12px;
      height: 32px;
      font-size: 12px;
    }

    .admin-header {
      padding: 16px 12px;
      top: 48px;
    }

    .admin-header-content {
      flex-direction: column;
      align-items: flex-start;
    }

    .admin-header-actions {
      width: 100%;
      gap: 8px;
    }

    .header-btn {
      flex: 1;
      padding: 0 12px;
      height: 32px;
      font-size: 12px;
    }

    .admin-title {
      font-size: 20px;
    }

    .admin-content {
      padding: 16px;
    }

    .summary-grid {
      grid-template-columns: 1fr;
    }

    .users-panel-header {
      flex-direction: column;
      align-items: flex-start;
    }

    .search-box {
      width: 100%;
    }

    .search-box form {
      flex-direction: column;
    }

    .users-table {
      font-size: 12px;
    }

    .users-table th,
    .users-table td {
      padding: 10px 12px;
    }

    .status-badges {
      gap: 4px;
    }

    .status-badge {
      padding: 3px 8px;
      font-size: 10px;
    }

    .summary-card {
      padding: 16px;
    }

    .summary-value {
      font-size: 28px;
    }
  }

  @media (max-width: 480px) {
    .admin-shell {
      padding: 0;
    }

    .admin-nav {
      overflow-x: auto;
      padding: 8px 12px;
    }

    .admin-nav-btn {
      flex-shrink: 0;
      padding: 0 10px;
      height: 30px;
      font-size: 11px;
    }

    .admin-header {
      padding: 16px 12px;
    }

    .admin-title {
      font-size: 18px;
    }

    .admin-copy {
      font-size: 12px;
    }

    .admin-content {
      padding: 12px;
    }

    .users-table th {
      font-size: 10px;
    }

    .users-table td {
      padding: 8px 10px;
      font-size: 11px;
    }

    .user-cell-name {
      font-size: 12px;
    }

    .user-cell-email {
      font-size: 11px;
    }

    .summary-card {
      padding: 16px;
    }

    .summary-value {
      font-size: 28px;
    }
  }
</style>