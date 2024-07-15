<div class="flex basis-full flex-col space-y-5 rounded-lg border border-gray-200 bg-white px-5 py-5 shadow dark:border-gray-700 dark:bg-gray-800">
	<h5 class="mb-5 text-center text-2xl font-medium tracking-tight text-gray-900 dark:text-white">
		Kepuasan Layanan PST
	</h5>
	<form action="{{ route('kuisioner') }}" method="GET">
		<input type="hidden" name="step" value="2">
		<input type="hidden" name="question" value="1">
		<div class="mb-5">
			<label for="layanan" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Tujuan Layanan</label>
			<select id="layanan" name="layanan" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
				<option value="" hidden>-Pilih-</option>
				@foreach ($layanan as $item)
					<option value="{{ $item->value }}" {{ old('layanan') == $item->value ? 'selected' : '' }}>{{ $item->label }}</option>
				@endforeach
			</select>
			@error('layanan')
				<p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
			@enderror
		</div>
		<div class="mb-5">
			<label for="media" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Media Yang Diakses</label>
			<select id="media" name="media" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
				<option value="" hidden>-Pilih-</option>
				@foreach ($media as $item)
					<option value="{{ $item->value }}" {{ old('media') == $item->value ? 'selected' : '' }}>{{ $item->label }}</option>
				@endforeach
			</select>
			@error('media')
				<p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
			@enderror
		</div>
		<div class="mb-5">
			<label for="tgl" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Tanggal Akses</label>
			<input type="date" id="tgl" name="tgl" value="{{ old('tgl') }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500">
			@error('tgl')
				<p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
			@enderror
		</div>
		<div class="mb-5 text-right">
			<x-button.submit text="Selanjutnya" id="submit-personal-info" />
		</div>
	</form>
</div>
