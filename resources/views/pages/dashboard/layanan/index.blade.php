@extends('layouts.dashboard', [
    'breadcrumbs' => [
        'Layanan' => '#',
    ],
])
@section('title', 'Survey Kepuasan Layanan')
@section('content')
	<x-card>
		<br>
		<form action="{{ route('layanan.export') }}" method="POST">
			@csrf
		{{-- <div class="row flex space-x-5">
			<div class="mb-4 flex-3">
				<label for="tglawal" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Tanggal Awal Akses</label>
				<input type="date" id="tglawal" name="tglawal" value="{{ old('tglawal') }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
				@error('tglawal')
					<p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
				@enderror
			</div>
			<div class="mb-4 flex-3">
				<label for="tglakhir" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Tanggal Akhir Akses</label>
				<input type="date" id="tglakhir" name="tglakhir" value="{{ old('tglakhir') }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
				@error('tglakhir')
					<p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
				@enderror
			</div>
		</div>
		<div class="text-center mt-3">       
			<button type="submit" id="exportMany" name="action" value="export" class="mr-2  items-center rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
						<path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
					</svg>
					Export
				</button>
		</div> --}}
		<div class="flex justify-center">
			<div class="row flex space-x-5">
				<div class="mb-4 flex-3">
					<label for="tglawal" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Tanggal Awal Akses</label>
					<input type="date" id="tglawal" name="tglawal" value="{{ old('tglawal') }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
					@error('tglawal')
						<p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
					@enderror
				</div>
				<div class="mb-4 flex-3">
					<label for="tglakhir" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Tanggal Akhir Akses</label>
					<input type="date" id="tglakhir" name="tglakhir" value="{{ old('tglakhir') }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
					@error('tglakhir')
						<p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
					@enderror
				</div>
			</div>
		</div>
		<div class="text-center mt-3">
			<button type="submit" id="exportMany" name="action" value="export" class="mr-2 items-center rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
				{{-- <svg class="mr-2 h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"> --}}
					<path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
				</svg>
				Export
			</button>
		</div>
		
		</form>
		<div class="relative overflow-x-auto p-5 sm:rounded-lg">
			<form action="{{ route('layanan.index') }}" method="GET">
				@csrf
				<div class="flex items-center justify-between pb-4">
					<div class="flex items-center space-x-2 ml-auto">
						<input type="text" name="search" placeholder="Cari Layanan" class="px-4 py-2 border rounded">
						<button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Cari</button>
					</div>
				</div>
				<table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
					<thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
						<tr>
							<th scope="col" class="px-6 py-3">
								No
							</th>
							<th scope="col" class="px-6 py-3">
								Layanan
							</th>
							<th scope="col" class="px-6 py-3">
								Media Yang Diakses
							</th>
							<th scope="col" class="px-6 py-3">
								Jawaban
							</th>
							<th scope="col" class="px-6 py-3">
								Tanggal Akses
							</th>
							{{-- <th scope="col" class="px-6 py-3">
								Tanggal Create
							</th> --}}
							<th scope="col" class="px-6 py-3">
								Kritik & Saran
							</th>
						</tr>
					</thead>
					<tbody>
						@if (count($layanan) == 0)
							<tr>
								<td colspan="8" class="py-5 text-center italic text-red-500">Data Kosong</td>
							</tr>
						@else
							@foreach ($layanan as $item)
								<tr class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-600">
									<th scope="row"  class="break-all px-6 py-4 text-gray-900 dark:text-white">{{ $loop->iteration + $layanan->firstItem() - 1 }}</th>
									<td scope="row" class="break-all px-6 py-4 text-gray-900 dark:text-white">
										@if($item->layanan == 'perpustakaan')
											 Perpustakaan 
										@elseif($item->layanan == 'rekomstat')
											 Rekomendasi Kegiatan Statistik 
										@elseif($item->layanan == 'konstat')
											 Konsultasi Statistik 
										@else
											Penjualan Produk Statistik
										@endif
										{{-- {{ $item->layanan }} --}}
									</td>
									<td scope="row" class="break-all px-6 py-4 text-gray-900 dark:text-white">
										{{ $item->media }}
									</td>
									<td scope="row" class="break-all px-6 py-4 text-gray-900 dark:text-white">
										@if( $item->answer == 1)
											Tidak Puas
										@elseif($item->answer == 2)
											Kurang Puas
										@elseif($item->answer == 3)
											Puas
										@else
											Sangat Puas
										@endif
										{{-- {{ $item->answer }} --}}
									</td>
									<td scope="row" class="break-all px-6 py-4 text-gray-900 dark:text-white">
										{{-- {{ $item->tglakses }} --}}
										{{ \Carbon\Carbon::parse($item->tglakses)->format('d-m-Y') }}

									</td>
									{{-- <td scope="row" class="break-all px-6 py-4 text-gray-900 dark:text-white">
										{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}
									</td> --}}
									<td scope="row" class="break-all px-6 py-4 text-gray-900 dark:text-white">
										{{ $item->feedback }}
									</td>
								</tr>
							@endforeach
						@endif
					</tbody>
				</table>

				<div class="mt-5">
					{{ $layanan->links() }}
				</div>
			</form>
		</div>
	</x-card>

	<script>
		const checkAll = document.getElementById('checkbox-table-all')
		const checkboxes = document.querySelectorAll(".checkbox-item")
		checkAll.addEventListener('change', (e) => {
			checkboxes.forEach(checkbox => checkbox.checked = e.target.checked)
			updateButtonVisibility()
		})

		const updateButtonVisibility = () => {
			const deleteMany = document.getElementById("deleteMany")
			let checked = false;

			for (let i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].checked) {
					checked = true;
					break;
				}
			}

			if (checked) {
				deleteMany.classList.add('inline-flex')
				deleteMany.classList.remove('hidden')
			} else {
				deleteMany.classList.add('hidden')
				deleteMany.classList.remove('inline-flex')
			}
		}
	</script>
@endsection
