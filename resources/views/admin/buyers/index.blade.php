@extends('admin.layouts.app')

@section('title', 'Data Buyer')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Data Buyer</h1>
</div>

<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <label class="text-sm text-gray-600">Tampilkan</label>
            <select id="per-page" class="border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
        <div class="w-[25%] relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" id="search-input" placeholder="Cari buyer..."
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 text-left text-sm font-semibold text-gray-600">
                    <th class="px-6 py-4 cursor-pointer hover:text-gray-900" data-sort="name">
                        Nama <span class="sort-icon text-xs">↕</span>
                    </th>
                    <th class="px-6 py-4 cursor-pointer hover:text-gray-900" data-sort="email">
                        Email <span class="sort-icon text-xs">↕</span>
                    </th>
                    <th class="px-6 py-4 cursor-pointer hover:text-gray-900" data-sort="phone">
                        Telepon <span class="sort-icon text-xs">↕</span>
                    </th>
                    <th class="px-6 py-4">Alamat</th>
                    <th class="px-6 py-4 cursor-pointer hover:text-gray-900" data-sort="bank">
                        Bank <span class="sort-icon text-xs">↕</span>
                    </th>
                    <th class="px-6 py-4 cursor-pointer hover:text-gray-900" data-sort="bank_number">
                        No. Rekening <span class="sort-icon text-xs">↕</span>
                    </th>
                    <th class="px-6 py-4 cursor-pointer hover:text-gray-900" data-sort="created_at">
                        Bergabung <span class="sort-icon text-xs">↕</span>
                    </th>
                </tr>
            </thead>
            <tbody id="table-body" class="divide-y divide-gray-200">
                @forelse ($buyers as $buyer)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $buyer->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $buyer->email }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $buyer->phone ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-600 max-w-xs truncate">{{ $buyer->address ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $buyer->bank ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $buyer->bank_number ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $buyer->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">Tidak ada data buyer.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="pagination" class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
        <div class="text-sm text-gray-600">
            Menampilkan <span id="from">{{ $buyers->firstItem() ?? 0 }}</span> - <span id="to">{{ $buyers->lastItem() ?? 0 }}</span>
            dari <span id="total">{{ $buyers->total() }}</span>
        </div>
        <div id="pagination-links" class="flex items-center gap-1">
            {{ $buyers->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    let currentSort = 'created_at';
    let currentDir = 'desc';
    let currentSearch = '';
    let currentPerPage = 10;

    const searchInput = document.getElementById('search-input');
    const perPageSelect = document.getElementById('per-page');
    const tableBody = document.getElementById('table-body');
    const paginationLinks = document.getElementById('pagination-links');
    const fromSpan = document.getElementById('from');
    const toSpan = document.getElementById('to');
    const totalSpan = document.getElementById('total');

    function updateSortIcons(field, dir) {
        document.querySelectorAll('[data-sort]').forEach(th => {
            const icon = th.querySelector('.sort-icon');
            if (th.dataset.sort === field) {
                icon.textContent = dir === 'asc' ? '↑' : '↓';
            } else {
                icon.textContent = '↕';
            }
        });
    }

    function fetchData() {
        const params = new URLSearchParams({
            page: currentPage,
            sort_field: currentSort,
            sort_dir: currentDir,
            search: currentSearch,
            per_page: currentPerPage
        });

        fetch(`{{ route('admin.buyers.data') }}?${params}`)
            .then(res => res.json())
            .then(data => {
                let html = '';
                if (data.data.length === 0) {
                    html = '<tr><td colspan="7" class="px-6 py-12 text-center text-gray-500">Tidak ada data buyer.</td></tr>';
                } else {
                    data.data.forEach(buyer => {
                        html += `<tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-800">${buyer.name}</td>
                            <td class="px-6 py-4 text-gray-600">${buyer.email}</td>
                            <td class="px-6 py-4 text-gray-600">${buyer.phone || '-'}</td>
                            <td class="px-6 py-4 text-gray-600 max-w-xs truncate">${buyer.address || '-'}</td>
                            <td class="px-6 py-4 text-gray-600">${buyer.bank || '-'}</td>
                            <td class="px-6 py-4 text-gray-600">${buyer.bank_number || '-'}</td>
                            <td class="px-6 py-4 text-gray-600">${new Date(buyer.created_at).toLocaleDateString('id-ID', {day: '2-digit', month: 'short', year: 'numeric'})}</td>
                        </tr>`;
                    });
                }
                tableBody.innerHTML = html;

                fromSpan.textContent = data.from || 0;
                toSpan.textContent = data.to || 0;
                totalSpan.textContent = data.total;

                // Build pagination with <<, <, numbers, >, >>
                const c = data.current_page;
                const last = data.last_page;
                let pagHtml = '';
                // First page
                pagHtml += `<button class="px-2.5 py-1 text-sm border rounded hover:bg-gray-100" data-page="1"${c === 1 ? ' disabled' : ''}>&laquo;</button>`;
                // Prev page
                pagHtml += `<button class="px-2.5 py-1 text-sm border rounded hover:bg-gray-100" data-page="${c - 1}"${!data.prev_page_url ? ' disabled' : ''}>&lsaquo;</button>`;
                // Numbered pages
                const start = Math.max(1, c - 2);
                const end = Math.min(last, c + 2);
                for (let i = start; i <= end; i++) {
                    if (i === c) {
                        pagHtml += `<button class="px-3 py-1 text-sm border rounded bg-indigo-600 text-white" disabled>${i}</button>`;
                    } else {
                        pagHtml += `<button class="px-3 py-1 text-sm border rounded hover:bg-gray-100" data-page="${i}">${i}</button>`;
                    }
                }
                // Next page
                pagHtml += `<button class="px-2.5 py-1 text-sm border rounded hover:bg-gray-100" data-page="${c + 1}"${!data.next_page_url ? ' disabled' : ''}>&rsaquo;</button>`;
                // Last page
                pagHtml += `<button class="px-2.5 py-1 text-sm border rounded hover:bg-gray-100" data-page="${last}"${c === last ? ' disabled' : ''}>&raquo;</button>`;
                paginationLinks.innerHTML = pagHtml;

                updateSortIcons(currentSort, currentDir);
            });
    }

    // Sort click
    document.querySelectorAll('[data-sort]').forEach(th => {
        th.addEventListener('click', function() {
            const field = this.dataset.sort;
            if (currentSort === field) {
                currentDir = currentDir === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort = field;
                currentDir = 'asc';
            }
            currentPage = 1;
            fetchData();
        });
    });

    // Search with debounce
    let searchTimer;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            currentSearch = this.value;
            currentPage = 1;
            fetchData();
        }, 400);
    });

    // Per page change
    perPageSelect.addEventListener('change', function() {
        currentPerPage = parseInt(this.value);
        currentPage = 1;
        fetchData();
    });

    // Pagination click delegation
    paginationLinks.addEventListener('click', function(e) {
        const page = e.target.dataset.page;
        if (page && !e.target.disabled) {
            currentPage = parseInt(page);
            fetchData();
        }
    });
});
</script>
@endpush